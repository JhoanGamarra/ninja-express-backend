<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Client;
use App\Models\Courier;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\Geocoder\Geocoder;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function sendPush($topic, $title, $body, $data)
    {
        $messaging = app('firebase.messaging');

        $notification = Notification::fromArray([
            'title' => $title,
            'body' => $body,
        ]);

        $message = CloudMessage::fromArray([
            'topic' => $topic,
            'notification' => $notification, // optional
            'data' => $data, // optional
        ]);

        $messaging->send($message);
    }

    public function sendPushWithoutData($topic, $title, $body)
    {
        $messaging = app('firebase.messaging');

        $notification = Notification::fromArray([
            'title' => $title,
            'body' => $body,
            'data' => [], // optional
        ]);

        $message = CloudMessage::fromArray([
            'topic' => $topic,
            'notification' => $notification, // optional
        ]);

        $messaging->send($message);
    }

    public function createOrder(Request $request)
    {
        $order = Order::create([
            'cash' => $request->cash,
            'status' => $request->status,
            'business_id' => $request->business_id,
            'client_id' => $request->client_id,
            'address' => $request->address,
            'total' => $request->total,
            'products' => $request->products,
        ]);

        if ($order) {
            $clientId = Client::find($request->client_id);
            $businessId = Business::find($request->business_id);
            $topicClient = 'client-' . $clientId->user_id;
            $topicBusiness = 'business-' . $businessId->user_id;
            $businessNotificationTitle = 'Tienes un nuevo pedido';
            $clientNotificationTitle = 'Orden creada con exito';
            $businessNotificationBody =
                'Tienes una orden pendiente por aceptar';
            $clientNotificationBody =
                'Tu orden fue creada y esta proceso de aceptacion por parte del negocio';
            $businessNotificationData = ['orderId' => $order->id];
            //send push notification to client and business when the order was created
            $this->sendPush(
                $topicBusiness,
                $businessNotificationTitle,
                $businessNotificationBody,
                $businessNotificationData
            );
            $this->sendPush(
                $topicClient,
                $clientNotificationTitle,
                $clientNotificationBody,
                $businessNotificationData
            );
        }

        return response()->json($order, 201);
    }

    public function updateOrder(Request $request, $orderId)
    {
        $order = Order::find($orderId);
        $clientId = Client::find($order->client_id);
        $topicClient = 'client-' . $clientId->user_id;

        if (!$order) {
            return response()->json(['message' => "the order doesn't exist "]);
        }

        if ($request->status == 'accepted') {
            //Courier
            $couriersTopic = 'courier-available';
            $courierNotificationTitle = 'Nuevo pedido pedido disponible';
            $courierNotificationBody =
                'Un nuevo pedido necesita de tu servicio, tomalo ahora!';
            $courierNotificationData = ['order' => $order];

            //Client
            $clientNotificationTitle = 'Tu orden fue aceptada';
            $clientNotificationBody =
                'Tu orden fue aceptada y esta en proceso de preparacion tardara un aproximado de ' .
                $request->estimated_delivery_time .
                ' minutos';
            $this->sendPushWithoutData(
                $couriersTopic,
                $courierNotificationTitle,
                $courierNotificationBody
            );
        }

        if ($request->status == 'shipped') {
            $order->estimated_delivery_time = $request->estimated_delivery_time;
            $courier = Courier::find($request->courier_id);
            $courier->available = false;
            $courier->save();
            $order->courier_id = $courier->id;
            $clientNotificationTitle = 'Tu orden esta en camino';
            $clientNotificationBody =
                'Nuestro ninja va corriendo con tu pedido y estara contigo en aproximadanmente ' .
                $request->estimated_delivery_time .
                ' minutos';
            $this->sendPushWithoutData(
                $topicClient,
                $clientNotificationTitle,
                $clientNotificationBody
            );
        }

        if ($request->status == 'delivered') {
            $courier = Courier::findOrFail($order->courier_id);
            $courier->available = true;
            $courier->save();
            $clientNotificationTitle =
                'Tu pedido ha sido entregado exitosamente';
            $clientNotificationBody =
                'Tu pedido fue entregado con exito, recuerda calificar al entregador y al negocio ';
            $this->sendPushWithoutData(
                $topicClient,
                $clientNotificationTitle,
                $clientNotificationBody
            );
        }

        $order->status = $request->status;
        $order->save();
        $response['order_id'] = $order->id;
        $response['status'] = $order->status;

        return response()->json($response, 201);
    }

    public function getAll()
    {
        $ordersArray = [];
        $orders = Order::get()->all();
        foreach ($orders as $order) {
            $productsArray = [];
            foreach ($order['products'] as $product) {
                $productById = Product::findOrFail($product['product_id']);
                $productResponse['id'] = $productById['id'];
                $productResponse['name'] = $productById['name'];
                $productResponse['price'] = $productById['price'];
                $productResponse['description'] = $productById['description'];
                $productResponse['photo'] = $productById['photo'];
                $quantity = $product['quantity'];
                $productResponse['quantity'] = $quantity;
                $productResponse['subtotal'] = $productById->price * $quantity;
                array_push($productsArray, $productResponse);
            }
            $order['products'] = $productsArray;
            $business = Business::findOrfail($order->business_id);
            $order['business'] = $business['name'];
            array_push($ordersArray, $order);
        }

        return response()->json($ordersArray, 200);
    }

    public function getClientOrders($clientId)
    {
        $ordersArray = [];
        $orders = Order::where('client_id', '=', $clientId)->get();
        foreach ($orders as $order) {
            $productsArray = [];
            foreach ($order['products'] as $product) {
                $productById = Product::findOrFail($product['product_id']);
                $productResponse['product'] = $productById;
                $quantity = $product['quantity'];
                $productResponse['quantity'] = $quantity;
                $productResponse['subtotal'] = $productById->price * $quantity;
                array_push($productsArray, $productResponse);
            }
            $order['products'] = $productsArray;
            $order['business'] = Business::findOrfail($order->business_id);
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
            foreach ($order['products'] as $product) {
                $productById = Product::findOrFail($product['product_id']);
                $productResponse['product'] = $productById;
                $quantity = $product['quantity'];
                $productResponse['quantity'] = $quantity;
                $productResponse['subtotal'] = $productById->price * $quantity;
                array_push($productsArray, $productResponse);
            }
            $order['products'] = $productsArray;
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

        $url =
            'https://maps.googleapis.com/maps/api/distancematrix/json??units=imperial&origins=' .
            $latFrom .
            ',' .
            $longFrom .
            '&destinations=' .
            $latTo .
            ',' .
            $longTo .
            '&mode=driving&key=AIzaSyAJc2ZO0suEYFUNwAiB5uvZ0ZuVaE64amk';
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
        $addressGecode = $geocoder->getCoordinatesForAddress($address);
        //$geocoder->getAddressForCoordinates(40.714224, -73.961452);
        //$geocoder->getAllAddressesForCoordinates(40.714224, -73.961452);

        return response()->json($addressGecode);
    }

    public function getOrderById($orderId)
    {
        $order = $orderById = Order::findOrFail($orderId);
        $productsIds = [];
        foreach ($orderById->products as $product) {
            array_push($productsIds, $product['product_id']);
        }
        $products = Product::findOrFail($productsIds);
        $order->products = $products;
        return response()->json($order, 200);
    }
}
