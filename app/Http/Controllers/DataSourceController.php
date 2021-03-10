<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DataSources;

class DataSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataSources = DataSources::paginate(10);
        return view('data-sources.index', compact('dataSources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('data-source.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'datasource' => 'required'
        ]);
        $datasource = new DataSources([
            'name' => $request->get('datasource')
        ]);
        $datasource->save();

        return redirect()->route('data-sources')->withStatus('Data Source successfully created.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id = $request->sourceId;
        $datasource = DataSources::findOrFail($id);

        return view('data-sources.edit', compact('datasource'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $datasource = DataSources::findOrFail($id);
        $datasource->name = $request->input('datasource');
        $datasource->save();

        return redirect()->route('data-sources')->withStatus('Data Source successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $datasource = DataSources::findOrFail($id);
        $datasource->delete();

        return redirect()->route('data-sources')->withStatus('Data Source deleted!');
    }
}
