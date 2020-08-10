<?php

namespace App\Http\Controllers\Api;

use App\Province;
use App\Municipality;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MunicipalityController extends Controller
{
    public function list(Province $province)
    {
    	return response()->json($province->municipalities);
    }
}
