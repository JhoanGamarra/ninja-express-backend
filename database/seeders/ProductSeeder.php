<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Product;
use App\Models\ProductSubcategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $business = Business::where('email', '=', 'business@email.com')->firstOrFail();

        $product = Product::create(["name" => "Product seeded 1", "description" => "product seeded description 1", "business_id" => $business->id , "price" => "12000", "category_id" => 1,  "active" => true]);
        $product->photo = "https://ninjaexpressclient.appspot.com.storage.googleapis.com/categories/icons/category_1.svg";
        $subcategories = [15,16,17];
        foreach ($subcategories as $subcagory) {
            ProductSubcategory::create([
                'product_id' => $product->id,
                'category_id' => $subcagory,
            ]);
        }
        $product->save();

        $product2 = Product::create(["name" => "Product seeded 1", "description" => "product seeded description 2", "business_id" => $business->id , "price" => "14000", "category_id" => 1,  "active" => true]);
        $product2->photo = "https://ninjaexpressclient.appspot.com.storage.googleapis.com/categories/icons/category_1.svg";
        $subcategories = [17,18,19];
        foreach ($subcategories as $subcagory) {
            ProductSubcategory::create([
                'product_id' => $product->id,
                'category_id' => $subcagory,
            ]);
        }
        $product2->save();

        $product3 = Product::create(["name" => "Product seeded 1", "description" => "product seeded description 2", "business_id" => $business->id , "price" => "8000", "category_id" => 1,  "active" => true]);
        $product3->photo = "https://ninjaexpressclient.appspot.com.storage.googleapis.com/categories/icons/category_1.svg";
        $subcategories = [20,21,22];
        foreach ($subcategories as $subcagory) {
            ProductSubcategory::create([
                'product_id' => $product->id,
                'category_id' => $subcagory,
            ]);
        }
        $product3->save();
    }
}
