<?php

namespace App\Http\Controllers\Backend;

use App\Models\UserLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuditTrailController extends Controller
{
   public function __construct()
   {
   	$this->middleware('auth:admin');
   }

   public function index()
   {
   	$data = 'Audit Trail';

   	return view('backend.audit_trail.index', compact('data'));
   }
}
