<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Order;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use Illuminate\Support\Facades\DB;


class ProductController extends Controller
{
    //
    public function getIndex(){
        $products = Product::all();

        return view('shop.index',['products' =>$products]);
    }

    public function getAddToCart(Request $request, $id){
        $product = Product::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->add($product, $product->id);

        $request->session()->put('cart', $cart);
        return redirect()->route('product.index');
    }

    public function getCart(){
        if(!Session::has('cart')){
            return view('shop.shopping-cart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        return view('shop.shopping-cart', ['products' => $cart->items, 'totalPrice' => $cart->totalPrice]);
    }

    public function getCheckout(){
        if(!Session::has('cart')){
            return view('shop.shopping-cart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        $total = $cart->totalPrice;
        return view('shop.checkout',['total' => $total]);
    }


    public function postCheckout(Request $request){

        if(!Session::has('cart')){
            return redirect()->route('shop.shoppingCart');
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);

        Stripe::setApiKey('sk_test_9P20f4nfmi3L4tAqGZkZgf30');
        // Token is created using Stripe.js or Checkout!
        // Get the payment token submitted by the form:
        $token = $_POST['stripeToken'];

        // Charge the user's card:
        try{
            $charge = \Stripe\Charge::create(array(
                "amount" =>  $cart->totalPrice*100,
                "currency" => "usd",
                "description" => "Example charge",
                "source" => $token,
            ));
            $order = new Order();
            $order->cart = serialize($cart);
            $order->address = $request->input('address');
            $order->name = $request->input('cardholder-name');
            $order->payment_id = $charge->id;

            Auth::user()->orders()->save($order);
        }catch(\Exception $exception){
            return redirect()->route('checkout')->with('error', $exception->getMessage());
        }


        Session::forget('cart');
        return redirect()->route('product.index')->with('success','transaction completed!');

    }


}
