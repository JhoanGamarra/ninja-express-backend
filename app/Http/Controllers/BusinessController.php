<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $business = Business::all();
        return $business;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {


    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function show(Business $business)
    {
        $business = Business::get($business);
        return $business;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function edit(Business $business)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
        $user = auth()->user();
        $business = Business::where('user_id', '=',  $user->id)->firstOrFail();
        $business->name = $request->name;
        $business->phone = $request->phone;
        $business->description = $request->description;
        $business->category_id = $request->category_id;
        $business->photo = $this->uploadPhoto($request, $business);
        $business->save();

        return response()->json($business, 211);

    }


    public function uploadPhoto(Request $request, $business)
    {
        $image = $request->file('photo'); //image file from mobile  
        $firebase_storage_path = "business/images/";
        $name = "business_" . $business->id;
        $localfolder = public_path('firebase-temp-uploads') . '/';
        $extension = $image->getClientOriginalExtension();
        $file      = $name . '.' . $extension;
        if ($image->move($localfolder, $file)) {
            $uploadedfile = fopen($localfolder . $file, 'r');
            $storage  = app('firebase.storage');
            $bucket = $storage->getBucket();
            $object = $bucket->upload($uploadedfile, ['name' => $firebase_storage_path . $file, 'predefinedAcl' => 'publicRead']);
            $publicUrl = "https://{$bucket->name()}.storage.googleapis.com/{$object->name()}";
            //will remove from local laravel folder  
            unlink($localfolder . $file);

            return $publicUrl;
        } else {
            echo 'error';
            return response()->json(["message" => "Error to upload firebase"], 504);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function destroy(Business $business)
    {
        //
    }
}
