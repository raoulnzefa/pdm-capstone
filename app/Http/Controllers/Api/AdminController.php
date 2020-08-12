<?php

namespace App\Http\Controllers\Api;

use App\UserLog;
use App\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Traits\UserLogs;

class AdminController extends Controller
{
    use UserLogs;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'username' =>  'required|string|unique:admins',
            'role' => 'required'
        ]);

        date_default_timezone_set("Asia/Manila");

        $password = "IFGadmin2019";

        $admin = new Admin;
        $admin->first_name = ucfirst($request->input('first_name'));
        $admin->last_name = ucfirst($request->input('last_name'));
        $admin->username = strtolower($request->input('username'));
        $admin->role = $request->input('role');
        $admin->status = ($request->status == false) ? 'Active' : 'Deactivated';
        $admin->password = Hash::make($password);
        $admin->save();

        $array_params = [
            'id' => $request->admin_id,
            'action' => 'Add user. User ID: '.$admin->id.', Admin Name: '.$admin->first_name.' '.$admin->last_name.'.'
        ];

        $this->createUserLog($array_params);


        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function updateUser(Request $request, Admin $admin)
    {

        $admin->status = ($request->input('status') == false) ? 'Active' : 'Deactivated';
        $admin->role = $request->input('role');
        $admin->update();

        //$adminData = Admin::where('id', $request->admin_id)->first();

        $array_params = [
            'id' => $request->admin_id,
            'action' => 'Edit user. User ID: '.$admin->id.', Admin Name: '.$admin->first_name.' '.$admin->last_name.'.'
        ];

        $this->createUserLog($array_params);
        
        return response()->json(['success'=> true]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Admin  $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        //
    }

    public function list()
    {
        $list = Admin::all();

        return response()->json($list);
    }

    public function changeRole($role, Admin $admin)
    {
        // if ($role == 'Admin')
        // {
        //     $admin->role = 'Assistant';
        //     $admin->update();
        //     $response = array('success'=>true);
        // }

        // if ($role == 'Assistant')
        // {
        //     $admin->role = 'Admin';
        //     $admin->update();
        //     $response = array('success'=>true);
        // }

        // return response()->json($response);
    }

    public function changeStatus($status, Admin $admin)
    {
        // if ($role == 'Admin')
        // {
        //     $admin->role = 'Assistant';
        //     $admin->update();
        //     $response = array('success'=>true);
        // }

        // if ($role == 'Assistant')
        // {
        //     $admin->role = 'Admin';
        //     $admin->update();
        //     $response = array('success'=>true);
        // }

        // return response()->json($response);
    }

    public function adminName(Admin $admin)
    {
        return response()->json($admin);
    }

    public function updateAccount(Request $request, Admin $admin)
    {
        $request->validate([
            'first_name' => "required|string|max:20|unique:admins,first_name,$admin->id",
            'last_name' => "required|string|max:20|unique:admins,last_name,$admin->id",
            'email' =>  "required|string|email|unique:admins,email,$admin->id"
        ]);

        $admin->first_name = $request->first_name;
        $admin->last_name = $request->last_name;
        $admin->email = $request->email;

        $admin->update();

        // set admin data
        $adminData = Admin::where('id', $request->admin_id)->first();

        
        $response = array('success'=>true, 'message'=>'User information has been successfully updated.');

        return response()->json($response);
    }

    public function changePass(Request $request, Admin $admin)
    {
        $request->validate([
            'current_password' => "required|string|min:6",
            'password' => "required|string|min:6|confirmed",
            'password_confirmation'=> "required|string|min:6"
        ]);

        if ($request->has(['current_password', 'password', 'password_confirmation']))
        {
            if (Hash::check($request->current_password, $admin->password))
            {
                $admin->password = Hash::make($request->password);
            }
            else
            {
                $response = array('success'=>false, 'message'=>'Invalid current password.');
                return response()->json($response);
            }

            $admin->update();

          

            $response = array('success'=>true, 'message'=>'Password has been successfully changed');

            return response()->json($response);
        }
    }

    public function editInformation($admin)
    {
        $data = Admin::where('id', $admin)->first();
        return response()->json($data);   
    }

    public function updateInformation(Request $request, Admin $admin)
    {
        $request->validate([
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20'
        ]);

        $admin->first_name = ucfirst($request->input('first_name'));
        $admin->last_name = ucfirst($request->input('last_name'));
        $admin->username = strtolower($request->input('username'));

        if ($request->filled('password'))
        {
            $admin->password = Hash::make($request->input('password'));
        }

        $admin->update();

        $array_params = [
            'id' => $request->admin_id,
            'action' => 'Edit user. User ID: '.$admin->id.', Admin Name: '.$admin->first_name.' '.$admin->last_name.'.'
        ];

        $this->createUserLog($array_params);
        
        return response()->json(['success'=> true]);

    }
}
