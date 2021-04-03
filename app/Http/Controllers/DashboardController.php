<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transactions;
use App\Models\DataSources;
use App\Models\User;

use Auth;

class DashboardController extends Controller
{
    public function renderDashboard()
    {
        if ( Auth::user()->hasRole('superadministrator') ){
            
            return redirect()->route('admin-dashboard');

        }elseif( Auth::user()->hasRole('user') ){

            return redirect()->route('user-dashboard');

        }
    }

    // admin dashboard
    public function index()
    {
        $transactions = Transactions::all();
        $user=User::all();
        $datasources= Transactions::whereNotNull('direct_user_mail')->get();

        $fileUploads = Transactions::orderBy('id','desc')->paginate(10);

        return view('admin-dashboard',compact('transactions','user','datasources','fileUploads'));
    }

    public function userDashboard()
    {
        $transactions = Transactions::where('user_id', auth()->user()->id)->get();
        $user=User::all();
        $datasources=Transactions::whereNotNull('direct_user_mail')
                                    ->where('user_id', auth()->user()->id)
                                    ->get();
                
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

        return view('user-dashboard' ,compact('transactions','user','datasources','fileUploads'));
    }

}
