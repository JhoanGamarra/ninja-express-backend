<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Business;
use Spatie\Geocoder\Geocoder;
use Illuminate\Http\Request;

class AddressController extends Controller
{
 
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function createClientAddress($clientId, Request $request)
    {
        //AV. CHAPULTEPEC 1422,BUENOS AIRES
        $state = $request->state;
        $city = $request->city;
        $country = $request->country;
        $address = $request->address;
        $latAndLong = $this->getCordinatesFromAddress(
            $address . ' ' . $state . ' ' . $city . ' ' . $country
        );
        if($request->current){
            $clientAddresses = Address::whereClientId($clientId)->get();
            foreach($clientAddresses as $clientAddress){
                $clientAddress->current = false;
                $clientAddress->save();
            }
        }
        $address = Address::create([
            'state' => $state,
            'city' => $city,
            'address' => $address,
            'lat' => $latAndLong->original['lat'],
            'lng' => $latAndLong->original['lng'],
            'client_id' => $clientId,
            'description' => $request->description,
            'current' => $request->current,
            'country' => $country,
        ]);
        $response['address'] = $address;
        $response['lat'] = $latAndLong->original['lat'];
        $response['lng'] = $latAndLong->original['lng'];
        return Response()->json($response, 200);
    }

    public function createBusinessAddress($businessId, Request $request)
    {
        //AV. CHAPULTEPEC 1422,BUENOS AIRES
        $state = $request->state;
        $city = $request->city;
        $country = $request->country;
        $address = $request->address;
        $latAndLong = $this->getCordinatesFromAddress(
            $address . ' ' . $state . ' ' . $city . ' ' . $country
        );
        $address = Address::create([
            'state' => $state,
            'city' => $city,
            'address' => $address,
            'lat' => $latAndLong->original['lat'],
            'lng' => $latAndLong->original['lng'],
            'client_id' => null,
            'description' => $request->description,
            'country' => $country,
        ]);
        $business = Business::findOrFail($businessId);
        $business->address_id = $address->id;
        $business->save();
        $response['address'] = $address;
        $response['lat'] = $latAndLong->original['lat'];
        $response['lng'] = $latAndLong->original['lng'];

        return Response()->json($response, 200);
    }

    public function getCordinatesFromAddress($address)
    {
        $client = new \GuzzleHttp\Client();
        $geocoder = new Geocoder($client);
        $geocoder->setApiKey(config('geocoder.key'));
        $geocoder->setCountry(config('geocoder.country', 'MX'));
        //$address = "transversal 112 #20-53";
        $addressGecode = $geocoder->getCoordinatesForAddress($address);
        //$geocoder->getAddressForCoordinates(40.714224, -73.961452);
        //$geocoder->getAllAddressesForCoordinates(40.714224, -73.961452);

        return response()->json($addressGecode);
    }

    public function getAddressById($addressId)
    {
        $address = Address::whereDeletedAndId(false, $addressId)->first();
        if($address == null){
            return Response()->json(["message" => "Address was deleted"], 404);
        }
        return Response()->json($address, 200);
    }

    public function updateAddress(Request $request, $addressId)
    {
        $latAndLong = $this->getCordinatesFromAddress(
            $request->address .
                ' ' .
                $request->state .
                ' ' .
                $request->city .
                ' ' .
                $request->country
        );
        $address = Address::findOrFail($addressId);
        $address->address = $request->address;
        $address->lat = $latAndLong->original['lat'];
        $address->lng = $latAndLong->original['lng'];
        $address->state = $request->state;
        $address->city = $request->city;
        $address->description = $request->description;
        $address->country = $request->country;
        $address->save();
        return Response()->json($address, 200);
    }

    public function getClientAddresses($clientId)
    {
        $clientAddresses = Address::whereClientIdAndDeleted($clientId, false)->get();
        return Response()->json($clientAddresses);
    }

    public function deleteAddress($addressId)
    {
        $address = Address::findOrFail($addressId);
        $address->deleted = true;
        $address->save();
        return Response()->json(["message" => "eliminado con exito"], 200);
    }
}
