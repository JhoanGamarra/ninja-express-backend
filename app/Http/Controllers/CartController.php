<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    
    public function getClientCart($clientId){
        $client = Client::findOrFail($clientId);
        $cart = Cart::findOrFail($client->cart_id);
        $cartItems = CartItem::whereCartId($client->cart_id)->get();
        $total = 0;

        foreach($cartItems as $cartItem){
            $product =  Product::findOrFail($cartItem->product_id);
            $cartItem['product'] = $product;
            $cartItem['subtotal'] = $cartItem->quantity * $product->price;
            $total = $total + $cartItem['subtotal']; 
        }
        $cart['items'] = $cartItems;
        $cart['total'] = $total;

        return response($cart);
    }

    public function addItemToCart($cartId, Request $request){

        $cart = Cart::find($cartId);
        $cart->delivery_cost = $request->delivery_cost;
        $cart->save();
        $cartItem =CartItem::create([
            'cart_id' => $cartId,
            'product_id' => (int)$request->product_id,
            'quantity' => $request->quantity
        ]);

        return response($cartItem, 201);

    }

    public function removeItemToCart($cartItemId){
        CartItem::findOrFail($cartItemId)->delete($cartItemId);
        return response(["message" => 'removed succesfully'], 201);
    }

}
