<?php

namespace App\Http\Controllers\BigBike\Agent;

use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Laracasts\Utilities\JavaScript\JavaScriptServiceProvider;
use Stripe\Charge;
use Stripe\Stripe;
use Auth;
use Illuminate\Support\Facades\DB;
use Mail;
use \Milon\Barcode\DNS1D;
use PHPMailerAutoload;
use PHPMailer;
use GuzzleHttp\Client;



//require '/PayPal-PHP-SDK/autoload.php';
//require __DIR__ . '/../bootstrap.php';
//use PayPal\Api\Amount;
//use PayPal\Api\Details;
//use PayPal\Api\FundingInstrument;
//use PayPal\Api\Item;
//use PayPal\Api\ItemList;
//use PayPal\Api\Payer;
//use PayPal\Api\Payment;
//use PayPal\Api\PaymentCard;
//use PayPal\Api\Transaction;
//use PayPal\Api\CreditCard;
//use Paypal\Exception;



class RentController extends Controller implements AgentInterface
{
    protected $location_url = "https://inventory.bikerent.nyc/api/v1/locations";
    protected $location_inventory_url = "https://inventory.bikerent.nyc/api/v1/locations/";
    protected $location_inventory_delete_url = "https://inventory.bikerent.nyc/api/v1/purchase/";
    protected $location_id = 1;

    public function getOrder(){
        if(!Session::has('cashier')){
//            $uc = new UserController();
//            return $uc->getLogout();
            return redirect()->route('user.logout');
        }

        return view('bigbike.agent.rent-order');
    }

    private function getInventory(){
        $client = new Client();
        $res = $client->get($this->location_url);
//            echo $res->getStatusCode(); // 200

//            echo $res->getBody(); // { "type": "User", ....
        $locations = json_decode($res->getBody());
//            dd(Session::get("location"));
        $location_obj = DB::table("locations")->where("title",Session::get("location"))->first();
//            dd($location_obj->inventory_id);
//            dd($locations);
        $cur_location = null;
        foreach ($locations as $location){
            if($location->id==$location_obj->inventory_id){
                $cur_location = $location;
                $this->location_id = $location->id;
                $this->location_inventory_url = $this->location_inventory_url.$location->id;
//                    dd($this->location_inventory_url);
                break;
            }
        }
        if(!isset($cur_location)){
            return "no such location";
        }
        $res = $client->get($this->location_inventory_url);
        $location_inventory = json_decode($res->getBody());
        return $location_inventory;
    }


    public function getRent(){
//        $location_inventory = $this->getInventory();
//        if(!isset($location_inventory)){
//            $location_inventory = null;
//        }

        if(!Session::has('cashier')){
//            $uc = new UserController();
//            return $uc->getLogout();
            return redirect()->route('user.logout');
        }
//        if(Session::get('location')=='40W 55th Street'){
//            $agent_rent_table= DB::table('agent_rents_big_discount')->get();
//        }else{
            $agent_rent_table= DB::table('agent_rents')->get();

//        }
        $memberships= DB::table('member_types')->get();
        $agents= DB::table('agents')->where('location', Session::get('location'))->where("active",1)->get();
        $user = DB::table('users')->where('email', Session::get('cashierEmail'))->first();

        if(Session::has('rent_id')){
            $agent_rents_order_cc = DB::table('pos_rents_orders')->where('id', Session::get('rent_id'))->first();
        }else{
            $agent_rents_order_cc = null;
        }
        $agent_rents_order_cc = json_decode(json_encode($agent_rents_order_cc), true);

        session(['rent_id'=>null]);

        return view('bigbike.agent.rent-main',[
            'agent_rent_table'=>$agent_rent_table,
            'memberships'=>$memberships,
            'agents'=>$agents,'agent_rents_order'=>null,
            'agent_rents_order_cc'=>$agent_rents_order_cc,
            'user'=>$user
//            'inventories'=> isset($location_inventory)?$location_inventory->stocks:null
        ]);

    }

//    public function calculate(){
//        $rent_duration = $_GET['rent_duration'];
//        $tax = 1.08875;
//        if($_GET['rent_duration']=="All Day (8am-8pm)"){
//            $rent_duration = 'all day';
//        }
//
//        $agentrents = DB::table('agent_rents')->where('title', $rent_duration)->first();
//        $adult_bike = intval($_GET['adult_bike']);
//        $child_bike = intval($_GET['child_bike']);
//        if($rent_duration != '24 hours') {
//            $tandem_bike = intval($_GET['tandem_bike']);
//            $road_bike = intval($_GET['road_bike']);
//            $mountain_bike = intval($_GET['mountain_bike']);
//            $trailer = intval($_GET['trailer']);
//            $seat = intval($_GET['seat']);
//            $basket = intval($_GET['basket']);
//        }else{
//            $tandem_bike = 0;
//            $road_bike = 0;
//            $mountain_bike = 0;
//            $trailer = 0;
//            $seat = 0;
//            $basket = 0;
//        }
//        $total_price = 0;
//        $total_bikes = $adult_bike+$child_bike+$tandem_bike+$road_bike+$mountain_bike;
//        $total_price += $agentrents->adult*$adult_bike;
//        $total_price += $agentrents->child*$child_bike;
//        $total_price += $agentrents->tandem*$tandem_bike;
//        $total_price += $agentrents->road*$road_bike;
//        $total_price += $agentrents->mountain*$mountain_bike;
//        $total_price += $agentrents->trailer*$trailer;
//        $total_price += $agentrents->seat*$seat;
//        $total_price += $agentrents->basket*$basket;
//        if($_GET['insurance']){
//            $total_price+= $agentrents->insurance*$total_bikes;
//        }
//        if($_GET['dropoff']){
//            $total_price+= $agentrents->dropoff*$total_bikes;
//        }
//        $total_price_after_tax = number_format($total_price*$tax,2);
//        //echo $request;
//
//        session(['total_price_after_tax' => $total_price_after_tax]);
//        return ['total_price_after_tax' => session('total_price_after_tax')];
//    }

