<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSubcategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'products_subcategories';
    protected $fillable = ['product_id', 'category_id'];
}
