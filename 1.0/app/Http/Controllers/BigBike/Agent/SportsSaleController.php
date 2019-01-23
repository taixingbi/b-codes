<?php
/**
 * Created by PhpStorm.
 * User: bike
 * Date: 7/5/17
 * Time: 12:06 PM
 */

namespace App\Http\Controllers\BigBike\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\Charge;
use App\User;
use Stripe\Stripe;
use Auth;
use Illuminate\Support\Facades\DB;
use \Milon\Barcode\DNS1D;
use Mail;
use Carbon\Carbon;
use App\Cart;

class SportsSaleController extends Controller
{
    public function sportsSale(){
//        Session::forget('cart');

        $categories = DB::table('inventory_category')->get();

        $date = date('m/d/Y');
        $date = explode('/', $date);


        $saleLists = DB::table('inventory_sale_item')
            ->where('location', Session::get('location'))
//            ->whereYear('created_at', '=', $date[2])
//            ->whereDay('created_at', '=', $date[1])
//            ->whereMonth('created_at', '=', $date[0])
            ->get();
//        dd($saleLists);

        $sales = DB::table('inventory_sales')
            ->where('location', Session::get('location'))
            ->where('order_completed', 1)
            ->get();

        $idMap = array();

        foreach ($sales as $sale){
            array_push($idMap, $sale->id);
        }

        $nameMap = array();
        $users = DB::table('users')->get();
        foreach ($users as $user){
            $nameMap[$user->email] = $user->first_name." ".$user->last_name;
        }

        if(!Session::has('cart')){
            return view('bigbike.agent.sports.main',['products'=>null,'totalPrice'=>0,'categories'=>$categories,'saleLists'=>$saleLists,'nameMap'=>$nameMap,'idMap'=>$idMap]);
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);



        return view('bigbike.agent.sports.main',['products'=>$cart->items,'totalPrice'=>!empty($cart->totalPrice)?$cart->totalPrice:0,'categories'=>$categories,'saleLists'=>$saleLists,'idMap'=>$idMap]);
    }

    public function barcodeSearch(Request $request){
        $product = DB::table('inventory')->where('barcode', $request->inventory_barcode)->first();

        return json_encode($product);
    }

    public function updateShoppingCart(Request $request){

        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->add($request, $request->inventory_id);
        session(['cart'=> $cart]);
//        dd(Session::get('cart'));
    }

    public function sportsForm(Request $request)
    {
        if(strtolower(trim($request->button))=='add'){
//            dd('here');
            $this->updateShoppingCart($request);
//            $product = DB::table('inventory')->where('barcode', $request->inventory_barcode)->first();
//            return json_encode($product);
            return json_encode('add');
//            return json_encode('add');
        }else if(trim($request->button)=='addWithoutBarcode'){
            try{
                $int_id = DB::table('inventory')->insertGetId([
                    'name' => $request->name,'price' => $request->price,'category' => $request->inventory_cat,
                    'size'=>$request->inventory_size,'quantity'=>$request->quantity,'location'=>session('location')
                ]);
                $request->inventory_id = $int_id;
                $this->updateShoppingCart($request);

                return json_encode('addWithoutBarcode');

            }catch(\Exception $exception){

                return json_encode($exception->getMessage());
            }
            return json_encode('addWithoutBarcode');
        }
    }

    public function getCart(){
        if(!Session::has('cart')){
            $nameMap = array();

            return view('bigbike.agent.sports.main',['products'=>null,'nameMap'=>$nameMap]);
        }
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        return redirect()->route('agent.sportsSale');

//        return view('bigbike.agent.sports.main',['products'=>$cart->items,'totalPrice'=>$cart->totalPrice]);
    }

    public function updateCart($id, $num){
        $product = DB::table('inventory')->where('id', $id)->first();
        $oldCart = Session::has('cart')? Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->minus($product, $num, $product->id);
        session(['cart'=> $cart]);
        return redirect()->route('agent.sportsSale');
//        return view('bigbike.agent.sports.main',['products'=>$cart->items,'totalPrice'=>$cart->totalPrice]);
    }