    public function calculate(Request $request){

        $rent_duration = $_POST['rent_duration'];
//        $tax = 1;
        $tax = 1.08875;

        if($_POST['rent_duration']=="All Day (8am-8pm)"){
            $rent_duration = 'all day';
        }

//        $agentrents = DB::table('agent_rents')->where('title', $rent_duration)->first();
        if(Session::get('location')=='40W 55th Street'  && $request->reservation_bike=='reservation'){
            $agentrents= DB::table('agent_rents_big_discount')->where('title', $rent_duration)->first();
        }else{
            $agentrents= DB::table('agent_rents')->where('title', $rent_duration)->first();

        }
        $adult_bike = intval($_POST['adult_bike']);
        $child_bike = intval($_POST['child_bike']);
        if($rent_duration != '24 hours') {
            $tandem_bike = intval($_POST['tandem_bike']);
            $road_bike = intval($_POST['road_bike']);
            $mountain_bike = intval($_POST['mountain_bike']);
            $kid_trailer = intval($request->kid_trailer);
            $electric_bike = intval($request->electric_bike);
//            elliptigo
            $elliptigo = intval($request->elliptigo);
            $electric_hand = intval($request->electric_hand);
            $snow = intval($request->snow);
            $tricycle = intval($request->tricycle);

        }else{
            $tandem_bike = 0;
            $road_bike = 0;
            $mountain_bike = 0;
            $kid_trailer = 0;
            $electric_bike = 0;
            $elliptigo = 0;
            $electric_hand = 0;
            $snow = 0;
            $tricycle = 0;

//            $trailer = 0;
//            $seat = 0;
//            $basket = 0;
        }
        
        $trailer = intval($_POST['trailer_bike']);
        $seat = intval($_POST['seat_bike']);
        $basket = intval($_POST['basket_bike']);

        $total_price = 0;
        $total_bikes = $adult_bike+$child_bike+$tandem_bike+$road_bike+$mountain_bike+$elliptigo+$tricycle;
        $total_doubleBikes = $tandem_bike+$road_bike+$mountain_bike+$elliptigo;
        $total_price += $agentrents->adult*$adult_bike;
        $total_price += $agentrents->child*$child_bike;
        $total_price += $agentrents->tandem*$tandem_bike;
        $total_price += $agentrents->road*$road_bike;
        $total_price += $agentrents->mountain*$mountain_bike;
        $total_price += $agentrents->kid_trailer*$kid_trailer;
        $total_price += $agentrents->electric_bike*$electric_bike;
        $total_price += $agentrents->elliptigo*$elliptigo;
        $total_price += $agentrents->electric_hand*$electric_hand;
        $total_price += $agentrents->snow*$snow;
        $total_price += $agentrents->tricycle*$tricycle;


        if(isset($_POST['dropoff'])){
            $total_price+= $agentrents->dropoff*$total_bikes;
        }

        if(isset($_POST['member_checkbox']) && ($request->member_type=='Month Pass/$45'|| $request->member_type=='Annual Pass/$129')){
            $total_price = 3;
        }else if(isset($_POST['coupon_bike'])){
            $total_price = 0;
            if(isset($_POST['dropoff'])){
                $total_price+= $agentrents->dropoff*$total_bikes;
            }
        }


        //calculate after membership
        $total_price += $agentrents->trailer*$trailer;
        $total_price += $agentrents->seat*$seat;
        $total_price += $agentrents->basket*$basket;
        if(isset($_POST['insurance'])){

            $total_price+= $agentrents->insurance*($total_bikes-$total_doubleBikes)+498*$electric_bike;
            $total_price+= 4*$total_doubleBikes;
        }


        if(isset($_POST['coupon_bike'])){
            $total_price = 0;
            if(isset($_POST['insurance'])){
                $total_price+= $agentrents->insurance*($total_bikes-$total_doubleBikes)+498*$electric_bike;
                $total_price+= 4*$total_doubleBikes;
            }
            if(isset($_POST['dropoff'])){
                $total_price+= $agentrents->dropoff*$total_bikes;
            }
            $total_price += $agentrents->basket*$basket;
            $total_price += $agentrents->seat*$seat;
        }


        if($request->rent_adjust!=null){
            $original_price = $total_price;

            $total_price = $request->rent_adjust;
//            $total_price = $total_price*(1-floatval($request->rent_discount)*0.01);

            if(isset($_POST['insurance'])){
                $total_price+= $agentrents->insurance*($total_bikes-$total_doubleBikes)+498*$electric_bike;
                $total_price+= 4*$total_doubleBikes;
            }
            if(isset($_POST['dropoff'])){
                $total_price+= $agentrents->dropoff*$total_bikes;
            }
            $total_price += $agentrents->basket*$basket;

        }else{
            $original_price = null;
        }

        if(isset($_POST['coupon_bike'])){
            $total_price_after_tax = number_format(floor($total_price * 1*100)/100,2);

        }else {
            $total_price_after_tax = number_format(floor($total_price *$tax*100)/100,2);
        }
        if(isset($request->rent_deposit)) {
            $total_price_after_tax += floatval($request->rent_deposit);
        }

        //echo $request;

        session(['original_price'=>$original_price,'total_price_before_tax'=>$total_price,'total_price_after_tax' => $total_price_after_tax,'total_bikes'=>$total_bikes]);
        return ['total_price_after_tax' => session('total_price_after_tax')];
    }

