<?php

namespace App\Http\Controllers\Api;

use App\Models\Admin;
use App\Models\UserLog;
use App\Models\Reason;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Traits\UserLogs;

class ReasonController extends Controller
{
    use UserLogs;

    public function store(Request $request)
    {
    	$request->validate([
    		'title' => [
                'required',
                'max:200',
                Rule::unique('reasons')->where('type', $request->type)
                        ->where(function($query) use ($request) {
                            return $query->where('title', $request->title);
                        })
            ],
    	]);

    	$reason = new Reason;
    	$reason->title = ucfirst(strtolower($request->title));
        $reason->type = $request->type;
    	$reason->save();

        $array_params = [
            'id' => $request->admin_id,
            'action' => 'Add reason. Reason ID: '.$reason->id.',Type: '.$reason->type.'.'
        ];

        $this->createUserLog($array_params);      

    	return response()->json(['success'=>true]);
    }

    public function update(Request $request, Reason $reason)
    {
    	$request->validate([
            'title' => [
                'required',
                'max:200',
                Rule::unique('reasons')->ignore($reason->id)
                        ->where('type', $request->type)
                        ->where(function($query) use ($request) {
                            return $query->where('title', $request->title);
                        })
            ],
        ]);

    	$reason->title = ucfirst(strtolower($request->title));
        $reason->type = $request->type;
    	$reason->update();

        $array_params = [
            'id' => $request->admin_id,
            'action' => 'Edit reason. Reason ID: '.$reason->id.',Type: '.$reason->type.'.'
        ];

        $this->createUserLog($array_params);      

    	return response()->json(['success'=>true]);
    }

    public function list()
    {
        $list = Reason::all();
        return response()->json($list);
    }

    public function returnReasons()
    {
    	$list = Reason::where('type', 'Return')->get();
    	return response()->json($list);
    }

    public function cancellationReasons()
    {
        $list = Reason::where('type', 'Cancellation')->get();
        return response()->json($list);
    }

}
