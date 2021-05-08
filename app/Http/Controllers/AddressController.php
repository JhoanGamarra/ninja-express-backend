<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Facade\FlareClient\Http\Response;
use Google\Cloud\Storage\Connection\Rest;
use Illuminate\Http\Request;

class AddressController extends Controller
{



    public function createAddress(Request $request)
    {

        $address = Address::create([
            "principal" => $request->principal, "secundary" => $request->secundary, "complement" => $request->complement,
            "client_id" => $request->client_id, "business_id"  => $request->business_id
        ]);

        return Response()->json($address, 200);
    }


    public function getAddressById($adsressId)
    {
        $address = Address::findOrFail($adsressId);

        return Response()->json($address , 200);


    }

    public function updateAddress(Request $request , $addressId)
    {

        $address = Address::findOrFail($addressId);
        $address->principal = $request->principal;
        $address->secundary = $request->secundary;
        $address->complement = $request->complement;
        $address->save();

        return Response()->json($address , 200);

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

        return Response()->json("eliminado con exito" ,  200);


    }
}
