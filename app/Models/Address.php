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
        'lat',
        'lng',
        'client_id',
        'business_id',

    ];

}
