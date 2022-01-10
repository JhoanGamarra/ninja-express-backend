<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Business;
use App\Models\Client;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Storage;
use Kreait\Laravel\Firebase\Facades\Firebase;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getAll()
    {
        $clients = Client::get()->all();
        return response()->json($clients, 200);
    }

    public function getClientById($clientId)
    {
        $client = Client::findOrFail($clientId);
        return response($client,200);
    }

    public function update(Request $request, Client $client)
    {
        $user = auth()->user();
        $user->device_token = $request->device_token;
        $user->save();
        $client = Client::where('user_id', '=', $user->id)->firstOrFail();
        $client->name = $request->name;
        $client->phone = $request->phone;
        $address = Address::findOrFail($request->address_id);
        $address->current = true;
        $photo = $this->uploadPhoto($request, $client);
        $client->photo = $photo;
        $client->save();
        $client['current_address'] = $address;
        return response()->json($client, 211);
    }

    public function changeCurrentClientAddress($clientId, Request $request)
    {
        $oldAddress = Address::whereIdAndClientId(
            $request->old_address_id,
            $clientId
        )->first();
        $oldAddress->current = false;
        $oldAddress->save();
        $currentAddress = Address::whereIdAndClientId(
            $request->current_address_id,
            $clientId
        )->first();
        $currentAddress->current = true;
        $currentAddress->save();
        return response($currentAddress, 200);
    }

    public function uploadPhoto(Request $request, $client)
    {
        $image = $request->file('photo'); //image file from mobile
        $firebase_storage_path = 'client/';
        $name = $client->id;
        $localfolder = public_path('firebase-temp-uploads') . '/';
        if ($image) {
            $extension = $image->getClientOriginalExtension();
            $file = 'client_' . $name . '.' . $extension;
            if ($image->move($localfolder, $file)) {
                $uploadedfile = fopen($localfolder . $file, 'r');
                $storage = app('firebase.storage');
                $bucket = $storage->getBucket();
                $object = $bucket->upload($uploadedfile, [
                    'name' => $firebase_storage_path . $file,
                    'predefinedAcl' => 'publicRead',
                ]);
                $publicUrl = "https://{$bucket->name()}.storage.googleapis.com/{$object->name()}";
                //will remove from local laravel folder
                unlink($localfolder . $file);
                return $publicUrl;
            } else {
                echo 'error';
                return response()->json(
                    ['message' => 'Error to upload firebase'],
                    504
                );
            }
        }
        return 'Image profile Null';
    }
}
