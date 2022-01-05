<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSubcategory;
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
            return response()->json($product, 200);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    public function getProducts($businessId)
    {

        $products = Product::where('business_id', '=', $businessId)->get();
        return response()->json($products, 200);
    }

    public function createProduct(Request $request)
    {
        $product = Product::create(["name" => $request->name, "description" => $request->description, "business_id" => $request->business_id, "price" => $request->price, "category_id" => $request->category_id,  "active" => true]);
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
        $firebase_storage_path = "business/products/";
        $name = "product_" . $product->id;
        $localfolder = public_path('firebase-temp-uploads') . '/';
        if ($image) {
            $extension = $image->getClientOriginalExtension();
            $file      = $name . '.' . $extension;
            if ($image->move($localfolder, $file)) {
                $uploadedfile = fopen($localfolder . $file, 'r');
                $storage  = app('firebase.storage');
                $bucket = $storage->getBucket();
                $object = $bucket->upload($uploadedfile, ['name' => $firebase_storage_path . $file, 'predefinedAcl' => 'publicRead']);
                $publicUrl = "https://{$bucket->name()}.storage.googleapis.com/{$object->name()}";
                //will remove from local laravel folder  
                unlink($localfolder . $file);

                return $publicUrl;
            } else {
                echo 'error';
                return response()->json(["message" => "Error to upload firebase"], 504);
            }
        }

        return "Image null";

    }

    public function updateProduct(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->photo = $this->uploadPhoto($request, $product);
        $product->active = $request->active;
        $product->save();
        return response()->json($product, 200);
    }
}
