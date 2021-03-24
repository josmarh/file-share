<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Jetstream\Jetstream;
use Laravel\Fortify\Rules\Password;

use App\Models\User;
use App\Models\RoleUser;
use App\Models\Transactions;

use Auth;

class UserController extends Controller
{
    public function users()
    {
        $name = request()->query('name');
        $email = request()->query('email');
        $role = request()->query('role');
        $status = request()->query('status');

        if($name || $email || $role || $status){

            $user = new User;

            if(isset($name)){
                $user = $user->join('role_user as x', 'users.id','=','x.user_id')
                            ->select('users.id','users.name','users.email','users.active_status','x.role_id')
                            ->where('users.name','like', '%'.$name.'%');
            }
            if(isset($email)){
                $user = $user->join('role_user as y', 'users.id','=','y.user_id')
                            ->select('users.id','users.name','users.email','users.active_status','y.role_id')
                            ->where('users.email','like', '%'.$email.'%');
            }
            if($role !='All' ){
                $user = $user->join('role_user as a', 'users.id','=','a.user_id')
                            ->select('users.id','users.name','users.email','users.active_status','a.role_id')
                            ->where('a.role_id', $role);
            }elseif($role == 'All'){
                $user = $user->join('role_user as a', 'users.id','=','a.user_id')
                            ->select('users.id','users.name','users.email','users.active_status','a.role_id');
            }
            if($status !='All' ){
                $user = $user->join('role_user as b', 'users.id','=','b.user_id')
                            ->select('users.id','users.name','users.email','users.active_status','b.role_id')
                            ->where('users.active_status', $status);
            }      
                $user = $user->sortable()->paginate(10);
        }else{

            $user = User::sortable()
            ->join('role_user', 'users.id','=','role_user.user_id')
            ->select('id','name','email','active_status','role_user.role_id')
            ->paginate(10);

        }

        return view('email-subscription.users', compact('user'));
    }

    public function userRole(Request $request, $id)
    {
        $newRole = $request->input('role');
        $originalRole = $request->input('role2');

        $user = User::findOrFail($id);
        $user->detachRole($originalRole);
        $user->attachRole($newRole);        

        return redirect()->route('users')->withStatus('New Role Assigned');

        // RoleUser::where('user_id', $id)
        //         ->update([ 'role_id' => $roleId ]);
    }

    public function userStatus(Request $request, $id)
    {
        // $userId = User::findOrFail($id);
        $status = $request->input('status');

        User::where('id', $id)
                ->update([ 'active_status' => $status ]);

        return redirect()->route('users')->withStatus('Status Changed');
    }

    public function userRegister(Request $request)
    {
        $request->validate([

            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', new Password, 'confirmed'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ]);

        $user = new User ([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'active_status' => 1,
        ]);

        $user->save();
        $user->attachRole($request->get('role'));

        return redirect()->route('users')->withStatus('User created successfully!');
    }

    public function userDelete(Request $request)
    {
        $id=$request->id;
        foreach($id as $ids){
            $users = User::findOrFail($ids)->delete();

            $userUpload = Transactions::where('user_id',$ids)
                                    ->update(['deleted_at', date('Y-m-d H:m:s')]);
        }

        return redirect()->route('users')->withStatus('User deleted successfully!');
    }
}
