<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transactions;
use App\Models\DataSources;
use App\Models\EmailSubscribers;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $transactions = Transactions::all();
        $user=User::all();
        $datasources=DataSources::all();

        $fileUploads = Transactions::orderBy('id','desc')->paginate(6);

        return view('dashboard',compact('transactions','user','datasources','fileUploads'));
    }
}
