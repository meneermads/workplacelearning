<?php
/**
 * This file (internship.blade.php) was created on 06/22/2016 at 23:59.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>

@extends('layout.HUdefault')
@section('title')
    {{ Lang::get('general.internship') }}
@stop
@section('content')
    <div class="container-fluid">
        <!-- Internship Info -->
        <div class="row">
            <!-- Current Internship -->
            <div class="col-lg-12 well-sm">
                <a href="{{ url('/profiel') }}">&lt;-  {{ Lang::get('elements.profile.internships.backtoprofile') }}</a>
            </div>

            {!! Form::open(array(
                'url' => ((is_null($period->wplp_id)) ? route('period-producing-create') : route('period-producing-update', ['id' => $period->wplp_id])),
                'data-toggle' => 'validator'))
             !!}
            <div class="col-lg-5">
                <div class="form-horizontal well">
                    <h2>
                        {{ Lang::get('elements.profile.internships.current.title') }}
                        @if(Auth::user()->getCurrentWorkplaceLearningPeriod() && $period->wplp_id == Auth::user()->getCurrentWorkplaceLearningPeriod()->wplp_id)
                            {{ Lang::get('elements.profile.internships.current.titleadditive') }}
                        @endif
                    </h2>
                    <div class="form-group">
                        <div class="col-lg-4"></div>
                        <div class="col-lg-8"><label><input type="checkbox" name="isActive" value="1" {{ ((Auth::user()->getCurrentWorkplaceLearningPeriod() != null && $period->wplp_id == Auth::user()->getUserSetting('active_internship')->setting_value) || Auth::user()->getUserSetting('active_internship') == NULL) ? "checked" : "" }}/> {{ Lang::get('elements.profile.internships.activeinternship') }}</label></div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('companyname', Lang::get('elements.profile.internships.companyname'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="companyName" maxlength="255" type="text" class="form-control" placeholder="{{Lang::get('elements.profile.internships.companyname')}}" value="{{ (is_null($workplace->wp_name)) ? old("companyName") : $workplace->wp_name }}" required />
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('companyStreet', Lang::get('elements.profile.internships.companystreet'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="companyStreet" maxlength="45" type="text" class="form-control" placeholder="{{ Lang::get('elements.profile.internships.companystreet') }}" value="{{ (is_null($workplace->street)) ? old("companyStreet") : $workplace->street }}" required />
                            <div class="help-block with-errors"></div>
                        </div>

                        {!! Form::label('companyHousenr', Lang::get('elements.profile.internships.companyhousenr'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="companyHousenr" type="text" class="form-control" placeholder="{{ Lang::get('elements.profile.internships.companyhousenr') }}" value="{{ (is_null($workplace->housenr)) ? old("companyHousenr") : $workplace->housenr }}" required />
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('companyPostalcode', Lang::get('elements.profile.internships.companypostalcode'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="companyPostalcode" type="text" class="form-control" placeholder="{{ Lang::get('elements.profile.internships.companypostalcode') }}" value="{{ (is_null($workplace->postalcode)) ? old("companyPostalcode") : $workplace->postalcode }}" required />
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('companyLocation', Lang::get('elements.profile.internships.companylocation'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="companyLocation" maxlength="255" type="text" class="form-control" placeholder="{{ Lang::get('elements.profile.internships.companylocation') }}" value="{{ (is_null($workplace->town)) ? old("companyLocation") : $workplace->town }}" required />
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('companyCountry', Lang::get('elements.profile.internships.companyCountry'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="companyCountry" maxlength="255" type="text" class="form-control" placeholder="{{ Lang::get('elements.profile.internships.companyCountry') }}" value="{{ (is_null($workplace->country)) ? old("companyCountry") : $workplace->country }}" required />
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('contactperson', Lang::get('elements.profile.internships.contactperson'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="contactPerson" maxlength="255" class="form-control" placeholder="{{ Lang::get('elements.profile.internships.contactperson') }}" value="{{ (is_null($workplace->contact_name)) ? old("contactPerson") : $workplace->contact_name }}" required />
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('contactphone', Lang::get('elements.profile.internships.contactphone'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="contactPhone" type="text" class="form-control" placeholder="{{ Lang::get('elements.profile.internships.contactphone') }}" value="{{ (is_null($workplace->contact_phone)) ? old("contactPhone") : $workplace->contact_phone }}" required />
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('contactemail', Lang::get('elements.profile.internships.contactemail'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="contactEmail" type="email" class="form-control" placeholder="{{ Lang::get('elements.profile.internships.contactemail') }}" value="{{ (is_null($workplace->contact_email)) ? old("contactEmail") : $workplace->contact_email }}"  required />
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('numdays', Lang::get('elements.profile.internships.numdays'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="numdays" type="number" class="form-control" placeholder="{{ Lang::get('elements.profile.internships.numdays') }}" value="{{ (is_null($period->nrofdays)) ? old("numdays") : $period->nrofdays }}" required />
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('numhours', Lang::get('elements.profile.internships.numhours'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-8">
                            <input name="numhours" step="0.5" type="number" max="24" class="form-control" placeholder="{{ Lang::get('elements.profile.internships.numhours') }}" value="{{ (is_null($period->hours_per_day)) ? old("numhours") : $period->hours_per_day }}"   required />
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('startdate', Lang::get('elements.profile.internships.startdate'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-6">
                            <input name="startdate" type="date" class="form-control" min="{{ date("Y-m-d", strtotime("-6 months")) }}" value="{{ date("Y-m-d", (($period->startdate) ? strtotime($period->startdate) : strtotime("now"))) }}">
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('enddate', Lang::get('elements.profile.internships.enddate'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-6">
                            <input name="enddate" type="date" class="form-control" min="{{ date("Y-m-d", strtotime("now")) }}" value="{{ date("Y-m-d", (($period->enddate) ? strtotime($period->enddate) : strtotime("tomorrow"))) }}">
                        </div>

                    </div>
                    <div class="form-group">
                        {!! Form::label('cohort', Lang::get('elements.profile.internships.cohort'), array('class' => 'col-lg-4 control-label')) !!}
                        <div class="col-lg-6">
                            <select @if($period->cohort !== null) readonly="true" disabled @endif class="form-control" name="cohort">
                                @foreach($cohorts as $cohort)

                                    @if(is_null($period->cohort))
                                        <option @if(old('cohort') == $cohort->id) selected @endif value="{{ $cohort->id }}">{{ $cohort->name }}</option>
                                    @else
                                        <option @if($period->cohort->id == $cohort->id) selected @endif value="{{ $cohort->id }}">{{ $cohort->name }}</option>
                                    @endif


                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <input type="submit" class="btn btn-info" value="{{ Lang::get("elements.profile.btnsave") }}" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 well form-group">
                <h2>{{ Lang::get('elements.profile.internships.current.titleassignment') }}</h2>
                <textarea name="internshipAssignment" rows="19" class="form-control" minlength="15" maxlength="500" data-error="{{ Lang::get('elements.profile.labels.internship-assignment-error') }}" required>{{ (old('internshipAssignment')) ? old('internshipAssignment') : $period->description }}</textarea>
                <div class="help-block with-errors"></div>
            </div>
            {!! Form::close() !!}
        </div>
        @if(!is_null($workplace->wp_name))
            <div class="row">
                <!-- Categories -->
                <div class="col-lg-5">
                    {!! Form::open(array('url' => URL::to('categorie/update', array('id'=>$period->wplp_id), true), 'class' => 'form form-horizontal well')) !!}
                    <h3>{{ Lang::get('elements.profile.categories.title') }}</h3>
                    <table class="table blockTable">
                        <thead class="blue_tile">
                        <tr>
                            <th>{{ Lang::get('elements.profile.categories.internshipname') }}</th>
                            <th>{{ Lang::get('elements.profile.categories.categoryname') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($categories as $cat)
                            <tr>
                                <input type="hidden" name="cat[{{ $cat->category_id  }}][wplp_id]" value="{{ $cat->wplp_id }}" />
                                <input type="hidden" name="cat[{{ $cat->category_id }}][cg_id]" value="{{ $cat->category_id }}" />
                                <td>{{ $workplace->wp_name
                            ." (". date('d-m-Y', strtotime($period->startdate))
                            . " - " . date('d-m-Y', strtotime($period->enddate)). ")" }}</td>
                                <td><input name="cat[{{ $cat->category_id }}][cg_label]"
                                           value="{{
                                        old("category[". $cat->category_id ."][cg_label]")
                                        ? old("category[". $cat->category_id ."][cg_label]")
                                        : $cat->category_label
                                        }}"
                                    /></td>
                            </tr>
                        @endforeach
                        <tr>
                            <input type="hidden" name="newcat[0][wplp_id]" value="{{ $period->wplp_id }}" />
                            <input type="hidden" name="newcat[0][cg_id]" value="-1" />
                            <td>{{ $workplace->wp_name }}<br />{{ "(". date('d-m-Y', strtotime($period->startdate)). " - " . date('d-m-Y', strtotime($period->enddate)). ")" }}</td>
                            <td><input name="newcat[0][cg_label]" placeholder="{{ Lang::get('elements.profile.placeholders.categoryname') }}" value="{{ old('cat[0][cg_label]') }}" /></td>
                        </tr>
                        </tbody>
                    </table>
                    <input type="submit" class="btn btn-info" value="{{ Lang::get("elements.profile.btnsave") }}" />
                    {!! Form::close() !!}
                </div>
            </div>
        @endif
    </div>
@stop
