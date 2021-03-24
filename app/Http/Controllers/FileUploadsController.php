<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Log;
use DB;

use App\Models\Transactions;
use App\Models\DataSources;
use App\Models\EmailSubscribers;
use App\Models\User;

use App\Jobs\TeamMailJob;
use App\Jobs\UserMailJob;

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
            // filter section
            $fileUploads = new Transactions;

            if(isset($fname) && auth()->user()->hasRole('superadministrator')){
                $fileUploads = $fileUploads->where('file_name', 'like', '%'.$fname.'%');
            }elseif(auth()->user()->hasRole('user')){
                $fileUploads = $fileUploads->where('user_id', auth()->user()->id)
                                        ->where('file_name', 'like', '%'.$fname.'%');
            }

            if(isset($creator) ){
                $fileUploads = $fileUploads->WhereHas('user', function($q) use($creator) {
                    $q->where('name', 'like', '%'.$creator.'%');
                });
            }
            if(isset($created_from) && isset($created_to)){
                $fileUploads = $fileUploads->whereDate('created_at', '>=', $created_from)
                                            ->whereDate('created_at', '<=', $created_to);
            }

            $fileUploads = $fileUploads->sortable()->paginate(10);
            
        }else{
            // no filter
            if(auth()->user()->hasRole('superadministrator')){
                
                $fileUploads = Transactions::sortable()
                                            ->orderBy('id','desc')
                                            ->paginate(10);

            }elseif( auth()->user()->hasRole('user') ){
                
                // for file uploaded by user and shared with user
                $fileUploads = Transactions::sortable()
                ->join('users', 'fs_transactions.user_id', '=', 'users.id')
                ->where('fs_transactions.user_id', auth()->user()->id)
                ->orWhereIn('fs_transactions.direct_user_mail', function($query){
                                        $query->select('email')
                                            ->from('users')
                                            ->where('id', auth()->user()->id);
                                    })
                ->orderBy('fs_transactions.id','desc')
                ->paginate(10);
                
            }
        }
        $dataSource = DataSources::orderBy('name','asc')->get();

        return view('file-uploads.index', compact('fileUploads','dataSource'));

    }

    public function getUserMail(Request $request)
    {
        $userMail = $request->getUser;
        if(isset($userMail)){
            $userList = User::select('id','email')
            ->where('email','like', '%'.$userMail.'%')
            ->get();
        }

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

        // get id for current uploaded file from transaction table for direct download from mail purposes
        $fileId = Transactions::select('id')->where('file_name', $getFileName)->first();
        $fileDetailUser = [
            'user_name'         => $uploadFile->user->name,
            'file_name'         => $fileName,
            'user_email'        => $uploadFile->user->email,
        ];
        $delay = 10;
        // mail sending... to user who uploaded the file
        dispatch(new UserMailJob($fileDetailUser))->delay($delay);

        // subscribers vs direct mail
        if( $uploadFile->data_source_id == 1 ){
            $subscribers = EmailSubscribers::where('status', 1)->get();
            
            foreach($subscribers as $subscriber ){
                $fileDetails = [
                    'file_name'         => $fileName,
                    'subscriber_name'   => $subscriber->name,
                    'file_id'           => $fileId->id,
                    'subscriber_email'  => $subscriber->email,
                ];
                // mail sending... subscribers mail
                dispatch(new TeamMailJob($fileDetails))->delay($delay);

                $delay = $delay + 5;
            }
        }else{
            // mail sending... direct mail
            $directEmail = $uploadFile->direct_user_mail;

            foreach(explode(',',$directEmail) as $directEmails){
                
                $fileDetails = [
                    'file_name'         => $fileName,
                    'subscriber_name'   => '',
                    'file_id'           => $fileId->id,
                    'subscriber_email'  => $directEmails
                ];

                dispatch(new TeamMailJob($fileDetails))->delay($delay);

                $delay = $delay + 5;
            }
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