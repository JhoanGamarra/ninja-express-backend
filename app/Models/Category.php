<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;


    // User.php => Aquí dejaremos una sentencia que significa que
// un categoria tiene muchos subcategorias
/*public function subcategories()
{
    return $this->hasMany(Category::class);
}

// Post.php => Aquí dejaremos una sentencia que significa que
// un post le pertence a un usuario.
public function subcategory()
{
    return $this->belongsTo(Category::class);
}*/


}
