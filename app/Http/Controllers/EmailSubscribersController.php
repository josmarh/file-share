<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\EmailSubscribers;
use App\Models\User;
use App\Models\RoleUser;


class EmailSubscribersController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $name = request()->query('name');
        $email = request()->query('email');
        $status = request()->query('status');

        if ( $name || $email || $status ) 
        {
            $mailSubscribers = new EmailSubscribers;
            if(isset($name)){
                $mailSubscribers = $mailSubscribers->where('name','like', '%'.$name.'%');
            }
            if(isset($email)){
                $mailSubscribers = $mailSubscribers->where('email','like', '%'.$email.'%');
            }
            if($status !='All'){
                $mailSubscribers = $mailSubscribers->where('status', $status);
            }

            $mailSubscribers = $mailSubscribers->sortable()->paginate(10);
        }else{
            $mailSubscribers = EmailSubscribers::sortable()->paginate(10);
        }
        
        return view('email-subscription.index', compact('mailSubscribers'));
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
            'name' => 'required',
            'email' => 'required|email'
        ]);
        $mailSubscribers = new EmailSubscribers([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'status' => '1',
        ]);
        $mailSubscribers->save();

        return redirect()->route('mail-subscribers')->withStatus('Subscriber successfully added.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id = $request->msId;
        $mailSubscribers = EmailSubscribers::findOrFail($id);

        return view('email-subscription.edit', compact('mailSubscribers'));
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
        $mailSubscribers = EmailSubscribers::findOrFail($id);
        $mailSubscribers->name = $request->input('name');
        $mailSubscribers->email = $request->input('email');
        $mailSubscribers->save();

        return redirect()->route('mail-subscribers')->withStatus('Subscriber successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
            $mailSubscribers = EmailSubscribers::findOrFail($id);
            $mailSubscribers->delete();
 

        return redirect()->route('mail-subscribers')->withStatus('Subscriber deleted!');
    }

    public function status(Request $request, $id)
    {
        $mailSubscribers = EmailSubscribers::findOrFail($id);
        $mailSubscribers->status = $request->input('status');
        $mailSubscribers->save();

        return redirect()->route('mail-subscribers');
    }

    public function bulkDelete(Request $request)
    {
        $id=$request->id;
        foreach($id as $ids){
            $mailSubscribers = EmailSubscribers::findOrFail($ids)->delete();
        }

        return redirect()->route('mail-subscribers')->withStatus('Subscriber deleted!');
    }

    

    
}
