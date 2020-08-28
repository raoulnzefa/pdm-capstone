<?php

namespace App\Http\Controllers\Api;

use App\Models\Admin;
use App\Models\UserLog;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Traits\UserLogs;

class CategoryController extends Controller
{
    use UserLogs;
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:30|unique:categories'
        ],
        [
            'name.unique' => 'The Category Name has already been taken.',
            'name.required' => 'The Category Name field is required.',
            'name.max' => 'The Category Name may not be greater than 20 characters.'
        ]);

        date_default_timezone_set("Asia/Manila");

        // create category
        $category = new Category;
        $category->name = ucwords($request->name);
        $category->status = (int)$request->status;
        $category->has_variant = (int)$request->has_variant;
        $category->url = strtolower(str_replace(' ', '-', $request->name));
        $category->save();

        $array_params = [
            'id' => $request->admin_id,
            'action' => 'Add new category. ID: '.$category->id.', New Category: '.$category->name.'.'
        ];

        $this->createUserLog($array_params);       

        $response = array('success'=>true);

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {

        $request->validate([
            'name' => "required|string|max:30|unique:categories,name,$category->id"
        ],
        [
            'name.unique' => 'The Category Name has already been taken.',
            'name.required' => 'The Category Name field is required.',
            'name.max' => 'The Category Name may not be greater than 20 characters.'
        ]);

        $old_category = $category->name;
        // update category
        $category->name = ucwords($request->name);
        $category->status = (int)$request->status;
        $category->has_variant = (int)$request->has_variant;
        $category->url = strtolower(str_replace(' ', '-', $request->name));
        $category->update();

        $array_params = [
            'id' => $request->admin_id,
            'action' => 'Edit category. ID: '.$category->id.', Edited Category: '.$category->name.', Old Category: '.$old_category.'.'
        ];

        $this->createUserLog($array_params);

        
        $response = array('success'=>true);

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if ($category->delete())
        {
            $response = array('deleted'=>true, 'message'=> 'Category has been deleted.');

            return response()->json($response);
        }
    }

    public function list()
    {
        $list = Category::all();

        return response()->json($list);
    }

    public function withVariants()
    {
        $categories = Category::where(['has_variant'=>1, 'status'=>1])->get();

        return response()->json($categories);
    }

    public function noVariants()
    {
        $categories = Category::where(['has_variant'=>0, 'status'=>1])->get();

        return response()->json($categories);
    }
}
