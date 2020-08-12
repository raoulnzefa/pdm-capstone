<?php

namespace App\Http\Controllers\Api;

use App\Admin;
use App\UserLog;
use App\Product;
use App\ProductNoVariant;
use App\ProductWithVariant;
use App\Inventory;
use App\InventoryVariant;
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
    public function createNoVariant(Request $request)
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
        $product->category_id = (int)$request->category;
        $product->product_name = ucwords(strtolower($request->product_name));
        $product->product_description = $request->product_description;
        $product->product_status = (int)$request->product_status;  
        
        // check if has image file
        if ($request->hasFile('product_image'))
        {   
            // set image nam
            $imageName = time().str_replace(' ', '-', $request->file('product_image')->getClientOriginalName());
            $product->product_image = $imageName;
            // save image into storage
            $request->product_image->storeAs('/public/products/', $imageName);
        }

        // set product url
        $product->product_url = strtolower(str_replace(' ', '-', $request->product_name));

        // save product
        $product->save();


        $prodNum = Product::where('number', $product->number)->first();
        $prodNum->number = str_pad($prodNum->id, 4, '0', STR_PAD_LEFT);
        $prodNum->update();

        
        // save product no variants
        $productNoVariant = new ProductNoVariant();
        $productNoVariant->product_number = $prodNum->number;
        $productNoVariant->price = (float)$request->product_price;
        $productNoVariant->save();

        // save inventory
        $inventory = new Inventory();
        $inventory->number = str_random(5);
        $inventory->product_number = $prodNum->number;
        $inventory->inventory_stock = (int)$request->product_stock;
        $inventory->inventory_critical_level = (int)$request->product_critical_level;
        $inventory->save();

        $inventoryUpdate = Inventory::where('number', $inventory->number)->first();
        $inventoryUpdate->number = str_pad($inventoryUpdate->id, 5, '0', STR_PAD_LEFT);
        $inventoryUpdate->update();

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

    public function updateNoVariant(Request $request, Product $product)
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

    public function createWithVariant(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'product_name' => 'required|string|max:75|unique:products',
            'product_description'   =>  'required|string',
            'variants' => 'required',
            'product_image' => 'required'
        ]);

        date_default_timezone_set("Asia/Manila");

        $product = new Product;
        $product->number = str_random(5);
        $product->category_id = (int)$request->category;
        $product->product_name = ucwords(strtolower($request->product_name));
        $product->product_description = $request->product_description;
        $product->product_status = (int)$request->product_status;  
        
        // check if has image file
        if ($request->hasFile('product_image'))
        {   
            // set image nam
            $imageName = time().str_replace(' ', '-', $request->file('product_image')->getClientOriginalName());
            $product->product_image = $imageName;
            // save image into storage
            $request->product_image->storeAs('/public/products/', $imageName);
        }

        // set product url
        $product->product_url = strtolower(str_replace(' ', '-', $request->product_name));

        // save product
        $product->save();


        $prodNum = Product::where('number', $product->number)->first();
        $prodNum->number = str_pad($prodNum->id, 4, '0', STR_PAD_LEFT);
        $prodNum->update();

        $variants = json_decode($request->variants);

        foreach ($variants as $variant)
        {
             // save inventory
            $inventory = new Inventory();
            $inventory->number = str_random(5);
            $inventory->product_number = $prodNum->number;
            $inventory->inventory_stock = (int)$variant->inventory_stock;
            $inventory->inventory_critical_level = (int)$variant->inventory_crit_level;
            $inventory->save();

            $inventoryUpdate = Inventory::where('number', $inventory->number)->first();
            $inventoryUpdate->number = str_pad($inventoryUpdate->id, 5, '0', STR_PAD_LEFT);
            $inventoryUpdate->update();

            $productVariant = new ProductWithVariant();
            $productVariant->product_number = $prodNum->number;
            $productVariant->variant_value = $variant->variant_value;
            $productVariant->variant_price = (float)$variant->variant_price;
            $productVariant->variant_status = (int)$variant->variant_status;
            $productVariant->save();

            $inventoryVariant = new InventoryVariant();
            $inventoryVariant->inventory_number = $inventoryUpdate->number;
            $inventoryVariant->variant_id = (int)$productVariant->id;
            $inventoryVariant->save();

        }


        $array_params = [
            'id' => $request->admin_id,
            'action' => 'Added product with variants. Product #: '.$prodNum->number
        ];

        $this->createUserLog($array_params);

        $response = array('success'=>true);
        
        return response()->json($response);
        
    }

    public function updateProductCatalog(Request $request, Product $product)
    {
        $request->validate([
            'category' => 'required',
            'product_name' => "required|string|max:75|unique:products,product_name,$product->id",
            'product_description'   =>  'required|string'
        ]);

        date_default_timezone_set("Asia/Manila");

        $product->category_id = (int)$request->category;
        $product->product_name = ucwords(strtolower($request->product_name));
        $product->product_description = $request->product_description;
        $product->product_status = (int)$request->product_status;  
        
        // check if has image file
        if ($request->hasFile('product_image') && $request->filled('product_image'))
        {   
            // set image nam
            $imageName = time().str_replace(' ', '-', $request->file('product_image')->getClientOriginalName());
            $product->product_image = $imageName;
            // save image into storage
            $request->product_image->storeAs('/public/products/', $imageName);
        }

        // set product url
        $product->product_url = strtolower(str_replace(' ', '-', $request->product_name));

        // save product
        $product->update();

        $array_params = [
            'id' => $request->admin_id,
            'action' => 'Updated product catalog with variants. Product #: '.$product->number
        ];

        $this->createUserLog($array_params);

        $response = array('success'=>true);
        
        return response()->json($response);
    }

    public function updateCatalogNoVariant(Request $request, Product $product)
    {
        $request->validate([
            'category' => 'required',
            'product_name' => "required|string|max:75|unique:products,product_name,$product->id",
            'product_description'   =>  'required|string',
            'product_price' => 'required||regex:/^[1-9][0-9.]/|max:8'
        ]);


        date_default_timezone_set("Asia/Manila");

        $product->category_id = (int)$request->category;
        $product->product_name = ucwords(strtolower($request->product_name));
        $product->product_description = $request->product_description;
        $product->product_status = (int)$request->product_status;  
        
        // check if has image file
        if ($request->hasFile('product_image') && $request->filled('product_image'))
        {   
            // set image nam
            $imageName = time().str_replace(' ', '-', $request->file('product_image')->getClientOriginalName());
            $product->product_image = $imageName;
            // save image into storage
            $request->product_image->storeAs('/public/products/', $imageName);
        }

        // set product url
        $product->product_url = strtolower(str_replace(' ', '-', $request->product_name));

        // save product
        $product->update();

        $productNoVariant = ProductNoVariant::where('product_number', $product->number)->first();
        $productNoVariant->price = (float)$request->product_price;
        $productNoVariant->update();

        $array_params = [
            'id' => $request->admin_id,
            'action' => 'Updated product catalog no variants. Product #: '.$product->number
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
                        ->with('category', 'productWithVariants', 'productNoVariant')->paginate(10);

            $products->appends($request->only('search'));
        }
        else
        {  
            // get products data
            $products = Product::with('category', 'productWithVariants', 'productNoVariant')->paginate(10);
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