    public function submitForm(Request $request){

        if(!Session::has('cashier')){
            return redirect()->route('user.logout');
        }
//        dd($request->member_type);

        $this->calculate($request);

        if($request->has('credit_card')){
            $payment_type = $request->credit_card;
            $order_completed = 0;
            $served = 0;
            //$rent_agent_total_pay = session('total_price_after_tax');
            $rent_agent_total_pay= session('total_price_after_tax');

            if(floatval($request->rent_total_pay)>floatval(session('total_price_after_tax'))){
                //price too low, can not complete the transaction
                session(['rent_price_error' => 'price is too high']);
                return redirect()->route('agent.rentOrder');
            }
        }else{
            $payment_type = $request->cash;
            $order_completed = 1;
            $served = 1;
            $rent_agent_total_pay = $request->rent_tips_label;

            if(floatval($request->rent_tips_label) > floatval(session('total_price_after_tax'))*0.3+0.01){
                //price too low, can not complete the transaction
                session(['rent_price_error' => 'agent price is too high to complete this order']);
//                session(['rent_price_error' => floatval($request->rent_tips_label)]);

                return redirect()->route('agent.rentOrder');
            }
        }

        $user = DB::table('users')->where('email', Session::get('cashierEmail'))->first();
        if($user->level==4){
//            dd($user->level);
            $served = 1;
            $payment_type = 'paypal';
        }

        if(isset($_POST['coupon_bike'])){
            $payment_type = $_POST['coupon_bike'];
        }

        if($request->member_checkbox){
            $customer_type = $request->member_type;
        }else if($request->member_guest_checkbox){
            $customer_type = $request->member_guest_checkbox;
        }else{
            $customer_type = "No";
        }

        if($request->rent_duration=='All Day (8am-8pm)'){


            $endTime = date('Y-m-d H:i:s', strtotime('today 8pm'));
        }else{
            $endTime = date('Y-m-d H:i:s', strtotime($request->rent_duration));
        }

        if(!isset($request->rent_deposit)){
            $deposit = 'ID';
            $depositPayType = 'ID';
        }else{
            $deposit = $request->rent_deposit;

            if($request->deposit_cc_checkbox) {
                $depositPayType = 'Credit Card';
            }elseif ($request->deposit_cash_checkbox){
                $depositPayType = 'Cash';
            }
        }

//        dd(date("Y-m-d H:i:s",strtotime($request->rent_duration)));
//        dd($request->rent_comment);

        if(isset($request->rent_adjust)){
            $adjust_price = $request->rent_adjust;
        }else{
            $adjust_price = null;
        }

        $ac = new AgentController();
        $array = $ac->getLocationTable(Session::get('location'));
        $num = $array[0];
        $table = $array[1];

//        session(['test'=>(floatval(session('total_price_after_tax'))-($deposit=='ID'? 0:floatval($deposit)))]);
        session(['test'=>floatval(str_replace(',','',session('total_price_after_tax')))]);

        $rent_id = DB::table('pos_rents_orders')->insertGetId([
            'location' => session('location'),'customer_name' => $request->rent_customer,'customer_lastname' => $request->rent_customer_last,'customer_email' => $request->rent_email,'customer_type'=>$customer_type,
            'agent_name' => strtoupper($request->rent_agent), 'agent_level' => $request->rent_agent_level, 'cashier_email' => Session::get('cashierEmail'), 'order_completed' => $order_completed,'served_date'=>date("Y-m-d H:i:s"),
            'payment_type' => $payment_type, 'order_id' => "",'original_price'=>session('original_price'),'total_price_before_tax'=>session('total_price_before_tax'),
            'total_price_after_tax' => floatval(str_replace(',','',session('total_price_after_tax')))-($deposit=='ID'? 0:floatval($deposit)),
            'rendered_cash'=>$request->rent_rendered-($deposit=='ID'? 0:floatval($deposit)),'deposit'=>$deposit,'deposit_pay_type'=>$depositPayType,'created_at'=>date("Y-m-d H:i:s"),'date' => $request->rent_date,
            'time' => date("Y-m-d H:i:s"),
            'adult' => $request->adult_bike,'end_time'=> $endTime,
            'child' => $request->child_bike,
            'tandem' => $request->tandem_bike,
            'road' => $request->road_bike,
            'mountain' => $request->mountain_bike,
            'kid_trailer' => $request->kid_trailer,
            'electric_bike' => $request->electric_bike,
            'elliptigo' => $request->elliptigo,
            'tricycle' => $request->tricycle,
            'electric_hand' => $request->electric_hand,
            'snow' => $request->snow,
            'total_bikes'=>session('total_bikes'),
            'trailer' => $request->trailer_bike,
            'basket' => $request->basket_bike, 'seat' => $request->seat_bike,'lock'=>$request->lock_bike,
            'dropoff' => $request->dropoff=='on'?1:0, 'insurance' => $request->insurance=='on'?1:0,
            'served'=>$served,'customer_address_phone'=>$request->rent_customer_address_phone,'customer_country'=>$request->rent_country
            , 'duration' => $request->rent_duration, 'comment' => $request->comment,
            'adjust_price'=>$adjust_price,'adjust_percentage'=>$request->rent_discount,'sequantial'=>strtoupper($table).$num
        ]);

        Session::forget('total_bikes');
        if($request->has('cash')){
            $ac = new AgentController();
            $barcode = $ac->barcodeEncode(intval($rent_id),'PR');
            try{
                DB::table('pos_rents_orders')
                    ->where('id', $rent_id)
                    ->update(['barcode'=>$barcode]);

                //update inventory database
                if(Session::has("inv_cart") && Session::get("inv_cart")["price"]>0){
                    $invController = new InventoryController();
                    $invController->updateDB($request->cc_firstname,$request->cc_lastname,$payment_type,"","cash","rent");
                }

            }catch(\Exception $exception){

                return redirect()->route('agent.rentOrder')->with('error', $exception->getMessage());
            }

            session(['rent_id'=>$rent_id]);
            //return redirect()->route('agent.main');
            //$agent_rents_order = DB::table('agent_rents_orders')->where('id', $order_id)->get();
            //session(['success'=>'transaction completed!']);
            //$agent_rents_order = json_decode(json_encode($agent_rents_order), true);

            //deposit with credit
            if(isset($request->rent_deposit) && $request->deposit_cc_checkbox){
                session(['rent_id' => $rent_id, 'agent_price_after_tax' => session('total_price_after_tax'), 'deposit'=>'deposit','rent_edit'=>null,'tour_edit'=>null,'tour'=>null,'rent'=>null,'net_price'=>$request->rent_deposit]);
                return view('bigbike.agent.cc-checkout',['price'=>$request->rent_deposit,'firstname'=>$request->rent_customer,'lastname'=>$request->rent_customer_last]);
            }

            return redirect()->route('agent.rentReceipt');
            //return view('bigbike.agent.agent-receipt',['agent_rents_order'=>$agent_rents_order[0],'rent_success'=>'Order Completed!']);
        }

        if($request->has('credit_card')){
            if(isset($request->rent_deposit) && $request->deposit_cash_checkbox){
                $tmp = floatval(session('total_price_after_tax'));
                $tmp -= intval($request->rent_deposit);
                session(['total_price_after_tax'=>$tmp,'net_price'=>$tmp]);

            }
        }


        session(['sequantial'=>strtoupper($table).$num,'rent_id' => $rent_id, 'agent_price_after_tax' => session('total_price_after_tax'), 'rent'=>'rent','tour'=>null,'rent_edit'=>'rent_edit','tour_edit'=>null,'deposit'=>null,"net_price"=>session('total_price_after_tax')]);
        //$this->paypalTest();
        return view('bigbike.agent.cc-checkout',['price'=>session('total_price_after_tax'),'firstname'=>$request->rent_customer,'lastname'=>$request->rent_customer_last]);
    }

//    public function submitForm(Request $request){
//
//        if($request->has('credit_card')){
//            $payment_type = $request->credit_card;
//            $order_completed = 0;
//            $rent_agent_total_pay = session('total_price_after_tax');
//
//            if($request->rent_total_pay>session('total_price_after_tax')){
//                //price too low, can not complete the transaction
//                session(['rent_price_error' => 'price is too high']);
//                return redirect()->route('agent.rentOrder');
//            }
//        }else{
//            $payment_type = $request->cash;
//            $order_completed = 1;
//            $rent_agent_total_pay = $request->rent_agent_total_pay;
//
//            if($request->rent_agent_total_pay>session('total_price_after_tax')*0.3){
//                //price too low, can not complete the transaction
//                session(['rent_price_error' => 'agent price is too high to complete this order']);
//                return redirect()->route('agent.rentOrder');
//            }
//        }
//
//
//        $rent_id = DB::table('agent_rents_orders')->insertGetId([
//            'customer_name' => $request->rent_customer_pay,'customer_email' => $request->rent_email_pay,'agent_email' => Auth::user()->email, 'order_completed' => $order_completed, 'payment_type' => $payment_type, 'order_id' => "",'total_price_after_tax' => session('total_price_after_tax'),'agent_price_after_tax' => $rent_agent_total_pay,'created_at'=>date("Y-m-d H:i:s"),'date' => $request->rent_date_pay, 'time' => $request->rent_time_pay, 'adult' => $request->adult_bike_pay
//            , 'child' => $request->child_bike_pay, 'tandem' => $request->tandem_bike_pay, 'road' => $request->road_bike_pay, 'mountain' => $request->mountain_bike_pay, 'trailer' => $request->trailer_pay,'basket' => $request->basket_pay, 'seat' => $request->seat_pay, 'dropoff' => $request->dropoff_pay=='on'?1:0, 'insurance' => $request->insurance_pay=='on'?1:0
//            , 'duration' => $request->rent_duration_pay, 'comment' => ""
//        ]);
//
//
//        if($request->has('cash')){
//            $ac = new AgentController();
//            $barcode = $ac->barcodeEncode(intval($rent_id));
//
//            try{
//
//                DB::table('agent_rents_orders')
//                    ->where('id', $rent_id)
//                    ->update(['barcode'=>$barcode]);
//
//            }catch(\Exception $exception){
//
//                return redirect()->route('agent.rentOrder')->with('error', $exception->getMessage());
//
//            }
//
//
//            session(['rent_id'=>$rent_id]);
//            //return redirect()->route('agent.main');
//            //$agent_rents_order = DB::table('agent_rents_orders')->where('id', $order_id)->get();
//            //session(['success'=>'transaction completed!']);
//            //$agent_rents_order = json_decode(json_encode($agent_rents_order), true);
//
//            return redirect()->route('agent.rentReceipt');
//            //return view('bigbike.agent.agent-receipt',['agent_rents_order'=>$agent_rents_order[0],'rent_success'=>'Order Completed!']);
//        }
//
//        session(['rent_id' => $rent_id, 'agent_price_after_tax' => $request->rent_total_pay, 'rent'=>'rent','tour'=>null]);
//        return view('bigbike.agent.cc-checkout');
//    }


