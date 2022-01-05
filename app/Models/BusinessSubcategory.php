<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSubcategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'businesses_subcategories';
    protected $fillable = ['business_id', 'category_id'];
}
