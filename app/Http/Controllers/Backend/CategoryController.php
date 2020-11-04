<?php

namespace App\Http\Controllers\Backend;

use App\Models\CompanyDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function __construct()
    {
    	$this->middleware('auth:admin');
    }

    public function index()
    {
    	$data = 'Categories';
    	$company = CompanyDetails::first();
    	return view('backend.category.index' , compact('data', 'company'));
    }
}
