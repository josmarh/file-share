<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\EmailSubscribers;

class EmailSubscribersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mailSubscribers = EmailSubscribers::paginate(10);
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
}
