<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Client;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use \Kreait\Firebase\Storage;
use Kreait\Laravel\Firebase\Facades\Firebase;

class ClientController extends Controller
{



    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {

        $user = auth()->user();
        $user->device_token = $request->device_token;
        $user->save();
        $client = Client::where('user_id', '=',  $user->id)->firstOrFail();
        $client->name = $request->name;
        $client->phone = $request->phone;
        $photo = $this->uploadPhoto($request, $client);
        $client->photo = $photo;
        $client->save();
        return response()->json($client, 211);
    }



    public function uploadPhoto(Request $request, $client)
    {

        $image = $request->file('photo'); //image file from mobile  
        $firebase_storage_path = "client/";
        $name = $client->id;
        $localfolder = public_path('firebase-temp-uploads') . '/';
        $extension = $image->getClientOriginalExtension();
        $file      = "client_" . $name . '.' . $extension;
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
