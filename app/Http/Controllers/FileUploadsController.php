<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Log;

use App\Models\Transactions;
use App\Models\DataSources;
use App\Models\EmailSubscribers;
use App\Models\User;

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
        $fname = request()->query('file_name');
        $creator = request()->query('created_by');
        $created_from = request()->query('created_from');
        $created_to = request()->query('created_to');

        if ($fname || $creator || $created_from || $created_to){

            $fileUploads = new Transactions;
            if(isset($fname)){
                $fileUploads = $fileUploads->where('file_name', 'like', '%'.$fname.'%');
            }
            if(isset($creator)){
                $fileUploads = $fileUploads->WhereHas('user', function($q) use($creator) {
                    $q->where('name', 'like', '%'.$creator.'%');
                });
            }
            if(isset($created_from) && isset($created_to)){
                $fileUploads = $fileUploads->whereDate('created_at', '>=', $created_from)
                                            ->whereDate('created_at', '<=', $created_to);
            }

            $fileUploads = $fileUploads->sortable()->paginate(10);
            $dataSource = DataSources::orderBy('name','asc')->get();

        }else{

            $dataSource = DataSources::orderBy('name','asc')->get();
            $fileUploads = Transactions::sortable()->orderBy('id','desc')->paginate(10);
        }

        return view('file-uploads.index', compact('fileUploads','dataSource'));

    }

    public function getUserMail(Request $request)
    {
        $userMail = $request->getUser;
        $userList = User::select('id','email')
                        ->where('email','like', '%'.$userMail.'%')
                        ->get();

        return view('file-uploads.user-list', compact('userList'));
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
            // $datasourceName = DataSources::where('id', $request->get('datasource'))->value('name');
            $getFile = $request->file('file');
            $getFileName = $request->get('filename').'-'.rand();
            
            $fileName = $getFileName.'.'.$getFile->getClientOriginalExtension();
           
            $path = Storage::putFileAs(
                'files-upload', $request->file('file'), $fileName
            );
            $size = Storage::disk('local')->size('files-upload/'.$fileName);
        }

        $uploadFile->file_size = $size;
        $uploadFile->file_type = $request->file('file')->getClientOriginalExtension();
        $uploadFile->file_hash = md5($getFileName).'.'.$request->file('file')->getClientOriginalExtension();
        $uploadFile->file_name = $getFileName;
        $uploadFile->data_source_id = $request->get('datasource');
        $uploadFile->user_id = auth()->user()->id;
        $uploadFile->direct_user_mail = $request->get('direct-email');
        $uploadFile->save();

        // get user name that is available on the db for direct sending
        // $directUserMail = User::select('name')->where('email', $uploadFile->direct_user_mail)->first();

        // get id for current uploaded file from transaction table for direct download from mail purposes
        $fileId = Transactions::select('id')->where('file_name', $getFileName)->first();
        $fileDetails = [
            'user_name' => $uploadFile->user->name,
            'file_name' => $fileName,
            'subscriber_name' => '',
            'file_id'=> $fileId->id,
        ];
        // sending... to user who uploaded the file
        // Mail::to($uploadFile->user->email)->send(new UserUploadNotification($fileDetails));

        // if direct user mail does not exist send mail to subscribers else send mail to direct usermail
        if( is_null($uploadFile->direct_user_mail)){
            $subscribers = EmailSubscribers::where('status', 1)->get();
        
            foreach($subscribers as $subscriber ){
                $fileDetails = [
                    'file_name' => $fileName,
                    'subscriber_name' => $subscriber->name,
                    'file_id' => $fileId->id,
                ];
                Mail::to($subscriber->email)->send(new TeamUploadNotification($fileDetails));
            }
        }else{
            Mail::to($uploadFile->direct_user_mail)->send(new TeamUploadNotification($fileDetails));
        }
        
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

    public function bulkDelete(Request $request)
    {
        $id=$request->id;
        foreach($id as $ids){
            $fileUploads = Transactions::findOrFail($ids);
            Storage::disk('local')->delete('files-upload/'.$fileUploads->file_name.'.'.$fileUploads->file_type);
            $fileUploads->delete();
        }

        return redirect()->route('file-uploads')->withStatus('File deleted!');
    }
}