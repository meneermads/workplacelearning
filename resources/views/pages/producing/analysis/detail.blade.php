@extends('layout.HUdefault')
@section('title')
    {{ Lang::get('analysis.detail') }}
@stop
@section('content')
    <script>
        let lastColorIndex = 0;

        function getChartColor() {
            const colors = [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
            ];
            if (lastColorIndex === colors.length) {
                lastColorIndex = 0;
            }
            return colors[lastColorIndex++];
        }
    </script>

    <div class="container-fluid">
        <script>
            $(document).ready(function () {
                $(".expand-detail").click(function (e) {
                    $("#detail-" + ($(this).attr("data-id"))).toggle();
                    e.preventDefault();
                });
            });
        </script>

        @if(Auth::user()->getCurrentWorkplaceLearningPeriod() != null && Auth::user()->getCurrentWorkplaceLearningPeriod()->hasLoggedHours())

            <div class="row">
                <div class="col-lg-6">
                    <a href="{{ route('analysis-producing-choice') }}" class="btn">{{__('analyses.back-to-choice')}}</a>
                    <h1>{{ __('tips.personal-tip') }}s</h1>


                    @if($evaluatedTips->count() > 0)
                        <?php $tipCounter = 1; ?>

                        @foreach($evaluatedTips as $evaluatedTip)
                            <?php $tip = $evaluatedTip->getTip(); ?>
                            @if($tipCounter <= 3 && ($tip->likes->count() === 0 || $tip->likes[0]->type === 1))
                                <strong>{{ trans('analysis.tip') }} {{ $tipCounter }}</strong>
                                <div class="row">
                                    @if($tip->likes->count() === 0)
                                        <div class="col-md-1"
                                             style="display: inline-block; vertical-align: middle;   float: none;">

                                            <h2 class="h2" style="cursor: pointer;color: #00A1E2;"
                                                id="likeTip-{{ $tip->id }}"
                                                onclick="likeTip({{ $tip->id }}, 1)"
                                                target="_blank"><span class="glyphicon glyphicon-thumbs-up"/></h2>
                                            <h2 class="h2" style="cursor: pointer;color: #e2423b;"
                                                id="likeTip-{{ $tip->id }}"
                                                onclick="likeTip({{ $tip->id }}, -1)"
                                                target="_blank"><span class="glyphicon glyphicon-thumbs-down"/></h2>
                                        </div>@endif<!-- {{-- this html comment is a hack, allows vertical aligment ¯\_(ツ)_/¯, dont move it --}}
                                        --><div class="col-md-11" style="display: inline-block; vertical-align: middle;   float: none;">
                                        <p>{!! nl2br($evaluatedTip->getTipText()) !!}</p>
                                    </div>
                                </div>
                                <br/><br/>
                                <?php $tipCounter++; ?>
                            @endif

                        @endforeach
                    @else
                        <p>{{ __('tips.none') }}</p>
                    @endif

                </div>
            </div>



            <div class="row">
                <div class="col-md-6">
                    <h1>{{ Lang::get('analyses.analyses-statistics-title') }}</h1>

                    <h2>{{ Lang::get('analyses.time-per-category') }}</h2>
                    <canvas id="chart_hours"></canvas>
                    <script>
                        var canvasHours = document.getElementById('chart_hours');
                        var chart_hours = new Chart(canvasHours, {
                            type: 'pie',
                            data: {
                                labels: {!! $producingAnalysis->charts('hours')->labels->toJson() !!},
                                datasets: [{
                                    data: {!! $producingAnalysis->charts('hours')->data->toJson() !!},
                                    backgroundColor: [
                                        @foreach($producingAnalysis->charts('hours')->labels as $label)
                                        {{ "getChartColor(),"}}
                                        @endforeach
                                    ],
                                    hoverBackgroundColor: []
                                }]
                            },
                            options: {
                                tooltips: {
                                    enabled: true,
                                    mode: 'single',
                                    callbacks: {
                                        label: function (tooltipItem, data) {
                                            var tooltipLabel = data.labels[tooltipItem.index];
                                            var tooltipData = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                                            return tooltipLabel + ' ' + tooltipData + '%';
                                        }
                                    }
                                }
                            }
                        });
                    </script>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    {!! Form::open(array('url' => 'dummy', 'class' => 'form-horizontal')) !!}
                    <h2>{{ Lang::get('analyses.statistic') }}</h2>
                    <div class="form-group">
                        {!! Form::label('', Lang::get('analyses.average-difficulty'), array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-9"><p
                                    class="form-control-static">{{ $producingAnalysis->statistic('averageDifficulty') }}
                                ({{ Lang::get('analyses.10-most-complex') }})</p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', Lang::get('analyses.percentage-difficult'), array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-9"><p
                                    class="form-control-static">{{ $producingAnalysis->statistic('percentageDifficultTasks') }}
                                <b>{{ Lang::get('general.moeilijk') }}</b></p></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', Lang::get('analyses.percentage-work-on-own'), array('class' => 'col-sm-3 control-label')) !!}
                        <div class="col-sm-9"><p
                                    class="form-control-static">{{ $producingAnalysis->statistic('percentageAloneHours') }}
                                {{ Lang::get('analyses.percentage-work-alone') }}</p></div>
                    </div>
                    {!! Form::close() !!}
                    <canvas id="chart_categories"></canvas>

                    <script>
                        var canvas_categories = document.getElementById("chart_categories");
                        var cat_chart = new Chart(canvas_categories, {
                            type: 'bar',
                            data: {
                                labels: {!! $producingAnalysis->charts('categories')->labels->toJson() !!},
                                datasets: [{
                                    label: '{{ __('Moeilijkheidsgraad op schaal van 1-10') }}',
                                    data: {!! $producingAnalysis->charts('categories')->data->toJson() !!},
                                    backgroundColor: [
                                        @foreach($producingAnalysis->charts('categories')->labels as $label)
                                        {{ "getChartColor(),"}}
                                        @endforeach
                                    ],
                                    borderColor: [],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
                            }
                        });
                    </script>
                    <hr/>
                </div>
            </div>


            @if(count($producingAnalysis->chains()) > 0)
                <div class="row">
                    <div class="col-md-12">
                        <h2>{{ trans('analysis.detail') }}</h2>
                        <p>{{ trans('analysis.producing.ordered-list-monthly-activities') }}</p>
                        <p>{{ trans('analysis.producing.activities-lookback') }}</p>
                    </div>
                    <table class="table blockTable col-md-12">
                        <thead class="blue_tile">
                        <tr>
                            <td>{{ trans('react.date') }}</td>
                            <td>{{ trans('activity.activity') }}</td>
                            <td>{{ trans('activity.hours') }}</td>
                            <td>{{ trans('activity.finished') }}?</td>
                            <td>{{ trans('analysis.detail') }}</td>
                        </tr>
                        </thead>

                        <tbody>
                        <?php $count = 0; ?>
                        @foreach($producingAnalysis->chains() as $chain)
                            <tr class="{{ $count % 2 ? "even": "odd" }}-row">
                                <td>{{ $chain->dateText() }}</td>
                                <td>
                                    {{ $chain->descriptionText() }}
                                </td>
                                <td>
                                    {{ $chain->hoursText() }}
                                </td>
                                <td>
                                    {{ $chain->statusText() }}
                                </td>
                                <td>
                                    @if($chain->hasDetail())
                                        <a data-id="{{ $chain->first()->lap_id }}" href="#"
                                           class="expand-detail">{{ trans('analysis.producing.show-details') }}</a>
                                    @else
                                        <p>{{ trans('general.not-applicable') }}</p>
                                    @endif
                                </td>
                            </tr>


                            @if($chain->count() >= 1)
                                <tr class="odd-row" id="detail-{{ $chain->first()->lap_id }}" style="display:none;">
                                    <td colspan="5">
                                        <table class="table blockTable col-md-12">
                                            <tbody>
                                            <tr class="blue_tile">
                                                <td>{{ trans('react.date') }}</td>
                                                <td>{{ trans('activity.description') }}</td>
                                                <td>{{ trans('react.complexity') }}</td>
                                                <td>{{ trans('analysis.producing.time-spent') }}</td>
                                                <td>{{ trans('react.aid') }}</td>
                                                <td>{{ trans('general.feedback') }}</td>
                                                <td>{{ trans('general.feedforward') }}</td>
                                            </tr>
                                            @foreach($chain->raw() as $learningActProd)
                                                <?php
                                                $feedback = $learningActProd->feedback;

                                                ?>
                                                <tr>
                                                    <td>{{ date('d-m', strtotime($learningActProd->date)) }}<br/><br/>
                                                    </td>
                                                    <td>{{ $learningActProd->description }}</td>
                                                    <td>{{ ($feedback != null) ? $learningActProd->getDifficulty().": ".$feedback->notfinished : $learningActProd->getDifficulty() }}</td>
                                                    <td>{{ $learningActProd->getDurationString() }}</td>
                                                    <td>{{ $learningActProd->getResourceDetail() }}</td>
                                                    <td>{!! ($feedback != null) ? "Je was " . (($feedback->progress_satisfied == 2) ? trans('general.satisfied') : trans('general.unsatisfied')) . " ".trans('general.course-of-activity')." (<a href='".route("feedback-producing", array("id" => $feedback->fb_id))."'>". trans('analysis.detail') ."</a>)." : trans('react.none') !!}</td>
                                                    <td>{{ ($feedback != null) ? $feedback->nextstep_self : "" }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif


                            <?php $count++; ?>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            @endif

        @endif

    </div>
    <script>
        function likeTip(tipId, type) {
            const url = "{{ route('tips.like', ['id' => ':id']) }}";
            $.get(url.replace(':id', tipId) + '?type=' + type).then(function () {
                $('#likeTip-' + tipId).parent().remove();
            });
        }
    </script>

@stop
