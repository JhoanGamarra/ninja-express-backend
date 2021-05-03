<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'cash',
        'status',
        'state',
        'estimated_delivery_time',
        'business_id',
        'client_id',
        'courier_id',
        'products',
        'address',
        'total',


    ];

    protected $casts = [
        'products' => 'array',
    ];
}
