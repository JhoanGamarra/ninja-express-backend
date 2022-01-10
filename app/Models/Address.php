<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'state',
        'address',
        'city',
        "country",
        'lat',
        'lng',
        'description',
        'client_id',
        'business_id',
        'current',
    ];

}
