<?php

namespace App\Http\Controllers;

use App\Analysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;

class AnalyticsController extends Controller
{
    private $analysis;

    /**
     * AnalysisController constructor.
     * @param \App\Analysis $analysis
     */
    public function __construct(\App\Analysis $analysis)
    {
        // We do this dependency injection so it's easier to mock during tests
        $this->analysis = $analysis;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $analyses = $this->analysis->all();
        return view('pages.analytics.index', compact('analyses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('pages.analytics.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $analysis = new $this->analysis($data);

        if (!$analysis->save())
            return redirect()
                ->back()
                ->withErrors(['error', Lang::get('analysis.create-error')]);

        return redirect()->route('analytics-show', $analysis->id)->with('success', Lang::get('analysis.create'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $analysis = $this->analysis->findOrFail($id);
        $analysis_result = null;

        if (!\Cache::has(Analysis::CACHE_KEY . $analysis->id))
            $analysis->refresh();
        $analysis_result = \Cache::get(Analysis::CACHE_KEY . $analysis->id);

        return view('pages.analytics.show', compact('analysis', 'analysis_result'));
    }

    public function export($id)
    {
        $analysis = $this->analysis->findOrFail($id);
        $data = null;

        if (!\Cache::has(Analysis::CACHE_KEY . $analysis->id))
            $analysis->refresh();

        $data = \Cache::get(Analysis::CACHE_KEY . $analysis->id);

        if (isset($data['error']) && $data === null)
            return abort(404);

        $d = \Excel::create('Analyse data', function ($excel) use ($data) {
            $excel->sheet('New sheet', function ($sheet) use ($data) {
                $sheet->loadView('pages.analytics.export', compact('data'));
            });
        });

        return $d->export('csv');
    }

    /**
     * Expire a cached analys
     *
     * @param Request $request
     * @return Response
     */
    public function expire(Request $request)
    {
        $data = $request->all();
        $analysis = $this->analysis->findOrFail($data['id']);
        $analysis->refresh();
        return redirect()->back()->with('success', Lang::get('analysis.query-data-expired'));
    }

    /**
     * Expire all cached analyses
     */
    public function expireAll() {
        $this->analysis->all()->each(function(Analysis $analysis) {
            $analysis->refresh();
        });

        return redirect()->route('analytics-index')->with('success', Lang::get('analysis.query-data-expired'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $analysis = $this->analysis->findOrFail($id);
        $analysis_result = null;

        if (!\Cache::has(Analysis::CACHE_KEY . $analysis->id))
            $analysis->refresh();
        $analysis_result = \Cache::get(Analysis::CACHE_KEY . $analysis->id);

        return view('pages.analytics.edit', compact('analysis', 'analysis_result'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'query' => 'required',
            'cache_duration' => 'required',
            'type_time' => 'required'
        ]);

        $analysis = $this->analysis->findOrFail($id);

        $name = Input::get('name');
        $query = Input::get('query');
        $cache_duration = Input::get('cache_duration');
        $type_time = Input::get('type_time');

        if (!$analysis->update(array(
            'name' => $name,
            'query' => $query,
            'cache_duration' => $cache_duration,
            'type_time' => $type_time
        ))
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['The analysis has not been updated']);
        }
        $analysis->refresh();
        return redirect()->action('AnalyticsController@show', [$analysis['id']])
            ->with('success', 'The analysis has been updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $analysis = $this->analysis->findOrFail($id);
        if (!$analysis->delete())
            return redirect()
                ->back()
                ->withErrors(['error', Lang::get('analysis.remove-error')]);

        return redirect()->route('analytics-index')
            ->with('success', Lang::get('analysis.removed'));
    }

}

?>
