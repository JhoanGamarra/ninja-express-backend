<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    
   
    public function __construct()
    {
        $this->middleware('auth:api');
    }



}
