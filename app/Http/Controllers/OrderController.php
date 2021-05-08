<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Courier;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

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
            $courier = Courier::where('available', '=', 1)->firstOrFail();
            $order->courier_id = $courier->id;
            $courier->available = false;
            $courier->save();
            //TODO calculate arrived hour from the estimated delivery time
            $order->estimated_delivery_time = $request->estimated_delivery_time;
            $response['courier'] = $courier;
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


    public function haversineGreatCircleDistance(Request $request)
    {


        $earthRadius = 6371000;
        // convert from degrees to radians
        $latFrom = deg2rad((float)$request->latitudeFrom);
        $lonFrom = deg2rad((float)$request->longitudeFrom);
        $latTo = deg2rad((float)$request->latitudeTo);
        $lonTo = deg2rad((float)$request->longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
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
