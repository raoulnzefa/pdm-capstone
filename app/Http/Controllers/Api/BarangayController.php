<?php

namespace App\Http\Controllers\Api;

use App\Municipality;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BarangayController extends Controller
{
    public function list(Municipality $municipal)
    {
    	return response()->json($municipal->barangays);
    }
}
