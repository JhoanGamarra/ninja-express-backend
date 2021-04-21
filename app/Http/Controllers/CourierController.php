<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use Illuminate\Http\Request;

class CourierController extends Controller
{
   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Courier  $courier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $user = auth()->user();
        $courier = Courier::where('user_id', '=',  $user->id)->firstOrFail();
        $courier->name = $request->name;
        $courier->phone = $request->phone;
        $courier->photo = $this->uploadPhoto($request , $courier);
        $courier->save();

        return response()->json($courier, 211);
    }


    public function uploadPhoto(Request $request, $courier)
    {

        $image = $request->file('photo'); //image file from mobile  
        $firebase_storage_path = "courier/";
        $name = $courier->id;
        $localfolder = public_path('firebase-temp-uploads') . '/';
        $extension = $image->getClientOriginalExtension();
        $file      = "courier_" . $name . '.' . $extension;
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

  
}
