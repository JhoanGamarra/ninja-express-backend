<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'user_id',
        'active',
        'available',
        'phone'
    ];


}
