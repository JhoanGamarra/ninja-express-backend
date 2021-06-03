<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Facade\FlareClient\Http\Response;
use Google\Cloud\Storage\Connection\Rest;
use Illuminate\Http\Request;
use Spatie\Geocoder\Geocoder;


class AddressController extends Controller
{



    public function createAddress(Request $request)
    {


        //AV. CHAPULTEPEC 1422,BUENOS AIRES
        $state = $request->state;
        $city = $request->city;
        $country = "Mexico";
        $address = $request->address;
        $latAndLong = $this->getCordinatesFromAddress($address . " " . $state . " " . $city . " " . $country);
        $address = Address::create([
            "state" => $state, "city" => $city, "address" => $address, "lat" => $latAndLong->original['lat'], "lng" => $latAndLong->original['lng'],
            "client_id" => $request->client_id, "business_id"  => $request->business_id
        ]);
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
        $addressGecode =  $geocoder->getCoordinatesForAddress($address);
        //$geocoder->getAddressForCoordinates(40.714224, -73.961452);
        //$geocoder->getAllAddressesForCoordinates(40.714224, -73.961452);


        return response()->json($addressGecode);
    }


    public function getAddressById($adsressId)
    {
        $address = Address::findOrFail($adsressId);

        return Response()->json($address, 200);
    }

    public function updateAddress(Request $request, $addressId)
    {
        
        $latAndLong = $this->getCordinatesFromAddress($request->address . " " . $request->state . " " . $request->city . " " . $request->country);
        $address = Address::findOrFail($addressId);
        $address->address = $request->address;
        $address->lat = $latAndLong->original['lat'];
        $address->lng = $latAndLong->original['lng'];
        $address->state = $request->state;
        $address->city = $request->city;
        $address->country = $request->country;
        $address->save();
        return Response()->json($address, 200);

    }

    public function getClientAddresses($clientId)
    {

        $clientAddresses = Address::whereClientId($clientId)->get();

        return Response()->json($clientAddresses);
    }


    public function getBussinessAddresses($businessId)
    {

        $businessAddresses = Address::whereBusinessId($businessId)->get();

        return Response()->json($businessAddresses);
    }


    public function deleteAdress($addressId)
    {

        $address = Address::findOrFail($addressId);

        $address->delete();

        return Response()->json("eliminado con exito",  200);
    }
}
