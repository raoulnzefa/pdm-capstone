<?php

namespace App\Http\Controllers\Api;

use App\Admin;
use App\UserLog;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Traits\UserLogs;

class ProductController extends Controller
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
            'category' => 'required',
            'product_name' => 'required|string|max:75|unique:products',
            'product_description'   =>  'required|string',
            'product_price' => 'required|regex:/^[1-9][0-9]/|max:8',
            'product_stock' => 'required|numeric|gte:product_critical_level|regex:/^[1-9]/',
            'product_critical_level' => 'required|numeric|lte:product_stock|regex:/^[1-9]/',
            'product_image' => 'required'
        ]);
        
        date_default_timezone_set("Asia/Manila");

        // create instance for product
        $product = new Product;
        $product->number = str_random(5);
        $product->category_id = $request->category;
        $product->product_name = ucwords(strtolower($request->product_name));
        $product->product_description = ucfirst($request->product_description);
        $product->product_variant = ($request->filled('product_variant')) ? $request->product_variant : NULL;
        $product->product_stock = (int)$request->product_stock;
        $product->product_critical_level = (int)$request->product_critical_level;
        $product->product_price = (float)$request->product_price;
        
        // check if has image file
        if ($request->hasFile('product_image'))
        {   
            // set image nam
            $imageName = time().str_replace(' ', '-', $request->file('product_image')->getClientOriginalName());
            $product->product_image = $imageName;
        }

        // set product url
        $product->product_url = strtolower(str_replace(' ', '-', $request->product_name));

        // save product
        $product->save();

        $prodNum = Product::where('number', $product->number)->first();
        $prodNum->number = 'P'.str_pad($prodNum->id, 5, '0', STR_PAD_LEFT);
        $prodNum->update();

        // save image into storage
        $request->product_image->storeAs('/public/products/', $imageName);


        $array_params = [
            'id' => $request->admin_id,
            'action' => 'Add new product. Product #: '.$prodNum->number
        ];

        $this->createUserLog($array_params);
        

        $response = array('success'=>true);
       

        return response()->json($response);
    }
   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {   
        $request->validate([
            'category' => 'required',
            'product_name' => "required|string|max:75|unique:products,product_name,$product->id",
            'product_description'   =>  'required|string',
            'product_price' => 'required|regex:/^[1-9][0-9]/|max:8',
            'product_critical_level' => 'required|numeric|regex:/^[1-9]/'
        ]);

        date_default_timezone_set("Asia/Manila");

        $old_product_name = $product->product_name;

        $product->category_id = $request->category;
        $product->product_name = ucwords($request->product_name);
        $product->product_description = ucfirst($request->product_description);
        $product->product_variant = ($request->filled('product_variant')) ? $request->product_variant : NULL;

        $old_image = $product->product_image;

        if ($request->hasFile('product_image') && $request->filled('product_image'))
        {
            $imageName = time().str_replace(' ', '.', $request->file('product_image')->getClientOriginalName());

            $request->product_image->storeAs('/public/products/', $imageName);
            $product->product_image = $imageName;

            Storage::delete('/public/products/'.$old_image);
        }

        $product->product_status = $request->product_status;

        $product->product_url = strtolower(str_replace(' ', '-', $request->product_name));

        $product->product_critical_level = (int)$request->product_critical_level;
        $product->product_price = (float)$request->product_price;

        $product->update();

        
        $array_params = [
            'id' => $request->admin_id,
            'action' => 'Edit product. Product #: '.$product->number
        ];

        $this->createUserLog($array_params);

        $response = array('success'=>true);
        
        return response()->json($response);
    }

    public function addStock(Request $request, Product $product)
    {
        $product->product_stock += (int)$request->quantity;
        $product->update();

         $array_params = [
            'id' => $request->admin_id,
            'action' => 'Added stock: '.$request->quantity.' Product #: '.$product->number
        ];

        $this->createUserLog($array_params);  

        return response()->json(['success'=>true]); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $imageName = $product->image;

        if ($product->delete())
        {
            Storage::delete('/public/products/'.$imageName);
            $response = array('deleted'=>true, 'message'=> 'Product has been deleted.');
        }
        else
        {
            $response = false;
        }

        return response()->json($response);

    }

    public function productsTable(Request $request)
    {
        if ($request->query('search'))
        {
            // search val
            $search_val = $request->query('search');
            // get products result
            $products = Product::where(function($query) use ($search_val) {
                            $query->where('number','LIKE','%'. $search_val .'%')
                            ->orWhere('product_name', 'LIKE','%'. $search_val .'%');
                        })
                        ->with('category')->paginate(10);

            $products->appends($request->only('search'));
        }
        else
        {  
            // get products data
            $products = Product::with('category')->paginate(10);
        }

        return response()->json($products);
    }

    public function featuredProducts()
    {
        $list = Product::where(['featured' => 'Yes', 'status' => 'Active'])->with('productNoVariant.inventory', 'inventory')->get();

        return response()->json($list);
    }

    public function productPage()
    {
        $list = Product::where('status', 'Active')->with(['productNoVariant.inventory','inventory'])->paginate(10);

        return response()->json($list);
    }

    public function productByCategory($category)
    {

        $list = Product::where(['category_id'=>$category, 'status' => 'Active'])->with('productNoVariant.inventory', 'inventory')->get();
        
        return response()->json($list);
    }

    public function edit(Product $product)
    {
        return response()->json($product);
    }

    public function relatedProducts(Product $product)
    {
        $related_products = Product::where('category_id', $product->category_id)
                                    ->where(function($query) use ($product) {
                                        $query->where('status', 'Active')
                                            ->where('sku','!=', $product->sku);
                                    })
                                    ->with('productNoVariant.inventory')
                                    ->get();

        return response()->json($related_products);
    }

    public function productsReport()
    {
        date_default_timezone_set("Asia/Manila");

        $date_now = date('F d, Y h:i A');
        $products = Product::with('category','productVariants', 'productNoVariant')->get();

        return response()->json(['products' => $products, 'date_now' => $date_now]);
    }

    public function delete(Product $product)
    {
        $product->delete();

        return response()->json([
            'success' => true
        ]);
    }
}

