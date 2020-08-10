<?php

namespace App\Http\Controllers\Api;

use App\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProvinceController extends Controller
{
    public function list()
    {
    	$provinces = Province::all();
    	return response()->json($provinces);
    }
}
