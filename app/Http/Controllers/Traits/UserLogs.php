<?php

namespace App\Http\Controllers\Traits;

use App\UserLog;
use Auth;

trait UserLogs
{
	public function getUser() {
		return Auth::guard('admin')->user();
	}

    public function createUserLog($array_params)
    {
        date_default_timezone_set("Asia/Manila");

        $user_log = new UserLog;
        $user_log->admin_id = $array_params['id'];
        $user_log->action = $array_params['action'];
        $user_log->log_created = date("Y-m-d H:i:s");
        $user_log->save();
    }  
}