    public function postCCCheckout(Request $request){
        if(!Session::has('cashier')){
//            $uc = new UserController();
//            return $uc->getLogout();
            return redirect()->route('user.logout');
        }

        if(!Session::has('agent_price_after_tax')){
            return redirect()->route('agent.rentOrder');
        }

        Stripe::setApiKey('sk_test_9P20f4nfmi3L4tAqGZkZgf30');
        // Token is created using Stripe.js or Checkout!
        // Get the payment token submitted by the form:
        $token = $_POST['stripeToken'];

        try{
            $charge = \Stripe\Charge::create(array(
                "amount" =>  Session::get('agent_price_after_tax')*100,
                "currency" => "usd",
                "description" => "Example charge",
                "source" => $token,
            ));
            //update db
            $rent_id = Session::get('rent_id');
            $ac = new AgentController();
            $barcode = $ac->barcodeEncode(intval($rent_id),'PR');

            DB::table('pos_rents_orders')
                ->where('id', $rent_id)
                ->update(['order_completed' => 1, 'order_id'=>$charge->id,'customer_name'=>$request->cardholder_name,'completed_at'=> date("Y-m-d H:i:s"),'barcode'=>$barcode]);

        }catch(\Exception $exception){
            return redirect()->route('agent.rentOrder')->with('rent_price_error', $exception->getMessage());
        }
        //return view('bigbike.agent.agent-rent-receipt',['agent_rents_order'=>$agent_rents_order[0],'rent_success'=>'Order Completed!','barcode'=>Session::pull('order_id')]);
        return redirect()->route('agent.rentReceipt');
    }

//    public function getPPToken(){
//        $client_id = 'Af9Vk-WKKPpXfot5k3HqGU_FUblvVzhY6Dc35zGqTGVj4DeLAPhRhhaMD7yxdUBIw-ggJKOhaYOSqKz1';
//        $secret = 'EO2lMk6DSB6AiriCW4idHsfcRsXsS4pJoQh0-W5W89Qwuj8nyiCE0cGzQsbLHbJnzSbaUKywPlE2G9H3';
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//            'Accept: application/json',
//            'Accept-Language: en_US'
//        ));
//        curl_setopt($ch, CURLOPT_URL,"https://api.sandbox.paypal.com/v1/oauth2/token");
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_USERPWD, "$client_id:$secret");
//        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
//
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//        $server_output = curl_exec ($ch);
//        curl_close ($ch);
//        $data = json_decode($server_output);
//        return $data->access_token;
//    }
//
//    public function makePPPmt(Request $request){
//
//        $token = $this->getPPToken();
//        $tmp = explode('/', $request->cc_expiration);
//        $expire_month = intval(trim($tmp[0]));
//        $expire_year = intval(trim($tmp[1]));
//        $cc_number = preg_replace('/\s+/', '', $request->cc_number);
//
////        4710717577717020
//        //payment
//        $data_json = array("intent"=>"sale",
//            "redirect_urls"=>array(
//                "return_url"=>"http://127.0.0.1:8000/bigbike/agent/rent/order",
//                "cancel_url"=>"http://127.0.0.1:8000/bigbike/agent/main"
//            ),
//            "payer"=>array(
//                "payment_method"=>"credit_card",
//                "funding_instruments"=>array(
//                    array(
//                        "credit_card"=>array(
//                            "number"=> $cc_number,
//                            "type"=>"visa",
//                            "expire_month"=>$expire_month,
//                            "expire_year"=>$expire_year,
//                            "cvv2"=>$request->cc_cvc,
////                            "first_name"=>"Betsy",
////                            "last_name"=>"Buyer",
////                            "billing_address"=>array(
////                                "line1"=>"111 First Street",
////                                "city"=>"Saratoga",
////                                "state"=>"CA",
////                                "postal_code"=>"95070",
////                                "country_code"=>"US"
////                            )
//                        )
//                    )
//                )
//            ),
//            "transactions"=>array(
//                array(
//                    "amount"=>array(
//                        "total"=> Session::get('agent_price_after_tax'),
//                        "currency"=>"USD"
//                    )
//                )
//            )
//        );
//
//        $data_json = json_encode($data_json);
//
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//            'Content-Type: application/json',
//            'Authorization: Bearer '.$token
//        ));
//        curl_setopt($ch, CURLOPT_URL,"https://api.sandbox.paypal.com/v1/payments/payment");
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
//
//
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//
//        $server_output = curl_exec ($ch);
//        curl_close ($ch);
//        $data = json_decode($server_output);
//        return $data;
//    }


    public function getCheckout(){

        return redirect()->route('agent.404');
    }

    public function postppCheckout(Request $request){
        if(!Session::has('cashier')){
//            $uc = new UserController();
//            return $uc->getLogout();
            return redirect()->route('user.logout');
        }
//        dd($request->_token);

        if(Session::token() != $request->_token){
            return redirect()->route('agent.rentOrder',['error'=>'token not valid, please redo the transaction']);

        }

        if(!Session::has('agent_price_after_tax')){
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
            $rent_id = Session::get('rent_id');
            $barcode = $ac->barcodeEncode(intval($rent_id),'PR');
            $refund_id = $data->transactions[0]->related_resources[0]->sale->id;
//            dd(gettype($refund_id));
            $user = DB::table('users')->where('email', Session::get('cashierEmail'))->first();
            if($user->level==4){
                $served = 1;
                $payment_type = 'paypal';
            }else{
                $served = 1;
                $payment_type = 'Credit Card';
            }

            try{
                DB::table('pos_rents_orders')
                    ->where('id', $rent_id)
                    ->update(['order_completed' => 1,'customer_cc_name' => $request->cc_firstname,'customer_cc_lastname' => $request->cc_lastname,'order_id' => $data->id,
                        'completed_at' => date("Y-m-d H:i:s"),'payment_type'=>$payment_type, 'barcode' => $barcode,'served'=>$served,'refund_id'=>$refund_id]);

                //update inventory database
                if(Session::has("inv_cart") && Session::get("inv_cart")["price"]>0){
                    $invController = new InventoryController();
                    $invController->updateDB($request->cc_firstname,$request->cc_lastname,$payment_type,$data->id,$refund_id,"rent");
                }

            }catch(\Exception $exception){
                return redirect()->route('agent.rentOrder')->with('rent_price_error', $exception->getMessage());
            }
        }else{
            return redirect()->route('agent.rentOrder')->with('rent_price_error', "credit card is declined");
        }
        Session::forget('agent_price_after_tax');
        //return view('bigbike.agent.agent-rent-receipt',['agent_rents_order'=>$agent_rents_order[0],'rent_success'=>'Order Completed!','barcode'=>Session::pull('order_id')]);
        return redirect()->route('agent.rentReceipt');
    }

    public function printReceipt(){
        if(Session::has('rent_id')) {

            if(Session::get('cashierEmail')=='s.tcukanov@gmail.com' || Session::get('cashierEmail')=='xdrealmadrid@gmail.com'){
                $agent_rents_order = DB::table('pos_rents_orders')->where('id', Session::get('rent_id'))->first();
            }else {
                $agent_rents_order = DB::table('pos_rents_orders')->where('id', Session::pull('rent_id'))->first();
            }
            $dns = new DNS1D();
            $data = "data:image/png;base64,".$dns->getBarcodePNG($agent_rents_order->barcode, "C39");


            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);

            file_put_contents(public_path().'/images/barcode/rent/'.$agent_rents_order->barcode.'.png', $data);


            $caisher_name = DB::table('users')->where('email', $agent_rents_order->cashier_email)->first();
//            $name = $caisher_name->first_name.' '.$caisher_name->last_name;
//            if($agent_rents_order->cashier_email=='reservation@bikerent.nyc'){
//                $name = $agent_rents_order->extra_cashier_email;
//            }
            $location = DB::table('locations')->where('title', Session::get('location'))->first();

//            $user = DB::table('users')->where('email', Auth::user()->email)->first();
//            if($user->level==4){
//                $this->sendCustomerEmail2($agent_rents_order->customer_email, $agent_rents_order);
//                $this->sendAgentEmail(Auth::user()->email,$agent_rents_order);
//            }

            $agent_rents_order = json_decode(json_encode($agent_rents_order), true);
//            $this->sendAgentEmail(Auth::user()->email);



            session(['rent_success'=>'Order completed!']);
//            if(Session::has('inv_cart') && Session::get("inv_cart")["price"]>0) {
//                session(["inventory_success" => true]);
//            }
            return view('bigbike.agent.rent-receipt', ['agent_rents_order' => $agent_rents_order, 'rent_success' => 'Order Completed!','caisher_name'=>$caisher_name->first_name.' '.$caisher_name->last_name,'user'=>$caisher_name,'location'=>$location]);
        }else{
            return redirect()->route('agent.main');
        }
    }

    public function printReceiptFromReturn($id){

        $agent_rents_order = DB::table('pos_rents_orders')->where('id', $id)->first();
        $dns = new DNS1D();
        $data = "data:image/png;base64,".$dns->getBarcodePNG($agent_rents_order->barcode, "C39");


        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);

        file_put_contents(public_path().'/images/barcode/rent/'.$agent_rents_order->barcode.'.png', $data);


        $caisher_name = DB::table('users')->where('email', $agent_rents_order->cashier_email)->first();
        $location = DB::table('locations')->where('title', Session::get('location'))->first();


        $agent_rents_order = json_decode(json_encode($agent_rents_order), true);
