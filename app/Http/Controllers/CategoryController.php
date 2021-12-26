<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{


    /**
     * Create a new CategoryController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategories()
    {
        $categories = Category::whereCategoryId(null)->get();;
        return $categories;
    }

    //Get Subcategories by Category Id
    public function getSubcategoryByCategoryId($categoryId)
    {
        $subcategories = Category::whereCategoryId($categoryId)->get();
        return $subcategories;
    }

    public function updateCategory($categoryId, Request $request)
    {
        $category = Category::findOrFail($categoryId);
        $category->icon = $this->uploadPhoto($request, $category);
        $category->save();
        return response()->json($category, 201);
    }

    public function uploadPhoto(Request $request, $category)
    {
        $image = $request->file('icon'); //image file from mobile  
        $firebase_storage_path = "categories/icons/";
        $name = "category_" . $category->id;
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

    

  
   
}
