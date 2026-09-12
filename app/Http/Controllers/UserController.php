<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageServiceProvider;
use DB;
use Storage;
use Image;
use Auth;
use App\User;
use App\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$user = auth()->user();
    	$users = User::where('tenant_id', $user->tenant_id)
    				->whereNotIn('role_id', [0])
            		->orderBy('id','DESC')
                	->get();
        foreach ($users as $key => $value){
            $name = Role::where('id', $value->role_id)->value('name');
            $value['role_name'] = $name;
        }
        $roles = Role::where('tenant_id',$user->tenant_id)->where('status', '1')->get();
        $total = count($users);
        return view('users.index',compact('users','roles','total'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        
        $validatedData = $request->validate([
           'name' => 'required|string|max:255',
           'email' => 'required|string|email|max:255|unique:users',
        ]);
        
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
        $token = '';
      
        for ($i = 0; $i <= 39; $i++) {
            $token .= $characters[rand(0, strlen($characters) - 1)];
        }
        $user_details = auth()->user();
        $users = new User;
        $users->name = $request->name;
        $users->email = $request->email;
        $users->phone = $request->phone;
        $users->role_id = $request->role;
        $users->tenant_id = $user_details->tenant_id;
        $users->password = Hash::make('doUsePwd@f56');
        $users->remember_token =  $token;
        $users->save();
        return response()->json("User Created Success!!");
    } 

    public function update(Request $request, $id)
    {
       $data = $request->all();

        $user_details = auth()->user();
        $users = User::find($id);
        $users->name = $request->edit_name;
        $users->phone = $request->edit_phone;
        $users->role_id = $request->edit_role;
        $users->save();
        return response()->json("User Updated Success!!");
    }

    public function destroy($id)
    {
        $users  = User::find($id);
        if($users->status == 1){
            $users->status = 0;
            $users->save();
        }
        else{
            $users->status = 1;
            $users->save();
        }
        return response()->json(['success'=>'Success!']);
    }
}
