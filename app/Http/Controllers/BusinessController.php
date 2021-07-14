<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Business;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

class BusinessController extends Controller
{

    /**
     * Create a new BusinessController instance.
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
     * @param  \App\Models\Business  $business
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $user = auth()->user();
        $user->device_token = $request->device_token;
        $user->save();
        $business = Business::where('user_id', '=',  $user->id)->firstOrFail();
        $business->name = $request->name;
        $business->phone = $request->phone;
        $business->description = $request->description;
        $business->category_id = (int)$request->category_id;
        $business->photo = $this->uploadPhoto($request, $business);
        $business->save();

        return response()->json($business, 211);
    }


    //this method get the businesses by category
    public function getBusinessesByCategory($categoryId)
    {
        $businesses = Business::whereCategoryId($categoryId)->get();

        foreach ($businesses as $business) {
        }

        return response()->json($businesses);
    }


    public function getDashboardBusinesses(Request $request)
    {

        //Get businesses by closer location and better rating  || Sorting || Ads


        $latClient = $request->latClient;
        $lngClient = $request->lngClient;
        $businesses = Business::all();
        $businessesArray[] = $businesses;
        $businessesArrayResponse = [];

        foreach ($businesses as $business) {

            $businessAddress = Address::whereBusinessId($business->id)->first();
            $distance = $this->calculateDistance($businessAddress->lat, $businessAddress->lng, $latClient, $lngClient);
            switch ($distance) {
                case $distance <= 3:
                    $business['deliveryCost'] = 25;
                    break;
                case $distance <= 6:
                    $business['deliveryCost'] = 35;
                    break;
                case $distance <= 10:
                    $business['deliveryCost'] = 45;
                    break;
                case $distance <= 15:
                    $business['deliveryCost'] = 50;
                    break;
                default;
                    $business['deliveryCost'] = 50;
                    break;
            }
            $business['distance'] = $distance;
            $businessesArrayResponse[] = $business;
        }
        //sort businesses by distance
        usort($businessesArrayResponse, array($this, 'sort_businesses_by_distance'));
        return response()->json($businessesArrayResponse);
    }


    private static function sort_businesses_by_distance($a, $b)
    {
        if ($a->distance == $b->distance) {
            return 0;
        }
        return ($a->distance < $b->distance) ? -1 : 1;
    }


    public function calculateDistance($latFrom, $lngFrom, $latTo, $lngTo)
    {

        $earthRadius = 6371000;
        // convert from degrees to radians
        $latFromConverted = deg2rad((float)$latFrom);
        $lonFromConverted = deg2rad((float)$lngFrom);
        $latToConverted = deg2rad((float)$latTo);
        $lonToConverted = deg2rad((float)$lngTo);
        $latDelta = $latToConverted - $latFromConverted;
        $lonDelta = $lonToConverted - $lonFromConverted;
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        //distance in meters
        $distance = ($angle * $earthRadius) / 1000;

        return round($distance, 3);
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
            if (file_exists($localfolder . $file)) {
                   unlink($localfolder . $file);
                } else {
                   echo "The file filename does not exist";
                }
            return $publicUrl;
        } else {
            echo 'error';
            return response()->json(["message" => "Error to upload firebase"], 504);
        }
    }
}
