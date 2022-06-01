<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSubcategory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getProductById($productId)
    {
        try {
            $product = Product::findOrFail($productId);
            $subcategories = ProductSubcategory::whereProductId(
                $productId
            )->get();
            foreach ($subcategories as $subcategory) {
                $subcategory['subcategory'] = Category::findOrFail(
                    $subcategory->category_id
                );
            }
            $product['subcategories'] = $subcategories;
            return response()->json($product, 200);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function getProducts($businessId)
    {
        $products = Product::whereBusinessId($businessId)->get();
        $subcategories = [];
        foreach ($products as $product) {
            $productSubcategories = ProductSubcategory::whereProductId(
                $product->id
            )->get();
            foreach ($productSubcategories as $subcategory) {
                $subcategory = Category::findOrFail(
                    $subcategory->category_id
                );
                array_push($subcategories, $subcategory);
              
            }
            $product['subcategories'] = $subcategories;
        }

        return response()->json($products, 200);
    }

    public function createProduct(Request $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'business_id' => $request->business_id,
            'price' => $request->price,
            'active' => true,
        ]);
        $product->photo = $this->uploadPhoto($request, $product);
        $subcategories = $request->subcategories;
        foreach ($subcategories as $subcagory) {
            ProductSubcategory::create([
                'product_id' => $product->id,
                'category_id' => $subcagory,
            ]);
        }
        $product->save();
        $product['subcategories'] = $subcategories;
        return response()->json($product, 201);
    }

    public function uploadPhoto(Request $request, $product)
    {
        $image = $request->file('photo'); //image file from mobile
        $firebase_storage_path = 'business/products/';
        $name = Carbon::now()->timestamp;
        $localfolder = public_path('firebase-temp-uploads') . '/';
        if ($image) {
            $extension = $image->getClientOriginalExtension();
            $file = 'product-' . $name . '.' . $extension;
            if ($image->move($localfolder, $file)) {
                $uploadedfile = fopen($localfolder . $file, 'r');
                $storage = app('firebase.storage');
                $bucket = $storage->getBucket();
                if($product->photo){
                    $oldFileName= explode(
                        '/',
                        $product->photo
                    );
                    $bucket->object($firebase_storage_path . $oldFileName[5])->delete();
                }
                $object = $bucket->upload($uploadedfile, [
                    'name' => $firebase_storage_path . $file,
                    'predefinedAcl' => 'publicRead',
                ]);
                $publicUrl = "https://{$bucket->name()}.storage.googleapis.com/{$object->name()}";
                //will remove from local laravel folder
                unlink($localfolder . $file);
                return $publicUrl;
            } else {
                echo 'error';
                return response()->json(
                    ['message' => 'Error to upload firebase'],
                    504
                );
            }
        }
        return 'Image profile Null';
    }

    public function updateProduct(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $image = $request->file('photo');
        if ($image) {
            $product->photo = $this->uploadPhoto($request, $product);
        }
        $product->active = $request->active;
        $subcategories = $request->subcategories;
        if ($subcategories) {
            $oldSubcategories = ProductSubcategory::whereProductId(
                $product->id
            )->get();
            foreach ($oldSubcategories as $productCategoryOld) {
                $productCategoryOld->delete();
            }
            foreach ($subcategories as $subcagory) {
                ProductSubcategory::create([
                    'product_id' => $product->id,
                    'category_id' => $subcagory,
                ]);
            }
            $subcategoriesResponse = ProductSubcategory::whereProductId($product->id)->get();
            foreach($subcategoriesResponse as $subcategoryResponse){
                $subcategoryResponse['subcategory'] = Category::find($subcategoryResponse->category_id);
            }
        }else{
            $subcategoriesResponse = ProductSubcategory::whereProductId($product->id)->get();
            foreach($subcategoriesResponse as $subcategoryResponse){
                $subcategoryResponse['subcategory'] = Category::find($subcategoryResponse->category_id);
            }
        }
        $product->save();
        $product['subcategories'] = $subcategoriesResponse;
        return response()->json($product, 200);
    }
}
