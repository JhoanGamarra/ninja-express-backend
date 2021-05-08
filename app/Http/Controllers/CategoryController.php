<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{


    /**
     * Create a new AuthController instance.
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

    

  
   
}
