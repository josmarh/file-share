<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Transactions;
use App\Models\DataSources;
use App\Models\EmailSubscribers;

use Mail;
use App\Mail\UserUploadNotification;
use App\Mail\TeamUploadNotification;

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
        $fileUploads = Transactions::orderBy('id','desc')->paginate(10);

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

        $fileDetails = [
            'user_name' => $uploadFile->user->name,
            'file_name' => $fileName,
        ];
        Mail::to($uploadFile->user->email)->send(new UserUploadNotification($fileDetails));

        $subscribers = EmailSubscribers::where('status', 1)->get();
        
        foreach($subscribers as $subscriber ){
            $fileDetail = [
                'file_name' => $fileName,
                'subscriber_name' => $subscriber->name,
            ];
            Mail::to($subscriber->email)->send(new TeamUploadNotification($fileDetail));
        }
        $uploadFile->save();
        
        return redirect()->route('file-uploads')->withStatus('File uploaded successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $fileUploads = Transactions::findOrFail($id);
        $headers = array(
            'Content-Type: application/pdf',
            'Content-Type: application/zip',
            'Content-Type: text/csv',
            'Content-Type: application/vnd.ms-excel',
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet ',
            'Content-Type: application/vnd.oasis.opendocument.text'
        );

        return Storage::disk('local')
                        ->download('files-upload/'.$fileUploads->file_name.'.'.$fileUploads->file_type, $fileUploads->file_hash, $headers );
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

        Storage::disk('local')->delete('files-upload/'.$fileUploads->file_name.'.'.$fileUploads->file_type);
        $fileUploads->delete();

        return redirect()->route('file-uploads')->withStatus('File deleted!');
    }
}
