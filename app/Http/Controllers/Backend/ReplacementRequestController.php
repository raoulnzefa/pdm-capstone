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
}
