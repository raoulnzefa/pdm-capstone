<?php

namespace App\Http\Controllers\Api;

use App\Models\UserLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserLogsController extends Controller
{
    public function searchUserLogs(Request $request)
    {
    	$from = $request->from_date.' 00:00:00';
    	$to = $request->to_date.' 23:59:59';

    	$userLogs = UserLog::whereBetween('log_created', [$from, $to])->get();

    	return response()->json($userLogs);
    }
}
