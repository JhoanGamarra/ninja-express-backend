<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Business;
use App\Models\BusinessSubcategory;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

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

    public function getAll()
    {
        $businessesArray = [];
        $businesses = Business::get()->all();
        foreach ($businesses as $business) {
            $address = Address::find($business->address_id);
            $category = Category::find($business->category_id);
            $business['address'] =
                $address['address'] .
                ',' .
                $address['state'] .
                ',' .
                $address['city'];
            $business['category'] = $category['name'];
            array_push($businessesArray, $business);
        }

        return response()->json($businesses);
    }

    public function changeAvailableStatus(Request $request, $business_id)
    {
        $business = Business::findOrFail($business_id);
        $business->available = $request->status;
        $business->save();
        $message = $request->status
            ? 'Business was activated successfully'
            : 'Business was deactivated successfully';
        return response()->json(['message' => $message]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $business = Business::where('user_id', '=', $user->id)->first();
        $business->name = $request->name;
        $business->phone = $request->phone;
        if ($request->category_id) {
            $business->category_id = (int) $request->category_id;
        }
        $subcategories = (array) $request->subcategories;
        if($subcategories){
        $oldSubcategories = BusinessSubcategory::whereBusinessId($business->id)->get();
            foreach($oldSubcategories as $oldSubcategory){
                $oldSubcategory->delete();
            }
            $subcategories = (array) $request->subcategories;
            foreach ($subcategories as $subcagoryId) {
                BusinessSubcategory::create([
                    'business_id' => $business->id,
                    'category_id' => $subcagoryId,
                ]);
            }
            $subcategoriesResponse = BusinessSubcategory::whereBusinessId($business->id)->get();
            foreach($subcategoriesResponse as $subcategoryResponse){
                $subcategoryResponse['subcategory'] = Category::find($subcategoryResponse->category_id);
            }
        }else{
            $subcategoriesResponse = BusinessSubcategory::whereBusinessId($business->id)->get();
            foreach($subcategoriesResponse as $subcategoryResponse){
                $subcategoryResponse['subcategory'] = Category::find($subcategoryResponse->category_id);
            }
        }
        if($request->address_id){
            $business->address_id = (int) $request->address_id;
        }
        if($request->file('photo')){
            $business->photo = $this->uploadPhoto($request, $business);
        }
        $business->save();
        $business['subcategories'] = $subcategoriesResponse;
        $business['address'] = Address::findOrFail($request->address_id);
        return response()->json($business, 211);
    }

    public function getBusinessesByCategory(Request $request, $categoryId)
    {
        $clientAddress = Address::find($request->query('addressId'));
        $businesses = Business::whereCategoryId($categoryId)->get();
        foreach ($businesses as $business) {
            $businessAddress = Address::findOrFail($business->address_id);
            $distance = (int) $this->calculateDistance(
                $businessAddress->lat,
                $businessAddress->lng,
                $clientAddress->lat,
                $clientAddress->lng
            );
            switch (true) {
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
                default:
                    $business['deliveryCost'] = 50;
                    break;
            }
            $business['distance'] = $distance;
            $business['address'] = $businessAddress;
        }
        //sort businesses by distance
        $businesessArray[] = $businesses;
        usort($businesessArray, [$this, 'sort_businesses_by_distance']);
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
            $distance = $this->calculateDistance(
                $businessAddress->lat,
                $businessAddress->lng,
                $latClient,
                $lngClient
            );
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
                default:
                    $business['deliveryCost'] = 50;
                    break;
            }
            $business['distance'] = $distance;
            $businessesArrayResponse[] = $business;
        }
        //sort businesses by distance
        usort($businessesArrayResponse, [$this, 'sort_businesses_by_distance']);
        return response()->json($businessesArrayResponse);
    }

    private static function sort_businesses_by_distance($a, $b)
    {
        if ($a->distance == $b->distance) {
            return 0;
        }
        return $a->distance < $b->distance ? -1 : 1;
    }

    public function calculateDistance($latFrom, $lngFrom, $latTo, $lngTo)
    {
        $earthRadius = 6371000;
        // convert from degrees to radians
        $latFromConverted = deg2rad((float) $latFrom);
        $lonFromConverted = deg2rad((float) $lngFrom);
        $latToConverted = deg2rad((float) $latTo);
        $lonToConverted = deg2rad((float) $lngTo);
        $latDelta = $latToConverted - $latFromConverted;
        $lonDelta = $lonToConverted - $lonFromConverted;
        $angle =
            2 *
            asin(
                sqrt(
                    pow(sin($latDelta / 2), 2) +
                        cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)
                )
            );
        //distance in meters
        $distance = ($angle * $earthRadius) / 1000;

        return round($distance, 3);
    }

    public function uploadPhoto(Request $request, $business)
    {
        $image = $request->file('photo'); //image file from mobile
        $firebase_storage_path = 'business/images/';
        $name = Carbon::now()->timestamp;
        $localfolder = public_path('firebase-temp-uploads') . '/';
        $extension = $image->getClientOriginalExtension();
        $file = 'business-' . $name . '.' . $extension;
        if ($image->move($localfolder, $file)) {
            $uploadedfile = fopen($localfolder . $file, 'r');
            $storage = app('firebase.storage');
            $bucket = $storage->getBucket();
            if ($business->photo) {
                $oldFileName = explode('/', $business->photo);
                $bucket
                    ->object($firebase_storage_path . $oldFileName[5])
                    ->delete();
            }
            $object = $bucket->upload($uploadedfile, [
                'name' => $firebase_storage_path . $file,
                'predefinedAcl' => 'publicRead',
            ]);
            $publicUrl = "https://{$bucket->name()}.storage.googleapis.com/{$object->name()}";
            //will remove from local laravel folder
            if (file_exists($localfolder . $file)) {
                unlink($localfolder . $file);
            } else {
                echo 'The file filename does not exist';
            }
            return $publicUrl;
        } else {
            echo 'error';
            return response()->json(
                ['message' => 'Error to upload firebase'],
                504
            );
        }
    }
}
