<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Courier;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\Geocoder\Geocoder;
use LaravelFCM\Message\Topics;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;


class OrderController extends Controller
{


    public function createOrder(Request $request)
    {

        $order = Order::create(["cash" => $request->cash, "status" => $request->status, "business_id" => $request->business_id, "client_id" => $request->client_id, "address" => $request->address, "total" => $request->total,  "products" => $request->products]);
        return response()->json($order, 201);
    }

    public function updateOrder(Request $request, $orderId)
    {

        $order = Order::findOrFail($orderId);
        if ($request->status == "accepted") {
            //TODO Courier reassignation logic
            /*$courier = Courier::where('available', '=', 1)->firstOrFail();
            $order->courier_id = $courier->id;
            $courier->available = false;
            $courier->save();
            $order->estimated_delivery_time = $request->estimated_delivery_time;
            $response['courier'] = $courier;*/

            //First notify the couriers

            $couriers = Courier::where('available', '=', 1)->get();

            foreach ($couriers as $courier) {

                $user = User::findOrFail($courier->user_id);
                $notification_id = $user->device_token;
                $title = "Greeting Notification";
                $message = "Have good day!";
                $id = $user->id;
                $type = "basic";

                $res = send_notification_FCM($notification_id, $title, $message, $id, $type);

                if ($res == 1) {
                    $response['courier_assigned'] = true;
                } else {
                    $response['courier_assigned'] = false;
                    $response['error_fcm'] = true;
                }
            }
        }

        if ($request->status == "shipped") {
            $order->estimated_delivery_time = $request->estimated_delivery_time;
        }

        if ($request->status == "delivered") {
            $courier = Courier::findOrFail($order->courier_id);
            $courier->available = true;
            $courier->save();
        }

        $order->status = $request->status;
        $order->save();
        $response['order_id'] = $order->id;
        $response['status'] = $order->status;

        return response()->json($response, 201);
    }



    public function sendMessageToTopicFCM()
    {


        $notificationBuilder = new PayloadNotificationBuilder('my title');
        $notificationBuilder->setBody('Hello world')
            ->setSound('default');

        $notification = $notificationBuilder->build();

        $topic = new Topics();
        $topic->topic('news');

        $topicResponse = FCM::sendToTopic($topic, null, $notification, null);

        $topicResponse->isSuccess();
        $topicResponse->shouldRetry();
        $topicResponse->error();
    }

    public function sendNotificationToDevice()
    {

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder('my title');
        $notificationBuilder->setBody('Hello world')
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $token = "a_registration_from_your_database";

        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

        $downstreamResponse->numberSuccess();
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();

        // return Array - you must remove all this tokens in your database
        $downstreamResponse->tokensToDelete();

        // return Array (key : oldToken, value : new token - you must change the token in your database)
        $downstreamResponse->tokensToModify();

        // return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();

        // return Array (key:token, value:error) - in production you should remove from your database the tokens
        $downstreamResponse->tokensWithError();
    }







    public function getClientOrders($clientId)
    {

        $ordersArray = [];
        $orders = Order::where('client_id', '=', $clientId)->get();
        foreach ($orders as $order) {
            $productsArray = [];
            foreach ($order["products"] as $product) {
                $productById = Product::findOrFail($product["product_id"]);
                $productResponse["product"] = $productById;
                $quantity = $product["quantity"];
                $productResponse["quantity"] = $quantity;
                $productResponse["subtotal"] = ($productById->price * $quantity);
                array_push($productsArray, $productResponse);
            }
            $order["products"] = $productsArray;
            $order["business"] = Business::findOrfail($order->business_id);
            array_push($ordersArray, $order);
        }

        return response()->json($ordersArray, 200);
    }




    public function notifyNewOrderToDeliveryCourier(Request $request)
    {
    }



    public function getBusinessOrders($businessId)
    {

        $ordersArray = [];
        $orders = Order::whereBusinessId($businessId)->get();
        foreach ($orders as $order) {
            $productsArray = [];
            foreach ($order["products"] as $product) {
                $productById = Product::findOrFail($product["product_id"]);
                $productResponse["product"] = $productById;
                $quantity = $product["quantity"];
                $productResponse["quantity"] = $quantity;
                $productResponse["subtotal"] = ($productById->price * $quantity);
                array_push($productsArray, $productResponse);
            }
            $order["products"] = $productsArray;
            // $order["business"] = Business::findOrfail($order->business_id);
            array_push($ordersArray, $order);
        }

        return response()->json($ordersArray, 200);
    }



    function getDrivingDistance($latFrom, $longFrom, $latTo, $longTo)
    {


        //40 peso base  hasta 6km

        //azalia-inem = 507.91 m
        //from = 7.085341, -73.118234
        //to = 7.080775, -73.118569

        /* $lat1 = 7.085341;
        $lat2 =7.080775;
        $long1 = -73.118234;
        $long2 = -73.118569;*/

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json??units=imperial&origins=" . $latFrom . "," . $longFrom . "&destinations=" . $latTo . "," . $longTo . "&mode=driving&key=AIzaSyAJc2ZO0suEYFUNwAiB5uvZ0ZuVaE64amk";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        $response_a = json_decode($response, true);
        $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
        $time = $response_a['rows'][0]['elements'][0]['duration']['text'];

        $response['distance'] = $dist;
        $response['time'] = $time;

        //array('distance' => $dist, 'time' => $time);
        return response()->json($response);
    }




    //Pending
    public function getCordinatesFromAddress($address)
    {

        $client = new \GuzzleHttp\Client();
        $geocoder = new Geocoder($client);
        $geocoder->setApiKey(config('geocoder.key'));
        $geocoder->setCountry(config('geocoder.country', 'CO'));
        //$address = "transversal 112 #20-53";
        $addressGecode =  $geocoder->getCoordinatesForAddress($address);
        //$geocoder->getAddressForCoordinates(40.714224, -73.961452);
        //$geocoder->getAllAddressesForCoordinates(40.714224, -73.961452);


        return response()->json($addressGecode);
    }



    public function getOrderById($orderId)
    {

        $order = $orderById = Order::findOrFail($orderId);
        $productsIds = [];
        foreach ($orderById->products as $product) {
            array_push($productsIds, $product["product_id"]);
        }
        $products = Product::findOrFail($productsIds);
        $order->products = $products;
        return response()->json($order, 200);
    }
}
