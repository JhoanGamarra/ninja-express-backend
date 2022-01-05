<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Category::create(
            [
                'name' => 'Restaurantes',
                'description' => 'Categoria de alimentos',
                'icon' => 'https://ninjaexpressclient.appspot.com.storage.googleapis.com/categories/icons/category_1.svg'
            ]
        );

        Category::create(
            [
                'name' => 'Mascotas',
                'description' => 'Categoria de mascotas',
                'icon' => 'https://ninjaexpressclient.appspot.com.storage.googleapis.com/categories/icons/category_2.svg'
            ]
        );

        Category::create(
            [
                'name' => 'Tecnologia',
                'description' => 'Categoria de tecnologia',
                'icon' => 'https://ninjaexpressclient.appspot.com.storage.googleapis.com/categories/icons/category_3.svg'
            ]
        );

        Category::create(
            [
                'name' => 'Moda',
                'description' => 'Categoria de moda',
                'icon' => 'https://ninjaexpressclient.appspot.com.storage.googleapis.com/categories/icons/category_4.svg'
            ]
        );

        Category::create(
            [
                'name' => 'Belleza',
                'description' => 'Categoria de belleza',
                'icon' => 'https://ninjaexpressclient.appspot.com.storage.googleapis.com/categories/icons/category_5.svg'
            ]
        );

        Category::create(
            [
                'name' => 'Hogar',
                'description' => 'Categoria de hogar',
                'icon' => 'https://ninjaexpressclient.appspot.com.storage.googleapis.com/categories/icons/category_6.svg'
            ]
        );

        Category::create(
            [
                'name' => 'Otros',
                'description' => 'Categoria de Otros',
                'icon' => 'https://ninjaexpressclient.appspot.com.storage.googleapis.com/categories/icons/category_7.svg'
            ]
        );

        Category::create(
            [
                'name' => 'Mensajeria',
                'description' => 'Categoria de Mensajeria',
                'icon' => 'https://ninjaexpressclient.appspot.com.storage.googleapis.com/categories/icons/category_8.svg'
            ]
        );

        Category::create(
            [
                'name' => 'Floristeria',
                'description' => 'Categoria de floristeria',
                'icon' => 'https://ninjaexpressclient.appspot.com.storage.googleapis.com/categories/icons/category_9.svg'
            ]
        );

        Category::create(
            [
                'name' => 'Sex Shop',
                'description' => 'Categoria de sex shop',
                'icon' => 'https://ninjaexpressclient.appspot.com.storage.googleapis.com/categories/icons/category_9.svg'
            ]
        );

        Category::create(
            [
                'name' => 'Jugueteria',
                'description' => 'Categoria de jugueteria',
                'icon' => 'https://ninjaexpressclient.appspot.com.storage.googleapis.com/categories/icons/category_9.svg'
            ]
        );

        Category::create(
            [
                'name' => 'Deportes',
                'description' => 'Categoria de deportes',
                'icon' => 'https://ninjaexpressclient.appspot.com.storage.googleapis.com/categories/icons/category_9.svg'
            ]
        );

        Category::create(
            [
                'name' => 'Bebes & Niños',
                'description' => 'Categoria de alimentos',
                'icon' => 'https://ninjaexpressclient.appspot.com.storage.googleapis.com/categories/icons/category_9.svg'
            ]
        );


        Category::create(
            [
                'name' => 'Alitas',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,
            ]
        );

       
        Category::create(
            [
                'name' => 'Arepas',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,
                
            ]
        );


        Category::create(
            [
                'name' => 'Bebidas',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );

        Category::create(
            [
                'name' => 'Café',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );

        Category::create(
            [
                'name' => 'Carne',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );


        Category::create(
            [
                'name' => 'Comida Rapida',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );

        Category::create(
            [
                'name' => 'China',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );


        Category::create(
            [
                'name' => 'Ejecutiva',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );

        Category::create(
            [
                'name' => 'Hamburguesas',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );

        Category::create(
            [
                'name' => 'Helado',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );

        Category::create(
            [
                'name' => 'Postres',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );

        Category::create(
            [
                'name' => 'Internacional',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );

        Category::create(
            [
                'name' => 'Panaderia',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );


        Category::create(
            [
                'name' => 'Mexicana',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );

        Category::create(
            [
                'name' => 'Tipica',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );

        Category::create(
            [
                'name' => 'Pastas',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );

        Category::create(
            [
                'name' => 'Pescado',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );

        Category::create(
            [
                'name' => 'Sushi',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );

        Category::create(
            [
                'name' => 'Sandwiches',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );

        Category::create(
            [
                'name' => 'Saludable',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,
            ]
        );

        Category::create(
            [
                'name' => 'Pollo',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,

            ]
        );

        Category::create(
            [
                'name' => 'Pizza',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,
            ]
        );

        Category::create(
            [
                'name' => 'Hot Dog',
                'description' => 'Subcategoria de alimentos',
                'category_id' => 1,
            ]
        );




    }
}