    public function pmtForm(Request $request){
        if(!Session::has('cashier')){
            return redirect()->route('user.logout');
        }

        if($request->has('credit_card')){
            $payment_type = $request->credit_card;
            $order_completed = 0;
            //$rent_agent_total_pay = session('total_price_after_tax');

        }else{
            $payment_type = $request->cash;
            $order_completed = 1;

        }

        if(!empty(trim($request->rent_adjust))){
            $adjust_price = $request->rent_adjust;
        }else{
            $adjust_price = null;
        }

        $ac = new AgentController();
        $array = $ac->getLocationTable(Session::get('location'));
        $num = $array[0];
        $table = $array[1];


        try{
            $int_id = DB::table('inventory_sales')->insertGetId([
                'location' => session('location'),'customer_name' => $request->rent_customer,'customer_lastname' => $request->rent_customer_last,
                'cashier_email' => Session::get('cashierEmail'), 'order_completed' => $order_completed,
                'payment_type' => $payment_type, 'order_id' => "",
                'original_price'=>doubleval(session('inventory_total_before_tax')),
                'total_price_before_tax'=>$adjust_price==null?doubleval(session('inventory_total_before_tax')):$adjust_price,
                'total_price_after_tax' => $adjust_price==null?doubleval(session('inventory_total')):number_format($adjust_price*(1.08875),2),
                'rendered_cash'=>$request->rent_rendered,'created_at'=>date("Y-m-d H:i:s"), 'comment' => $request->comment,'adjust_price'=>$adjust_price,'adjust_percentage'=>$request->rent_discount,'sequantial'=>strtoupper($table).$num
            ]);

            try{
                $oldCart = Session::get('cart');
                $cart = new Cart($oldCart);
                $products = $cart->items;
                foreach ($products as $item) {
                    DB::table('inventory_sale_item')->insert([
                        'sale_id' => $int_id, 'name' => $item['item'], 'quantity' => $item['qty'],'cashier_email'=>Session::get('cashierEmail'),
                        'price' => $item['price'],'size'=>$item['size'],'created_at'=>date("Y-m-d H:i:s"),'location'=>session('location')
                    ]);
                }

            }catch(\Exception $exception){

                return redirect()->route('agent.sportsSale')->with('rent_price_error', $exception->getMessage());
            }


        }catch(\Exception $exception){

            return redirect()->route('agent.sportsSale')->with('rent_price_error', $exception->getMessage());
        }


        if($request->has('cash')){
            $ac = new AgentController();
            $barcode = $ac->barcodeEncode(intval($int_id),'INT');

            try{
                DB::table('inventory_sales')
                    ->where('id', $int_id)
                    ->update(['barcode'=>$barcode]);

            }catch(\Exception $exception){

                return redirect()->route('agent.sportsSale')->with('rent_price_error', $exception->getMessage());
            }

            session(['int_id'=>$int_id]);
            return redirect()->route('agent.intReceipt');
            //return view('bigbike.agent.agent-receipt',['agent_rents_order'=>$agent_rents_order[0],'rent_success'=>'Order Completed!']);
        }


        session(['sequantial'=>strtoupper($table).$num,'int_id' => $int_id, 'agent_price_after_tax' => session('total_price_after_tax'), 'inventory'=>'inventory','rent'=>null,'tour'=>null,'rent_edit'=>null,'tour_edit'=>null,'deposit'=>null,'net_price'=>$adjust_price==null?number_format(session('inventory_total'),2):number_format($adjust_price*(1.08875),2)]);
        //$this->paypalTest();
        return view('bigbike.agent.cc-checkout',['price'=>$adjust_price==null?number_format(session('inventory_total'),2):number_format($adjust_price*(1.08875),2),'firstname'=>$request->rent_customer,'lastname'=>$request->rent_customer_last]);

    }

