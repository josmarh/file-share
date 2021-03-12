<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transactions;
use App\Models\DataSources;
use Illuminate\Support\Facades\Storage;

class FileUploadsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataSource = DataSources::orderBy('name','asc')->get();
        $fileUploads = Transactions::paginate(10);
        return view('file-uploads.index', compact('fileUploads','dataSource'));
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
            'datasource' => 'required|numeric',
            'file' => 'required|max:512000',
        ]);

        $messages = [
            'datasource.required' => 'A data source must selected',
            'file.required' => 'A file must be chosen',
        ];

        $uploadFile = new Transactions;

        if($request->hasFile('file'))
        {
            $datasourceName = DataSources::where('id', $request->get('datasource'))->value('name');
            $getFile = $request->file('file');
            
            $fileName = $datasourceName.'-'.date('dmY').'.'.$getFile->extension();
           
            $path = Storage::putFileAs(
                'files-upload', $request->file('file'), $fileName
            );
            $size = Storage::disk('local')->size('files-upload/'.$fileName);
        }

        $uploadFile->file_size = $size;
        $uploadFile->file_type = $request->file('file')->extension();
        $uploadFile->file_hash = md5($datasourceName).'.'.$request->file('file')->extension();
        $uploadFile->file_name = $datasourceName.'-'.date('dmY');
        $uploadFile->data_source_id = $request->get('datasource');
        $uploadFile->user_id = auth()->user()->id;
        $uploadFile->save();
        
        return redirect()->route('file-uploads')->withStatus('File uploaded successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fileUploads = Transactions::findOrFail($id);

        // $getFileDetails = Transactions::where('id', $id)->select('file_name', 'file_type')->first();
        Storage::disk('local')->delete('files-upload/'.$fileUploads->file_name.'.'.$fileUploads->file_type);
        $fileUploads->delete();

        return redirect()->route('file-uploads')->withStatus('File deleted!');
    }
}
