<?php

namespace App\Http\Controllers\Backend;

use App\Models\ReplacementRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReplacementRequestController extends Controller
{
	public function __construct()
   {
      $this->middleware('auth:admin');
   }

   public function index()
   {
   	$data = 'Replacements';
   	return view('backend.replacement.index', compact('data'));
   }

   public function details($requestId)
   {
   	$exists = ReplacementRequest::where('id',$requestId)->exists();

   	if ($exists)
   	{ 
   		$replacement = ReplacementRequest::where('id',$requestId)->first();
         $replacement->viewed = 1;
         $replacement->update();

         $data = 'Replacement Details';
         
   		return view('backend.replacement.details', compact('data','requestId'));
   	}

   	return redirect()->route('replacements');
   }
}
