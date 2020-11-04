<?php

namespace App\Http\Controllers\Api;

use App\Models\CompanyDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\UserLogs;
use Illuminate\Support\Facades\Storage;
use Config;

class CompanyDetailsController extends Controller
{
	use UserLogs;

   public function getCompanyDetails()
   {
   	$company = CompanyDetails::first();

   	return response()->json(['company_details' => $company]);
   }

   public function saveCompanyDetails(Request $request)
   {
   	$request->validate([
   		'logo' => 'required',
   		'company_image' => 'required',
   		'name' => 'required|string',
   		'address' => 'required|string',
   		'contact_number' => 'required',
   		'tin_number' => 'required',
   		'about_us' => 'required',
   		'terms_and_conditions' => 'required',
   		'return_policy' => 'required',
         'reserved_days' => 'required',
   		'num_delivery_days' => 'required',
   		'num_due_payment_days' => 'required',
   		'num_follow_up_email' => 'required'
   	]);

   	date_default_timezone_set("Asia/Manila");

   	$company = new CompanyDetails();
   	
   	if ($request->hasFile('company_image') && $request->hasFile('logo'))
      {   
         // set image name
         $logoName = time().'.'.str_replace(' ', '-', $request->file('logo')->getClientOriginalName());

         // save image into storage
         $logoPath = $request->file('logo')->storeAs(
             'company_images',
             $logoName,
             's3'
         );

         $company->logo_url = Storage::disk('s3')->url($logoPath);
         $company->logo_path = $logoPath;

         // company image
         // set image nam
         $imageName = time().'.'.str_replace(' ', '-', $request->file('company_image')->getClientOriginalName());

         // save image into storage
         $imagePath = $request->file('company_image')->storeAs(
             'company_images',
             $imageName,
             's3'
         );

         $company->image_url = Storage::disk('s3')->url($imagePath);
         $company->image_path = $imagePath;

      }

      $company->name = ucwords($request->name);
      $company->address = ucfirst($request->address);
      $company->contact_number = $request->contact_number;
      $company->tin_number = $request->tin_number;
      $company->about_us = $request->about_us;
      $company->terms_and_conditions = $request->terms_and_conditions;
      $company->return_policy = $request->return_policy;
      $company->reserved_days = (int)$request->reserved_days;
      $company->delivery_days = (int)$request->num_delivery_days;
      $company->due_payment_days = (int)$request->num_due_payment_days;
      $company->follow_up_days = (int)$request->num_follow_up_email;
      $company->save();

      $array_params = [
         'id' => $request->admin_id,
         'action' => 'Setup Company Details.'
      ];

      $this->createUserLog($array_params); 

      return response()->json(['success' => true]);
   }

   public function updateCompanyDetails(Request $request, CompanyDetails $company)
   {
      $request->validate([
         'name' => 'required|string',
         'address' => 'required|string',
         'contact_number' => 'required',
         'tin_number' => 'required',
         'about_us' => 'required',
         'terms_and_conditions' => 'required',
         'return_policy' => 'required',
         'num_delivery_days' => 'required',
         'num_due_payment_days' => 'required',
         'num_follow_up_email' => 'required',
         'reserved_days' => 'required'
      ]);


      date_default_timezone_set("Asia/Manila");

      $company->name = ucwords($request->name);
      $company->address = ucfirst($request->address);
      $company->contact_number = $request->contact_number;
      $company->tin_number = $request->tin_number;
      $company->about_us = $request->about_us;
      $company->terms_and_conditions = $request->terms_and_conditions;
      $company->return_policy = $request->return_policy;
      $company->reserved_days = (int)$request->reserved_days;
      $company->delivery_days = (int)$request->num_delivery_days;
      $company->due_payment_days = (int)$request->num_due_payment_days;
      $company->follow_up_days = (int)$request->num_follow_up_email;

      $old_logo = $company->logo_path;
      $old_image = $company->image_path;

      if ($request->hasFile('logo'))
      {   
         // set image nam
         $logoName = time().'.'.str_replace(' ', '-', $request->file('logo')->getClientOriginalName());

         // save image into storage
         $logoPath = $request->file('logo')->storeAs(
             'company_images',
             $logoName,
             's3'
         );

         $company->logo_url = Storage::disk('s3')->url($logoPath);
         $company->logo_path = $logoPath;
         
         Storage::disk('s3')->delete($old_logo);
         
      }

      if ($request->hasFile('company_image'))
      {
          // company image
         // set image nam
         $imageName = time().'.'.str_replace(' ', '-', $request->file('company_image')->getClientOriginalName());

         // save image into storage
         $imagePath = $request->file('company_image')->storeAs(
             'company_images',
             $imageName,
             's3'
         );

         $company->image_url = Storage::disk('s3')->url($imagePath);
         $company->image_path = $imagePath;

         Storage::disk('s3')->delete($old_image);
      }

      $company->update();

      $array_params = [
         'id' => $request->admin_id,
         'action' => 'Update Company Details.'
      ];

      $this->createUserLog($array_params); 

      return response()->json(['success' => true]);
   }
}
