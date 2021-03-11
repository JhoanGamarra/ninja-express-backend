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
            ]
        );

        Category::create(
            [
                'name' => 'Mascotas',
                'description' => 'Categoria de mascotas',
            ]
        );

        Category::create(
            [
                'name' => 'Tecnologia',
                'description' => 'Categoria de tecnologia',
            ]
        );

        Category::create(
            [
                'name' => 'Moda',
                'description' => 'Categoria de moda',
            ]
        );

        Category::create(
            [
                'name' => 'Belleza',
                'description' => 'Categoria de belleza',
            ]
        );

        Category::create(
            [
                'name' => 'Hogar',
                'description' => 'Categoria de hogar',
            ]
        );

        Category::create(
            [
                'name' => 'Otros',
                'description' => 'Categoria de Otros',
            ]
        );

        Category::create(
            [
                'name' => 'Mensajeria',
                'description' => 'Categoria de Mensajeria',
            ]
        );

        Category::create(
            [
                'name' => 'Floristeria',
                'description' => 'Categoria de floristeria',
            ]
        );

        Category::create(
            [
                'name' => 'Sex Shop',
                'description' => 'Categoria de sex shop',
            ]
        );

        Category::create(
            [
                'name' => 'Jugueteria',
                'description' => 'Categoria de jugueteria',
            ]
        );

        Category::create(
            [
                'name' => 'Deportes',
                'description' => 'Categoria de deportes',
            ]
        );

        Category::create(
            [
                'name' => 'Bebes & Niños',
                'description' => 'Categoria de alimentos',
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