//            $this->sendAgentEmail(Auth::user()->email);

        session(['rent_success'=>'Order completed!']);
        return view('bigbike.agent.rent-receipt', ['agent_rents_order' => $agent_rents_order, 'rent_success' => 'Order Completed!','caisher_name'=>$caisher_name->first_name.' '.$caisher_name->last_name,'user'=>$caisher_name,'location'=>$location]);

    }


    public function printTicket(){
        if(Session::has('rent_id')) {

            $agent_rents_order = DB::table('pos_rents_orders')->where('id', Session::get('rent_id'))->get();
            $agent_rents_order = json_decode(json_encode($agent_rents_order), true);
            session(['rent_success'=>'Order completed!']);
            return view('bigbike.agent.rent-ticket', ['agent_rents_order' => $agent_rents_order[0], 'rent_success' => 'Order Completed!']);
        }else{
            return redirect()->route('agent.main');
        }
    }

    public function getMembership(Request $request){

//        $rent_membership= $request->rent_membership;
//        dd($rent_membership);
        $member = DB::table('memberships')->where('member_number', $request->rent_membership)->first();
//        return $member->customer_name;
        return json_encode($member);

//        return $request->rent_membership;

    }

    public function showReservationDetail($id){


        $agent_rents_order = DB::table('pos_rents_orders')->where('id', $id)->first();
        if($agent_rents_order->duration=='All Day (8am-8pm)'){
            $duration = 'all day';
        }else{
            $duration = $agent_rents_order->duration;
        }
//        if(Session::get('location')=='40W 55th Street'){
//            $agent_rent_table= DB::table('agent_rents_big_discount')->where('title', $duration)->get();
//        }else{
            $agent_rent_table= DB::table('agent_rents')->where('title', $duration)->get();

//        }
//        $agent_rent_table= DB::table('agent_rents')->where('title', $duration)->get();

        $agent_rents_order = json_decode(json_encode($agent_rents_order), true);
        $agent_rent_table = json_decode(json_encode($agent_rent_table), true);


        return view('bigbike.agent.reservation.extra-service', ['agent_rents_order' => $agent_rents_order,'agent_rent_table'=>$agent_rent_table]);
    }


    public function updateReservation(Request $request){

        if($request->has('credit_card')){
            $payment_type = $request->credit_card;

        }else{
            $payment_type = $request->cash;
        }

        try{

            DB::table('pos_rents_orders')
                ->where('id', $request->id_bike)
                ->update(['basket'=>$request->basket_bike,'lock'=>$request->lock_bike,'dropoff'=>$request->dropoff=='on'?1:0,'insurance'=>$request->insurance=='on'?1:0,'extra_cashier_email'=>Session::get('cashierEmail'),
                    'extra_service_payment_type'=>$payment_type,'extra_service_total_before_tax'=>$request->rent_total_label,'extra_service_total_after_tax'=>$request->rent_total_after_tax,
                    'extra_service_rendered_cash'=>$request->rent_rendered,'extra_served_date'=>date("Y-m-d H:i:s")]);
            session(['rent_id'=>$request->id_bike,'agent_price_after_tax'=>$request->rent_total_after_tax]);

            if($request->has('cash')){

                return redirect()->route('agent.rentReceipt');
            }

            session(['agent_price_after_tax'=>$request->rent_total_after_tax,'rent_reserve'=>'rent_reserve','rent'=>null,'tour'=>null,"net_price"=>$request->rent_total_after_tax]);
            //$this->paypalTest();

            return view('bigbike.agent.cc-checkout',['price'=>$request->rent_total_after_tax,'firstname'=>$request->rent_customer,'lastname'=>$request->rent_customer_last]);

        }catch(\Exception $exception){

            return redirect()->route('agent.rentOrder')->with('error', $exception->getMessage());
        }

    }

    public function showReturnDetail($id){

        $agent_rents_order = DB::table('pos_rents_orders')->where('id', $id)->first();
        if($agent_rents_order->duration=='All Day (8am-8pm)'){
            $duration = 'all day';
        }else{
            $duration = $agent_rents_order->duration;
        }
//        $agent_rent_table= DB::table('agent_rents')->where('title', $duration)->get();
//        if(Session::get('location')=='40W 55th Street'){
//            $agent_rent_table= DB::table('agent_rents_big_discount')->where('title', $duration)->get();
//        }else{
            $agent_rent_table= DB::table('agent_rents')->where('title', $duration)->get();

//        }

        $agent_rents_order = json_decode(json_encode($agent_rents_order), true);
        $agent_rent_table = json_decode(json_encode($agent_rent_table), true);
        $user = DB::table('users')->where('email', $agent_rents_order['cashier_email'])->first();
        $user = json_decode(json_encode($user), true);

        return view('bigbike.agent.return.detail', ['agent_rents_order' => $agent_rents_order,'agent_rent_table'=>$agent_rent_table,'user'=>$user]);

    }

    public function showEditPage(Request $request){

//        dd($request->edit_id);
        if($request->has('edit')) {

//            $agent_rent_table = DB::table('agent_rents')->get();

            $memberships = DB::table('member_types')->get();
            $agents = DB::table('agents')->get();
            $user = DB::table('users')->where('email', Session::get('cashierEmail'))->first();
            $agent_rents_order = DB::table('pos_rents_orders')->where('id', $request->edit_id)->first();

//            if(Session::get('location')=='40W 55th Street' && $agent_rents_order->payment_type=="paypal"){
//                $agent_rent_table= DB::table('agent_rents_big_discount')->get();
//                session(['test' => $agent_rents_order->cashier_email]);
//            }else{
                $agent_rent_table= DB::table('agent_rents')->get();

//            }
//            $cashier = DB::table('users')->where('email', $agent_rents_order->cashier_email)->first();


            session(['previous_total_before_tax' => $agent_rents_order->total_price_before_tax, 'previous_total_price_after_tax' => $agent_rents_order->total_price_after_tax]);

            $agent_rents_order = json_decode(json_encode($agent_rents_order), true);

            return view('bigbike.agent.rent-main', ['agent_rent_table' => $agent_rent_table, 'memberships' => $memberships, 'agents' => $agents, 'agent_rents_order' => $agent_rents_order, 'isEdit' => 'true','user'=>$user,'agent_rents_order_cc'=>null]);
        }else if($request->has('delete')){
            //set returned 1
            DB::table('pos_rents_orders')
                ->where('id', $request->edit_id)
                ->update(['returned'=>1,'returned_date'=>date("Y-m-d H:i:s"),'returned_cashier'=>Session::get('cashierEmail')]);

            session(['error'=>'Delete Success']);
            return redirect()->route('agent.rentOrder');

        }else if($request->has('release_pp')){
            return $this->refundPP($request);
        }
    }

    public function reserveShowEditPage($id){
//        dd($request->edit_id);
//        $agent_rent_table = DB::table('agent_rents')->get();
//        if(Session::get('location')=='40W 55th Street'){
//            $agent_rent_table= DB::table('agent_rents_big_discount')->get();
//        }else{
            $agent_rent_table= DB::table('agent_rents')->get();

//        }
        $memberships = DB::table('member_types')->get();
        $agents = DB::table('agents')->get();
        $user = DB::table('users')->where('email', Session::get('cashierEmail'))->first();

        $agent_rents_order = DB::table('pos_rents_orders')->where('id', $id)->first();

        if(Session::get('location')=='40W 55th Street' && $agent_rents_order->payment_type=='paypal'){
            $agent_rent_table= DB::table('agent_rents_big_discount')->get();
//            session(['test' => "test"]);

        }else{
            $agent_rent_table= DB::table('agent_rents')->get();

        }
        session(['previous_total_before_tax' => $agent_rents_order->total_price_before_tax, 'previous_total_price_after_tax' => $agent_rents_order->total_price_after_tax]);

        $agent_rents_order = json_decode(json_encode($agent_rents_order), true);

        return view('bigbike.agent.rent-main', ['agent_rent_table' => $agent_rent_table, 'memberships' => $memberships, 'agents' => $agents, 'agent_rents_order' => $agent_rents_order, 'isEdit' => 'true','user'=>$user,'agent_rents_order_cc'=>null,'reservation'=>'reservation']);

    }

    public function editSubmitForm(Request $request){

        $this->calculate($request);

//        dd($request->credit_card);
        if($request->has('credit_card')){
            $payment_type = $request->credit_card;
            $order_completed = 0;
        }else{
            $payment_type = $request->cash;
            $order_completed = 1;
        }

//        dd($payment_type);
        if(isset($_POST['coupon_bike'])){
            $payment_type = $_POST['coupon_bike'];
        }

//        dd($payment_type);

        if($request->member_checkbox){
            $customer_type = $request->member_type;
        }else if($request->member_guest_checkbox){
            $customer_type = $request->member_guest_checkbox;
        }else{
            $customer_type = "No";
        }

        if($request->rent_duration=='All Day (8am-8pm)'){
            $endTime = date('Y-m-d H:i:s', strtotime('today 8pm'));
        }else{
            $endTime = date('Y-m-d H:i:s', strtotime($request->rent_duration));
        }

        if(isset($request->deposit_id_checkbox)){
            $deposit = 'ID';
            $deposit_payment_type = 'ID';
        }else{
            $deposit = $request->rent_deposit;
            if(isset($request->deposit_cash_checkbox)){
                $deposit_payment_type = 'Cash';
            }elseif(isset($request->deposit_cc_checkbox)){
                $deposit_payment_type = 'Credit Card';
            }
        }


        if(isset($request->reservation_bike) && $request->reservation_bike=='reservation'){
            $columnName = 'cashier_email';
            $deposit_columnName = 'deposit';
            $deposit_type_columnName = 'deposit_pay_type';
        }else{
            $columnName = 'extra_cashier_email';
            $deposit_columnName = 'extra_deposit';
            $deposit_type_columnName = 'extra_deposit_pay_type';

        }

        try {
            DB::table('pos_rents_orders')->where('id', $request->rent_id)
                ->update(['location' => session('location'),'extra_order_completed' => $order_completed, 'customer_name' => $request->rent_customer, 'customer_lastname' => $request->rent_customer_last, 'served' => 1,'served_date'=>date("Y-m-d H:i:s"), 'extra_served_date' => date("Y-m-d H:i:s"),
                    'extra_customer_type' => $customer_type, 'agent_name' => $request->rent_agent, 'agent_level' => $request->rent_agent_level, $columnName => Session::get('cashierEmail'),'extra_cashier_email' => Session::get('cashierEmail'), 'extra_order_completed' => $order_completed, 'extra_service_payment_type' => $payment_type,
                    'extra_service_total_before_tax' => $request->rent_total_label, 'extra_service_total_after_tax' => $request->rent_total_after_tax,'reservation'=>0,'customer_country'=>$request->rent_country,'customer_email'=>$request->rent_email,
                    'extra_service_rendered_cash' => $request->rent_rendered, $deposit_columnName => $deposit,$deposit_type_columnName => $deposit_payment_type, 'created_at' => date("Y-m-d H:i:s"), 'date' => $request->rent_date, 'time' => date("Y-m-d H:i:s"), 'end_time' => $endTime,
                    'adult' => $request->adult_bike,
                    'child' => $request->child_bike,
                    'tandem' => $request->tandem_bike,
                    'road' => $request->road_bike,
                    'mountain' => $request->mountain_bike,
                    'kid_trailer' => $request->kid_trailer,
                    'electric_bike' => $request->electric_bike,
                    'elliptigo' => $request->elliptigo,

                    'total_bikes' => session('total_bikes'),
                    'trailer' => $request->trailer_bike, 'basket' => $request->basket_bike,
                    'seat' => $request->seat_bike, 'lock' => $request->lock_bike,
                    'dropoff' => $request->dropoff == 'on' ? 1 : 0, 'insurance' => $request->insurance == 'on' ? 1 : 0, 'duration' => $request->rent_duration, 'comment' => $request->comment
                ]);

            if($request->has('cash')) {

                session(['rent_id' => $request->rent_id]);

                //return redirect()->route('agent.main');
                //$agent_rents_order = DB::table('agent_rents_orders')->where('id', $order_id)->get();
                //session(['success'=>'transaction completed!']);
                //$agent_rents_order = json_decode(json_encode($agent_rents_order), true);

                //deposit with credit
                if(isset($request->rent_deposit) && $request->deposit_cc_checkbox){
                    session(['rent_id' => $request->rent_id, 'agent_price_after_tax' => session('total_price_after_tax'), 'deposit'=>'deposit','rent_edit'=>null,'tour_edit'=>null,'tour'=>null,'rent'=>null,"net_price"=>number_format($request->rent_deposit,2)]);
                    return view('bigbike.agent.cc-checkout',['price'=>number_format($request->rent_deposit,2),'firstname'=>$request->rent_customer,'lastname'=>$request->rent_customer_last]);
                }

                return redirect()->route('agent.rentReceipt');
            }

        } catch (\Exception $exception) {
            return redirect()->route('agent.rentOrder')->with('rent_price_error', $exception->getMessage());
        }

        session(['rent_id' => $request->rent_id, 'agent_price_after_tax' => floatval(session('total_price_after_tax')) - floatval(session('previous_total_price_after_tax')), 'rent_edit'=>'rent_edit','tour_edit'=>null,'rent'=>null,'tour'=>null,"net_price"=>$request->rent_total_after_tax]);
        return view('bigbike.agent.cc-checkout',['price'=>$request->rent_total_after_tax,'firstname'=>$request->rent_customer,'lastname'=>$request->rent_customer_last]);

    }

    public function postReserveCheckout(Request $request){
        if(!Session::has('agent_price_after_tax')){
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
            $rent_id = Session::get('rent_id');

            try{
                DB::table('pos_rents_orders')
                    ->where('id', $rent_id)
                    ->update(['extra_order_completed' => 1,'extra_customer_name' => $request->cc_firstname,'extra_customer_lastname' => $request->cc_lastname,'extra_order_id' => $data->id, 'extra_completed_at' => date("Y-m-d H:i:s"),'served'=>1,'served_date'=>date("Y-m-d H:i:s")]);

            }catch(\Exception $exception){
                return redirect()->route('agent.rentOrder')->with('rent_price_error', $exception->getMessage());
            }
        }else{
            return redirect()->route('agent.rentOrder')->with('rent_price_error', "credit card is declined");
        }
        Session::forget('agent_price_after_tax');
        //return view('bigbike.agent.agent-rent-receipt',['agent_rents_order'=>$agent_rents_order[0],'rent_success'=>'Order Completed!','barcode'=>Session::pull('order_id')]);
        return redirect()->route('agent.rentReceipt');
    }

    public function finishReturn(Request $request){
//        dd($request);

        //release deposit and update db
        if($request->has('release')){
//            dd($request->release);
            try{
                DB::table('pos_rents_orders')
                    ->where('id', $request->id_bike)
                    ->update(['returned'=>1,'returned_cashier'=>Session::get('cashierEmail'),'returned_date'=>date("Y-m-d H:i:s")]);

                session(['rent_id'=>$request->id_bike]);

                return redirect()->route('agent.depoistReleaseReceipt');

            }catch(\Exception $exception){
                return redirect()->route('agent.rentOrder')->with('rent_price_error', $exception->getMessage());
            }
        }

        if($request->has('credit_card')){
            $payment_type = $request->credit_card;
            $order_completed = 0;
            $returned = null;

        }else{
            $payment_type = $request->cash;
            $order_completed = 1;
            $returned = 1;

        }

        try{
            DB::table('pos_rents_orders')
                ->where('id', $request->id_bike)
                ->update(['returned'=>$returned,'returned_cashier'=>Session::get('cashierEmail'), 'returned_order_completed' => $order_completed,'returned_date'=>date("Y-m-d H:i:s"),
                    'late_hours'=>json_decode(json_encode($request->late_hours_bike)),'late_fee'=>$request->late_fee_bike,
                    'returned_payment_type'=>$payment_type, 'returned_total'=>$request->rent_rendered,'returned_change'=>$request->rent_change]);

            if($request->has('cash')){
                session(['rent_id'=>$request->id_bike]);
                return redirect()->route('agent.returnReceipt');
            }

            session(['rent_id'=>$request->id_bike,'returned_total'=>$request->rent_total_after_tax,'rent_return'=>'rent_return','rent'=>null,'tour'=>null,"net_price"=>number_format($request->rent_total_after_after_tax,2)]);
            //$this->paypalTest();

            return view('bigbike.agent.cc-checkout',['price'=>number_format($request->rent_total_after_after_tax,2),'firstname'=>$request->rent_customer,'lastname'=>$request->rent_customer_last]);


        }catch(\Exception $exception){
            return redirect()->route('agent.rentOrder')->with('rent_price_error', $exception->getMessage());
        }
    }

    public function returnReceipt(){
        if(!Session::has('rent_id')){
            return redirect()->route('agent.404');
        }
        try{

            $agent_rents_order = DB::table('pos_rents_orders')->where('id', Session::get('rent_id'))->first();
            $caisher_name = DB::table('users')->where('email', $agent_rents_order->cashier_email)->first();
            $location = DB::table('locations')->where('title', Session::get('location'))->first();

            $agent_rents_order = json_decode(json_encode($agent_rents_order), true);


        }catch(\Exception $exception){
            return redirect()->route('agent.rentOrder')->with('rent_price_error', $exception->getMessage());
        }

        session(['rent_success'=>'Deposit Released']);

        return view('bigbike.agent.receipt.rent-return-receipt',['agent_rents_order'=>$agent_rents_order,'caisher_name'=>$caisher_name->first_name.' '.$caisher_name->last_name,'location'=>$location]);

    }

    public function depoistReleaseReceipt(){
        if(!Session::has('rent_id')){
            return redirect()->route('agent.404');
        }
        try{

            $agent_rents_order = DB::table('pos_rents_orders')->where('id', Session::get('rent_id'))->first();
            $caisher_name = DB::table('users')->where('email', $agent_rents_order->cashier_email)->first();
            $location = DB::table('locations')->where('title', Session::get('location'))->first();

            $agent_rents_order = json_decode(json_encode($agent_rents_order), true);


        }catch(\Exception $exception){
            return redirect()->route('agent.rentOrder')->with('rent_price_error', $exception->getMessage());
        }

        session(['rent_success'=>'Deposit Released']);

        return view('bigbike.agent.receipt.rent-release-deposit',['agent_rents_order'=>$agent_rents_order,'caisher_name'=>$caisher_name->first_name.' '.$caisher_name->last_name,'location'=>$location]);
    }

    public function rentReturnCheckout(Request $request){
        if(!Session::has('returned_total')){
            return redirect()->route('agent.404');
        }
//        dd(session('agent_price_after_tax'));
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
            $rent_id = Session::get('rent_id');

            try{
                DB::table('pos_rents_orders')
                    ->where('id', $rent_id)
                    ->update(['returned'=>1,'returned_order_completed' => 1,'returned_customer_name' => $request->cc_firstname,'returned_customer_lastname' => $request->cc_lastname,'returned_order_id' => $data->id,
                        'returned_completed_at' => date("Y-m-d H:i:s"),'returned'=>1,'returned_date'=>date("Y-m-d H:i:s"),'returned_total'=>session('returned_total')]);

            }catch(\Exception $exception){
                return redirect()->route('agent.rentOrder')->with('rent_price_error', $exception->getMessage());
            }
        }else{
            return redirect()->route('agent.rentOrder')->with('rent_price_error', "credit card is declined");
        }
        Session::forget('returned_total');
        //return view('bigbike.agent.agent-rent-receipt',['agent_rents_order'=>$agent_rents_order[0],'rent_success'=>'Order Completed!','barcode'=>Session::pull('order_id')]);
        return redirect()->route('agent.returnReceipt');
    }

    public function refundPP(Request $request){

        try{

            $cancel_order= DB::table('pos_rents_orders')->where('id', $request->edit_id)->first();

        }catch(\Exception $exception){
            return redirect()->route('agent.rentOrder')->with('rent_price_error', "Something is wrong, try again");

        }

        if(!$cancel_order->refund_id){
            return redirect()->route('agent.rentOrder')->with('rent_price_error', "No such Paypal Transaction");
        }

        $ac = new AgentController();
        $data = $ac->refundPP($cancel_order->refund_id, floatval($request->refund_amt));

//        dd($data);

        if(isset($data->{'name'}) && $data->{'name'}=="TRANSACTION_REFUSED"){
            return redirect()->route('agent.rentOrder')->with('rent_price_error', $data->{'message'});
        }


        if($data->{'state'}=='completed') {

            try{
                DB::table('pos_rents_orders')
                    ->where('id', $request->edit_id)
                    ->update(['refund_amt'=>$request->refund_amt,'refund_transaction_id' => $data->{'id'},'returned_completed_at' => date("Y-m-d H:i:s"),'returned'=>1,'returned_date'=>date("Y-m-d H:i:s"),'returned_cashier'=>Session::get('cashierEmail')]);

            }catch(\Exception $exception){
                return redirect()->route('agent.rentOrder')->with('rent_price_error', $exception->getMessage());
            }
        }else{
//            dd($data);
            return redirect()->route('agent.rentOrder')->with('rent_price_error', "not success");

        }

        session(['rent_id'=>$request->edit_id]);
        return redirect()->route('agent.depoistRefundReceipt');

    }

    public function depoistRefundReceipt(){

        if(Session::has('rent_id')) {
            $agent_rents_order = DB::table('pos_rents_orders')->where('id', Session::get('rent_id'))->first();

            $agent_rents_order = json_decode(json_encode($agent_rents_order), true);
            session(['rent_success'=>'Order completed!']);
            $caisher_name = DB::table('users')->where('email', Session::get('cashierEmail'))->first();
            $location = DB::table('locations')->where('title', Session::get('location'))->first();

            return view('bigbike.agent.rent-receipt-refund', ['agent_rents_order' => $agent_rents_order, 'rent_success' => 'Order Completed!','caisher_name'=>$caisher_name->first_name.' '.$caisher_name->last_name,'location'=>$location]);
        }else{
            return redirect()->route('agent.main');
        }
    }

    public function rentDepositCheckout(Request $request){

        if(!Session::has('rent_id')){
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
            $rent_id = Session::get('rent_id');
            $refund_id = $data->transactions[0]->related_resources[0]->sale->id;

            try{
                DB::table('pos_rents_orders')
                    ->where('id', $rent_id)
                    ->update(['order_completed' => 1,'customer_name' => $request->cc_firstname,'customer_lastname' => $request->cc_lastname,'order_id' => $data->id,
                        'refund_id'=>$refund_id,'completed_at' => date("Y-m-d H:i:s"), 'served'=>1]);

            }catch(\Exception $exception){
                return redirect()->route('agent.rentOrder')->with('rent_price_error', $exception->getMessage());
            }
        }else{
            return redirect()->route('agent.rentOrder')->with('rent_price_error', "credit card is declined");
        }
        //return view('bigbike.agent.agent-rent-receipt',['agent_rents_order'=>$agent_rents_order[0],'rent_success'=>'Order Completed!','barcode'=>Session::pull('order_id')]);
        return redirect()->route('agent.rentReceipt');

    }

    public function deleteRent(Request $request){
        $rent_ids = explode(",",$request->ids);

//        dd($ids);

        for($i=0; $i<count($rent_ids); $i++) {
            $id = $rent_ids[$i];
            if($id > 0) {
                try{
                    DB::table('pos_rents_orders')
                        ->where('id', $id)
                        ->update(['returned' => 1,
                            'returned_date'=>date("Y-m-d H:i:s"),
                            'returned_cashier'=>Session::get('cashierEmail')]);
                }catch(\Exception $exception){
                    return 'update not success';
                }
            }
        }
        return 'update success';
    }


    public function addAgent(Request $request){
        try{
            $agent= DB::table('agents')->where('fullname', $request->rent_agent)->get();
        }catch(\Exception $exception){
            return redirect()->route('agent.rentOrder')->with('rent_price_error', "Something is wrong, try again");
        }

        if($agent->count()==0){
            //update
            try{
                DB::table('agents')->insert([
                    'location' => session('location'),'fullname' => $request->rent_agent,'active'=>1,
                ]);
            }catch(\Exception $exception){
                return json_encode($exception);
            }
            return 'New Agent added';
        }else{
            return 'exists';
        }
    }



    public function editCheck(Request $request){
        try{
            $agent_rents_order = DB::table('pos_rents_orders')->where('id', $request->id)->first();
            if(empty($agent_rents_order)){
                return response()->json(['type'=>'error','response' => "Ooops, this transaction was not found"]);
            }else {
                if (!empty($agent_rents_order->extra_cashier_email)) {
                    return response()->json(['type'=>'error','response' => "You can't edit this transaction more than 1 time, if you still need to do it, delete this one and create a new one."]);
                }else {
                    return response()->json(['type' => 'noerror', 'response' => $agent_rents_order->extra_cashier_email]);
                }
            }

        }catch(\Exception $exception){
            return response()->json(['type'=>'return','response' => "error, go back and try again"]);
        }
    }

    public function getEsignature(){
        return view('bigbike.agent.receipt.esignature');
    }

    public function storeEsignature(Request $request){
//        dd(Session::get('rent_id'));
//        $agent_rents_order = DB::table('pos_rents_orders')->where('id', Session::get('rent_id'))->first();
        try{

            DB::table('pos_rents_orders')
            ->where('id', $request->id)
            ->update(['esignature'=>$request->id.'.png']);
//            return response()->json(['type'=>'return','response' => $request->dataURL]);
        }catch(\Exception $exception){
            return response()->json(['type'=>'error','response' => $request->dataURL]);
        }
//        $data = 'data:image/png;base64,'+$request->dataURL;
        $img = str_replace('data:image/png;base64,', '', $request->dataURL);
        $img = str_replace(' ', '+', $img);
//        $decodedData = base64_decode($encodedData);

//        list($type, $data) = explode(';', $request->dataURL);
//        list(, $data)      = explode(',', $request->dataURL);
//        $data = base64_decode($data);

        file_put_contents(public_path().'/images/esignature/rent/'.$request->id.'.png', base64_decode($img));
        return response()->json(['type'=>'good','response' => "uploading signature succeed"]);
    }

}