    public function inventoryCheckout(Request $request){
        if(!Session::has('cashier')){
//            $uc = new UserController();
//            return $uc->getLogout();
            return redirect()->route('user.logout');
        }
//        dd($request->_token);

        if(Session::token() != $request->_token){
            return redirect()->route('agent.sportsSale',['rent_price_error'=>'token not valid, please redo the transaction']);

        }

        if(!Session::has('inventory_total')){
            return redirect()->route('agent.404');
        }

        $ac = new AgentController();
        $data = $ac->makePPPmt($request);
//        dd($ac->makePPPmt($request));

//        dd($data);
        if(array_key_exists('message', $data) ) {
//            CREDIT_CARD_CVV_CHECK_FAILED
//            if($data->{'message'}=='Credit card was refused.'){
//                session(['error'=>'Credit card was refused.']);
//            }else if($data->{'message'}=='Credit card CVV check failed.'){
//                session(['error'=>'Credit card CVV check failed.']);
//            }
            session(['error'=>$data->{'message'}]);

            return redirect()->route('agent.rentOrder');
        }

        if($data->{'state'}=='approved') {
            //update db
            $int_id = Session::get('int_id');
            $barcode = $ac->barcodeEncode(intval($int_id),'PR');
            $refund_id = $data->transactions[0]->related_resources[0]->sale->id;
//            dd(gettype($refund_id));
            $user = DB::table('users')->where('email', Session::get('cashierEmail'))->first();
            if($user->level==4){
                $served = 0;
                $payment_type = 'paypal';
            }else{
                $served = 1;
                $payment_type = 'Credit Card';
            }

            try{
                DB::table('inventory_sales')
                    ->where('id', $int_id)
                    ->update(['order_completed' => 1,'customer_cc_name' => $request->cc_firstname,'customer_cc_lastname' => $request->cc_lastname,'order_id' => $data->id,
                        'completed_at' => date("Y-m-d H:i:s"),'payment_type'=>$payment_type, 'barcode' => $barcode,'refund_id'=>$refund_id]);

            }catch(\Exception $exception){
                return redirect()->route('agent.sportsSale')->with('rent_price_error', $exception->getMessage());
            }
        }else{
            return redirect()->route('agent.sportsSale')->with('rent_price_error', "credit card is declined");
        }
        Session::forget('agent_price_after_tax');
        Session::forget('cart');
        //return view('bigbike.agent.agent-rent-receipt',['agent_rents_order'=>$agent_rents_order[0],'rent_success'=>'Order Completed!','barcode'=>Session::pull('order_id')]);
        return redirect()->route('agent.intReceipt');
    }

    public function intReceipt(){
        Session::forget('cart');
        if(Session::has('int_id')) {

            $agent_rents_order = DB::table('inventory_sales')->where('id', Session::get('int_id'))->first();
            $dns = new DNS1D();
            $data = "data:image/png;base64,".$dns->getBarcodePNG($agent_rents_order->barcode, "C39");


            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);

            file_put_contents(public_path().'/images/barcode/inventory/'.$agent_rents_order->barcode.'.png', $data);


            $caisher_name = DB::table('users')->where('email', $agent_rents_order->cashier_email)->first();
//            $name = $caisher_name->first_name.' '.$caisher_name->last_name;
//            if($agent_rents_order->cashier_email=='reservation@bikerent.nyc'){
//                $name = $agent_rents_order->extra_cashier_email;
//            }
            $location = DB::table('locations')->where('title', Session::get('location'))->first();
            $agent_rents_order = json_decode(json_encode($agent_rents_order), true);
//            $this->sendAgentEmail(Auth::user()->email);
            $products = DB::table('inventory_sale_item')->where('sale_id', Session::get('int_id'))->get();



            session(['rent_success'=>'Order completed!']);
            return view('bigbike.agent.receipt.intReceipt', ['agent_rents_order' => $agent_rents_order, 'products'=>$products, 'rent_success' => 'Order Completed!','caisher_name'=>$caisher_name->first_name.' '.$caisher_name->last_name,'user'=>$caisher_name,'location'=>$location]);
        }else{
            return redirect()->route('agent.sportsSale');
        }
    }

    public function addPage(){
        $categories = DB::table('inventory_category')->get();

        return view('bigbike.agent.sports.add',['categories'=>$categories]);
    }

    public function addToInt(Request $request){
        try{
            $int_id = DB::table('inventory')->insertGetId([
                'name' => $request->inventory_name,'price' => number_format($request->inventory_price,2),'category' => $request->inventory_cat,
                'size'=>$request->inventory_size,'quantity'=>$request->quantity,'barcode'=>$request->inventory_barcode,'location'=>session('location')
            ]);


        }catch(\Exception $exception){

            return json_encode($exception->getMessage());
        }
        session(['success'=>'Product added']);
        return redirect()->route('agent.sportsSale');
    }


}