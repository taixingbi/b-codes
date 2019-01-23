<?php

namespace App\Http\Controllers\BigBike\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\Charge;
use Stripe\Stripe;
use Auth;
use Illuminate\Support\Facades\DB;
use Mail;
use \Milon\Barcode\DNS1D;


class TourController extends Controller implements AgentInterface
{

    public function loginAgent(){

        if(!Session::has('cashier')){
//            $uc = new UserController();
//            return $uc->getLogout();
            return redirect()->route('user.logout');
        }

        $agent_rent_table= DB::table('agent_rents')->get();
        $agent_tour_table= DB::table('agent_tours')->get();
        return view('bigbike.agent.main',['agent_rent_table'=>$agent_rent_table,'agent_tour_table'=>$agent_tour_table]);
    }


    public function getOrder(){
        if(!Session::has('cashier')){
//            $uc = new UserController();
//            return $uc->getLogout();
            return redirect()->route('user.logout');
        }
//        return view('bigbike.agent.tour-order');
        $agent_tour_table= DB::table('agent_tours')->get();
        $agents = DB::table('agents')->get();
        $user = DB::table('users')->where('email', Session::get('cashierEmail'))->first();

        if(Session::has('tour_id')){
            $agent_tours_order_cc = DB::table('pos_tours_orders')->where('id', Session::get('tour_id'))->first();
        }else{
            $agent_tours_order_cc = null;
        }
        $agent_tours_order_cc = json_decode(json_encode($agent_tours_order_cc), true);
        session(['tour_id'=>null]);

        return view('bigbike.agent.tour-main',['agents' => $agents,'agent_tour_table'=>$agent_tour_table,'agent_tours_order'=>null,'type'=>'tour','user'=>$user,'agent_tours_order_cc'=>$agent_tours_order_cc]);

    }

    public function calculate(Request $request){

//        $tax = 1;
        $tax = 1.00;

        $agenttours = DB::table('agent_tours')->where('title', $_POST['tour_type'])->first();
        $adult_tour = intval($_POST['adult_tour']);
        $child_tour = intval($_POST['child_tour']);

        if(isset($_POST['tour_place']) && $_POST['tour_place'] == 'walking'){
            $total_price = 0;
            $total_price += 24.00*$adult_tour;
            $total_price += 19.00*$child_tour;
            $total_price += $agenttours->seat*$request->seat_tour;
            $total_price += intval($request->basket_tour);

        }
        else{

            $total_price = 0;
            $total_price += $agenttours->adult*$adult_tour;
            $total_price += $agenttours->child*$child_tour;
            $total_price += $agenttours->seat*$request->seat_tour;
            $total_price += intval($request->basket_tour);

        }


        if(isset($_POST['tour_coupon'])){
            $total_price = 0;
//            $total_price += $agenttours->seat*$request->seat_tour;
//            $total_price+= intval($request->basket_tour);
        }
        if(isset($_POST['tour_coupon'])){
            $total_price = 0;
//            $total_price += $agenttours->seat*$request->seat_tour;
//            $total_price+= intval($request->basket_tour);
        }


//        if(isset($_POST['insurance'])){
//            $total_price+= 2*($adult_tour+$child_tour);
//        }

        if($request->rent_adjust!=null){
            $original_price = $total_price;
            $total_price = $request->rent_adjust;
        }else{
            $original_price = null;
        }

//        $total_price += $agenttours->seat*$request->seat_tour;
//        $total_price += intval($request->basket_tour);
        if(isset($_POST['insurance'])){
            $total_price+= 2*($adult_tour+$child_tour);
        }


//        if($request->rent_discount!=null){
//            $original_price = $total_price;
//
//            $total_price = $total_price*(1-floatval($request->rent_discount)*0.01);
//        }else{
//            $original_price = null;
//        }

        session(['total_tour_price_before_tax'=>$total_price]);
        if(isset($_POST['tour_coupon'])){
            $total_price = number_format($total_price * 1,2);
        }else {
            $total_price = number_format($total_price * $tax,2);
        }

        if(isset($request->rent_deposit)) {
            $total_price += floatval($request->rent_deposit);
        }

        session(['total_tour_price_after_tax' => $total_price,'total_people'=>($adult_tour+$child_tour),'original_price'=>$original_price]);

        return ['total_tour_price_after_tax' => $total_price,'total_people'=>($adult_tour+$child_tour)];
    }



    public function submitForm(Request $request){

        if(!Session::has('cashier')){
//            $uc = new UserController();
//            return $uc->getLogout();
            return redirect()->route('user.logout');
        }


        $this->calculate($request);

        if($request->has('credit_card')){

            $payment_type = $request->credit_card;
            $order_completed = 0;
            //$tour_agent_total_pay = session('total_tour_price_after_tax');
            $tour_agent_total_pay = session('total_tour_price_after_tax');

            if($request->tour_total>session('total_tour_price_after_tax')){
                //price too low, can not complete the transaction
                session(['tour_price_error' => 'price is too high']);
                return redirect()->route('agent.tourOrder');
            }

        }else{

            $payment_type = $request->cash;
            $order_completed =1;
            $tour_agent_total_pay = $request->tour_tips;

            if(floatval($request->tour_tips)>floatval(session('total_tour_price_after_tax')*0.3)+0.01){
                //price too low, can not complete the transaction
                session(['tour_price_error' => 'agent price is too high to complete this order']);
                return redirect()->route('agent.tourOrder');
            }
        }

        $user = DB::table('users')->where('email', Session::get('cashierEmail'))->first();

        if(isset($_POST['tour_coupon'])){
            $payment_type = $_POST['tour_coupon'];
        }


        if($user->level==4){
//            dd($user->level);
            $payment_type = 'paypal';
        }

        $total_people = 0;
        if($request->has('child_tour')){
            $total_people += $request->child_tour;
        }
        if($request->has('adult_tour')){
            $total_people += $request->adult_tour;
        }

//        if($request->tour_type=='public(2h)' || $request->tour_type=='private(2h)'){
//            $endTime = date('Y-m-d H:ia', strtotime('2 hours'));
//        }else if($request->tour_type=='private(3h)'){
//            $endTime = date('Y-m-d H:ia', strtotime('3 hours'));
//        }


        if (strpos($request->tour_type, '(') !== false) {
            $hour = intval(substr($request->tour_type, strpos($request->tour_type, '(')+1));
            preg_match_all('!\d+!', $request->tour_time, $start);


            $endTime = intval($start[0][0])+$hour;
//            dd($endTime);
            if($endTime<12 && $endTime>7){
                $endTime.='AM';
            }else{
                $endTime.='PM';
            }
        }

        if(!isset($request->rent_deposit)){
            $deposit = 'ID';
            $depositPayType = 'ID';
        }else{
            $deposit = $request->rent_deposit;
            $depositPayType = 'ID';
            if($request->deposit_cc_checkbox) {
                $depositPayType = 'Credit Card';
            }elseif ($request->deposit_cash_checkbox){
                $depositPayType = 'Cash';
            }else{
                $depositPayType = 'ID';
            }
        }


        $ac = new AgentController();
        $array = $ac->getLocationTable(Session::get('location'));
        $num = $array[0];
        $table = $array[1];


        $order_id = DB::table('pos_tours_orders')->insertGetId([
            'location'=> session('location'),'customer_name' => $request->tour_customer_first,
            'customer_lastname' => $request->tour_customer_last,
            'customer_email' => $request->tour_email,'cashier_email' => Session::get('cashierEmail'),
            'order_completed' => $order_completed,
            'payment_type' => $payment_type, 'order_id' => "",'original_price'=>session('original_price'),
            'total_price_before_tax' => session('total_tour_price_before_tax'),
            'total_price_after_tax' => session('total_tour_price_after_tax')-($deposit=='ID'? 0:floatval($deposit)),
            'agent_name'=>$request->tour_agent,'agent_level'=>$request->tour_agent_level,
            'basket'=> $request->basket_tour,'insurance'=>$request->insurance=='on'?1:0,
            'created_at'=>date("Y-m-d H:i:s"),'real_time'=>date("Y-m-d H:i:s"),
            'served_date'=>$payment_type==('Cash'||'coupon')?date("Y-m-d H:i:s"):null,
            'tour_type' => $request->tour_type, 'tour_place'=>$request->tour_place,
            'date' => $request->tour_date,'end_time'=>$endTime,
            'time' => $request->tour_time,
            'adult' => $request->adult_tour,
            'child' => $request->child_tour,
            'served'=>$payment_type==('Cash'||'coupon')?1:0,
            'customer_country'=>$request->tour_country,
            'deposit'=>$deposit,'deposit_pay_type'=>$depositPayType,
            'total_people' => $total_people,'comment' => $request->comment,
            'rendered_cash'=>$request->rent_rendered,'customer_address_phone'=>$request->tour_customer_address_phone,
            'seat'=>$request->seat_tour,
            'adjust_price'=>$request->rent_adjust,'sequantial'=>strtoupper($table).$num
        ]);

        if($request->has('cash')){
            //$barcode = $order_id;
            $ac = new AgentController();
            $barcode = $ac->barcodeEncode(intval($order_id),'PT');

            try{
                DB::table('pos_tours_orders')
                    ->where('id', $order_id)
                    ->update([ 'barcode'=>$barcode]);

                //update inventory database
                if(Session::has("inv_cart") && Session::get("inv_cart")["price"]>0){
                    $invController = new InventoryController();
                    $invController->updateDB($request->cc_firstname,$request->cc_lastname,$payment_type,"","","tour");
                }

            }catch(\Exception $exception){
                return redirect()->route('agent.tourOrder')->with('error', $exception->getMessage());
            }

            session(['tour_id' => $order_id]);
//            return redirect()->route('agent.tourOrder');
//            $agent_tours_order = DB::table('agent_tours_orders')->where('id', $order_id)->get();
//            $agent_tours_order = json_decode(json_encode($agent_tours_order), true);
            if(isset($request->rent_deposit) && $request->deposit_cc_checkbox){
                session(['tour_id' => $order_id, 'agent_price_after_tax' => session('total_tour_price_after_tax'), 'tour_deposit'=>'tour_deposit','deposit'=>null,'rent_edit'=>null,'tour_edit'=>null,'tour'=>null,'rent'=>null,"net_price"=>Session::get('total_tour_price_after_tax')]);
                return view('bigbike.agent.cc-checkout',['price'=>$request->rent_deposit,'firstname'=>$request->tour_customer_first,'lastname'=>$request->tour_customer_last]);
            }

            return redirect()->route('agent.tourReceipt');

//            return view('bigbike.agent.tour-receipt',['agent_tours_order'=>$agent_tours_order[0], 'tour_success'=>'Order Completed!']);
        }

        if($request->has('credit_card')){
            if(isset($request->rent_deposit) && $request->deposit_cash_checkbox){
                $tmp = floatval(session('total_tour_price_after_tax'));
                $tmp -= intval($request->rent_deposit);
                session(['total_tour_price_after_tax'=>$tmp]);
            }
        }


        session(['tour_id' => $order_id,'agent_tour_price_after_tax' => session('total_tour_price_after_tax'),'tour'=>'tour','rent'=>null,"net_price"=>session('total_tour_price_after_tax')]);
        return view('bigbike.agent.cc-checkout',['price'=>session('agent_tour_price_after_tax'),'firstname'=>$request->tour_customer_first, 'lastname'=>$request->tour_customer_last]);


    }

    public function postCCCheckout(Request $request){
        if(!Session::has('agent_tour_price_after_tax')){
            return redirect()->route('agent.rentOrder');
        }

        Stripe::setApiKey('sk_test_9P20f4nfmi3L4tAqGZkZgf30');
        // Token is created using Stripe.js or Checkout!
        // Get the payment token submitted by the form:
        $token = $_POST['stripeToken'];

        try{
            $charge = \Stripe\Charge::create(array(
                "amount" =>  Session::get('agent_tour_price_after_tax')*100,
                "currency" => "usd",
                "description" => "Example charge",
                "source" => $token,
            ));

            //update db
            $tour_id = Session::get('tour_id');
//            $barcode = $tour_id;

            $ac = new AgentController();
            $barcode = $ac->barcodeEncode(intval($tour_id),'PT');
            DB::table('pos_tours_orders')
                ->where('id', $tour_id)
                ->update(['order_completed' => 1, 'order_id'=>$charge->id,'customer_name'=>$request->cardholder_name,'completed_at'=> date("Y-m-d H:i:s"),'barcode'=>$barcode]);



        }catch(\Exception $exception){
            return redirect()->route('agent.tourOrder')->with('error', $exception->getMessage());
        }
        //session(['tour_id' => null]);

        Session::forget('cart');

        //return view('bigbike.agent.agent-tour-receipt',['agent_tours_order'=>$agent_tours_order, 'tour_success'=>'Order Completed!', 'barcode'=>$barcode]);
        return redirect()->route('agent.tourReceipt');

    }


    public function postppCheckout(Request $request){

        if(!Session::has('cashier')){
//            $uc = new UserController();
//            return $uc->getLogout();
            return redirect()->route('user.logout');
        }

        if(!Session::has('agent_tour_price_after_tax')){
            return redirect()->route('agent.404');
        }
        $ac = new AgentController();

        $data = $ac->makePPPmt($request);

        if(array_key_exists('message', $data) ) {
//            CREDIT_CARD_CVV_CHECK_FAILED
            if($data->{'message'}=='Credit card was refused.'){
                session(['error'=>'Credit card was refused.']);
            }else if($data->{'message'}=='Credit card CVV check failed.'){
                session(['error'=>'Credit card CVV check failed.']);
            }else{
                session(['error'=>$data->{'message'}]);
            }
            return redirect()->route('agent.tourOrder');
        }

        if($data->{'state'}=='approved') {
            //update db
            $tour_id = Session::get('tour_id');
            $barcode = $ac->barcodeEncode(intval($tour_id),'PT');
            $refund_id = $data->transactions[0]->related_resources[0]->sale->id;

            $user = DB::table('users')->where('email', Session::get('cashierEmail'))->first();
            if($user->level==4){
//            dd($user->level);
                $served = 1;
                $payment_type = 'paypal';
                $agent_tours_order = DB::table('pos_tours_orders')->where('id', $tour_id)->first();
//                $this->sendOrderEmail($agent_tours_order->customer_email, $agent_tours_order);
//                $this->sendAgentEmail(Auth::user()->email,$agent_tours_order);

            }else{
                $served = 1;
                $payment_type = 'Credit Card';
            }

            try{
                DB::table('pos_tours_orders')
                    ->where('id', $tour_id)
                    ->update(['order_completed' => 1,'payment_type'=>$payment_type,'customer_cc_name' => $request->cc_firstname,'customer_cc_lastname' => $request->cc_lastname,
                        'order_id' => $data->id, 'completed_at' => date("Y-m-d H:i:s"), 'barcode' => $barcode,'served'=>$served,'served_date'=>date("Y-m-d H:i:s"),
                        'refund_id'=>$refund_id]);


                //update inventory database
                if(Session::has("inv_cart") && Session::get("inv_cart")["price"]>0){
                    $invController = new InventoryController();
                    $invController->updateDB($request->cc_firstname,$request->cc_lastname,$payment_type,$data->id,$refund_id,"tour");
                }


            }catch(\Exception $exception){
                return redirect()->route('agent.tourOrder')->with('error', $exception->getMessage());
            }
        }else{
            return redirect()->route('agent.tourOrder')->with('error', "credit card was declined");
        }

        Session::forget('agent_tour_price_after_tax');
        //return view('bigbike.agent.agent-rent-receipt',['agent_rents_order'=>$agent_rents_order[0],'rent_success'=>'Order Completed!','barcode'=>Session::pull('order_id')]);
        return redirect()->route('agent.tourReceipt');
    }


    public function printReceipt(){

        if(Session::has('tour_id')) {
            $agent_tours_order = DB::table('pos_tours_orders')->where('id', Session::pull('tour_id'))->first();

            $dns = new DNS1D();
            $data = "data:image/png;base64,".$dns->getBarcodePNG($agent_tours_order->barcode, "C39");


            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);
            $data = base64_decode($data);
            file_put_contents(public_path().'/images/barcode/tour/'.$agent_tours_order->barcode.'.png', $data);

            $caisher_name = DB::table('users')->where('email', $agent_tours_order->cashier_email)->first();
            $location = DB::table('locations')->where('title', Session::get('location'))->first();

            $agent_tours_order = json_decode(json_encode($agent_tours_order), true);
            session(['tour_success'=>'Order completed!']);

            return view('bigbike.agent.tour-receipt', ['agent_tours_order' => $agent_tours_order,'caisher_name'=>$caisher_name->first_name.' '.$caisher_name->last_name,'user'=>$caisher_name,'location'=>$location]);
        }else{
            return redirect()->route('agent.main');
        }
    }

    public function sendOrderEmail($email, $agent_tours_order){

        $data = array('name' => 'Bigbike', 'msg' => 'Order Confirmation','payment_type'=>$agent_tours_order->payment_type,
            'cashier_email'=>$email,'customer_email'=>$agent_tours_order->customer_email,'completed_at'=>$agent_tours_order->completed_at,
            'total_price_after_tax'=>$agent_tours_order->total_price_after_tax,'agent_price_after_tax'=>$agent_tours_order->agent_price_after_tax,
            'barcode'=>$agent_tours_order->barcode,'customer_name'=>$agent_tours_order->customer_name,
            'customer_email'=>$agent_tours_order->customer_email,'date'=>$agent_tours_order->date,
            'time'=>$agent_tours_order->time,'tour_type'=>$agent_tours_order->tour_type,'agent_email'=>$agent_tours_order->cashier_email,
            'adult'=>$agent_tours_order->adult,'child'=>$agent_tours_order->child,'total_people'=>$agent_tours_order->total_people
        );

//        Mail::send('emails.order-customer-email', $data, function ($message) use($email,$pdf) {
        //if($type=='agent')
        Mail::send('emails.tour-order-email', $data, function ($message) use($email) {
            $message->from('vouchers@bikerent.nyc', 'Order Confirmation');
//            $pdf = PDF::loadView('emails.signup-welcome', 'A4', 'portrait');
//           $message->to('support@my3dcrestwhite.ru')->subject('Thank you for registration');

            $message->to($email)->subject('Order Confirmation');


//            $message->attach($pdf->output(),['filename.pdf']);
            //$message->attachData($data, ['invoice.pdf']);

        });
    }

    public function sendCustomerEmail($email){
        $data = array('name' => 'Bigbike', 'msg' => 'Order Confirmation');
        Mail::send('emails.order-customer-email', $data, function ($message) use($email) {

            $message->from('alexrussian1001@gmail.com', 'Order Confirmation');

//           $message->to('support@my3dcrestwhite.ru')->subject('Thank you for registration');
            $message->to($email)->subject('Order Confirmation');

        });
    }

    public function sendAgentEmail($email,$agent_tours_order){
        $data = array('name' => 'Bigbike', 'msg' => 'Order Confirmation','payment_type'=>$agent_tours_order->payment_type,
            'agent_email'=>$email,'customer_email'=>$agent_tours_order->customer_email,'completed_at'=>$agent_tours_order->completed_at,
            'total_price_after_tax'=>$agent_tours_order->total_price_after_tax,'agent_price_after_tax'=>$agent_tours_order->agent_price_after_tax,
            'barcode'=>$agent_tours_order->barcode,'customer_name'=>$agent_tours_order->customer_name.' '.$agent_tours_order->customer_lastname,
            'customer_email'=>$agent_tours_order->customer_email,'date'=>$agent_tours_order->date,"total_people"=>$agent_tours_order->total_people,
            'time'=>$agent_tours_order->time,'tour_type'=>$agent_tours_order->tour_type,'tour_place'=>$agent_tours_order->tour_place,'adult'=>$agent_tours_order->adult,
            'child'=>$agent_tours_order->child, 'seat'=>$agent_tours_order->seat, 'basket'=>$agent_tours_order->basket, 'insurance'=>$agent_tours_order->insurance);
        Mail::send('emails.order-agent-email-tour', $data, function ($message) use($email) {

            $message->from('marketing@bikerent.nyc', 'Order Confirmation');

//           $message->to('support@my3dcrestwhite.ru')->subject('Thank you for registration');
            $message->to($email)->subject('Order Confirmation!');

        });
    }

    public function printTicket(){
        if(Session::has('tour_id')) {

            $agent_tours_order = DB::table('pos_tours_orders')->where('id', Session::get('tour_id'))->get();
            $agent_tours_order = json_decode(json_encode($agent_tours_order), true);
            session(['tour_success'=>'Order completed!']);
            return view('bigbike.agent.tour-ticket', ['agent_tours_order' => $agent_tours_order[0], 'tour_success' => 'Order Completed!']);
        }else{
            return redirect()->route('agent.main');
        }
    }

    public function reserveShowEditPage($id){
//        dd($id);
        $agent_tour_table = DB::table('agent_tours')->get();
        $agents = DB::table('agents')->get();
        $user = DB::table('users')->where('email', Session::get('cashierEmail'))->first();

        $agent_tours_order = DB::table('pos_tours_orders')->where('id', $id)->first();
        session(['previous_total_before_tax' => $agent_tours_order->total_price_before_tax, 'previous_total_price_after_tax' => $agent_tours_order->total_price_after_tax]);

        $agent_tours_order = json_decode(json_encode($agent_tours_order), true);

        return view('bigbike.agent.tour-main', ['agent_tour_table' => $agent_tour_table, 'agents' => $agents, 'agent_tours_order' => $agent_tours_order, 'isEdit' => 'true','user'=>$user,'agent_tours_order_cc'=>null,'reservation'=>'reservation']);

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

        if($request->tour_type=='public(2h)' || $request->tour_type=='private(2h)'){
            $endTime = date('Y-m-d H:i:s', strtotime('2 hours'));
        }else if($request->tour_type=='private(3h)'){
            $endTime = date('Y-m-d H:i:s', strtotime('3 hours'));
        }

        if(isset($request->deposit_id_checkbox)){
            $deposit = 'ID';
            $deposit_payment_type = 'ID';
        }else{
            $deposit = $request->rent_deposit;
            if(isset($request->deposit_cash_checkbox)){
                $deposit_payment_type = 'Cash';
                session(['total_tour_price_after_tax'=>(floatval(session('total_tour_price_after_tax'))-floatval($deposit))]);
            }elseif(isset($request->deposit_cc_checkbox)){
                $deposit_payment_type = 'Credit Card';
            }
        }

        if(isset($request->reservation_bike) && $request->reservation_bike=='reservation'){
            $columnName = 'cashier_email';
            $deposit_columnName = 'deposit';
            $deposit_type_columnName = 'deposit_pay_type';
            $columnName2 = 'extra_cashier_email';

        }else{
            $columnName = 'extra_cashier_email';
            $deposit_columnName = 'extra_deposit';
            $deposit_type_columnName = 'extra_deposit_pay_type';
            $columnName2 = 'extra_cashier_email';

        }


        try {
            DB::table('pos_tours_orders')->where('id', $request->tour_id)
                ->update(['extra_order_completed' => $order_completed, 'extra_customer_name' => $request->tour_customer_first, 'extra_customer_lastname' => $request->tour_customer_last, 'served' => 1,'served_date'=>date("Y-m-d H:i:s"), 'extra_served_date' => date("Y-m-d H:i:s"),
                    'agent_name' => $request->tour_agent, 'agent_level' => $request->tour_agent_level, $columnName => Session::get('cashierEmail'),$columnName2=>Session::get('cashierEmail'), 'extra_service_payment_type' => $payment_type,'tour_type'=>$request->tour_type,$deposit_columnName=>$deposit,$deposit_type_columnName=>$deposit_payment_type,
                    'extra_service_total_before_tax' => $request->rent_total_label, 'extra_service_total_after_tax' => $request->rent_total_after_tax,
                    'extra_service_rendered_cash' => $request->rent_rendered, 'created_at' => date("Y-m-d H:i:s"), 'date' => $request->tour_date, 'time' => $request->tour_time, 'end_time' => $endTime, 'adult' => $request->adult_tour,'child' => $request->child_tour,'basket' => $request->basket_tour,
                    'total_people' => session('total_people'), 'comment' => $request->comment
                ]);

            if($request->has('cash')) {

                if(isset($request->deposit_cc_checkbox)){
                    session(['tour_id' => $request->tour_id, 'agent_price_after_tax' => floatval(session('total_tour_price_after_tax')) - floatval(session('previous_total_price_after_tax')), 'tour_edit'=>'tour_edit','rent_edit'=>null,'rent'=>null,'tour'=>null,"net_price"=>$deposit]);
                    return view('bigbike.agent.cc-checkout',['price'=>$deposit,'firstname'=>$request->tour_customer_first,'lastname'=>$request->tour_customer_last]);
                }


                session(['tour_id' => $request->tour_id]);
                return redirect()->route('agent.tourReceipt');
            }

        } catch (\Exception $exception) {
            return redirect()->route('agent.tourOrder')->with('tour_price_error', $exception->getMessage());
        }



        session(['tour_id' => $request->tour_id, 'agent_price_after_tax' => floatval(session('total_tour_price_after_tax')) - floatval(session('previous_total_price_after_tax')), 'tour_edit'=>'tour_edit','rent_edit'=>null,'rent'=>null,'tour'=>null,"net_price"=>$request->rent_total_after_tax_deposit-($deposit_payment_type=='Cash'?$deposit:0)]);
        return view('bigbike.agent.cc-checkout',['price'=>$request->rent_total_after_tax_deposit-($deposit_payment_type=='Cash'?$deposit:0),'firstname'=>$request->tour_customer_first,'lastname'=>$request->tour_customer_last]);

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
            return redirect()->route('agent.tourOrder');
        }

        if($data->{'state'}=='approved') {
            //update db
            $tour_id = Session::get('tour_id');

            try{
                DB::table('pos_tours_orders')
                    ->where('id', $tour_id)
                    ->update(['extra_order_completed' => 1,'extra_customer_name' => $request->cc_firstname,'extra_customer_lastname' => $request->cc_lastname,'extra_order_id' => $data->id, 'extra_completed_at' => date("Y-m-d H:i:s"),'served'=>1,'served_date'=>date("Y-m-d H:i:s")]);

            }catch(\Exception $exception){
                return redirect()->route('agent.tourOrder')->with('tour_price_error', $exception->getMessage());
            }
        }else{
            return redirect()->route('agent.tourOrder')->with('tour_price_error', "credit card is declined");
        }
        Session::forget('agent_price_after_tax');
        //return view('bigbike.agent.agent-rent-receipt',['agent_rents_order'=>$agent_rents_order[0],'rent_success'=>'Order Completed!','barcode'=>Session::pull('order_id')]);
        return redirect()->route('agent.tourReceipt');
    }

    public function showReturnDetail($id){
        $agent_tours_order = DB::table('pos_tours_orders')->where('id', $id)->first();
//        if($agent_tours_order->duration=='All Day (8am-9pm)'){
//            $duration = 'all day';
//        }else{
//            $duration = $agent_tours_order->duration;
//        }
        $agent_tour_table= DB::table('agent_tours')->where('title', $agent_tours_order->tour_type)->get();

        $agent_tours_order = json_decode(json_encode($agent_tours_order), true);
        $agent_tour_table = json_decode(json_encode($agent_tour_table), true);
        $user = DB::table('users')->where('email', $agent_tours_order['cashier_email'])->first();
        $user = json_decode(json_encode($user), true);

        return view('bigbike.agent.return.tour-detail', ['agent_tours_order' => $agent_tours_order,'agent_tour_table'=>$agent_tour_table,'user'=>$user]);
    }

    public function showEditPage(Request $request){
//        dd($request->edit_id);
        if($request->has('edit')) {

            $agent_tour_table = DB::table('agent_tours')->get();
            $agents = DB::table('agents')->get();
            $user = DB::table('users')->where('email', Session::get('cashierEmail'))->first();


            $agent_tours_order = DB::table('pos_tours_orders')->where('id', $request->edit_id)->first();
            session(['previous_total_before_tax' => $agent_tours_order->total_price_before_tax, 'previous_total_price_after_tax' => $agent_tours_order->total_price_after_tax]);

            $agent_tours_order = json_decode(json_encode($agent_tours_order), true);
            $agent_tours_order_cc = null;

            return view('bigbike.agent.tour-main', ['agent_tour_table' => $agent_tour_table, 'agents' => $agents, 'agent_tours_order' => $agent_tours_order, 'isEdit' => 'true','user'=>$user,'agent_tours_order_cc'=>$agent_tours_order_cc]);
        }else if($request->has('delete')){
            //set returned 1
            DB::table('pos_tours_orders')
                ->where('id', $request->edit_id)
                ->update(['returned'=>1,'returned_date'=>date("Y-m-d H:i:s"),'returned_cashier'=>Session::get('cashierEmail'),'cashier_email'=>Session::get('cashierEmail')]);

            session(['error'=>'Delete Success']);

            return redirect()->route('agent.rentOrder');

        }else if($request->has('release_pp')){
            return $this->refundPP($request);
        }
    }

    public function deleteTour(Request $request){
        $tour_ids = explode(",",$request->ids);

//        dd($ids);

        for($i=0; $i<count($tour_ids); $i++) {
            $id = $tour_ids[$i];
            if($id > 0) {
                try{
                    DB::table('pos_tours_orders')
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

    public function printReceiptFromReturn($id){

        $agent_tours_order = DB::table('pos_tours_orders')->where('id', $id)->first();
        $dns = new DNS1D();
        $data = "data:image/png;base64,".$dns->getBarcodePNG($agent_tours_order->barcode, "C39");


        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);

        file_put_contents(public_path().'/images/barcode/tour/'.$agent_tours_order->barcode.'.png', $data);


        $caisher_name = DB::table('users')->where('email', $agent_tours_order->cashier_email)->first();
        $location = DB::table('locations')->where('title', Session::get('location'))->first();


        $agent_tours_order = json_decode(json_encode($agent_tours_order), true);
//            $this->sendAgentEmail(Auth::user()->email);

        session(['tour_success'=>'Order completed!']);
        return view('bigbike.agent.tour-receipt', ['agent_tours_order' => $agent_tours_order, 'rent_success' => 'Order Completed!','caisher_name'=>$caisher_name->first_name.' '.$caisher_name->last_name,'user'=>$caisher_name,'location'=>$location]);

    }

    public function tourDepositCheckout(Request $request){
        if(!Session::has('tour_id')){
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

            return redirect()->route('agent.tourOrder');
        }

        if($data->{'state'}=='approved') {
            //update db
            $tour_id = Session::get('tour_id');
            $refund_id = $data->transactions[0]->related_resources[0]->sale->id;

            try{
                DB::table('pos_tours_orders')
                    ->where('id', $tour_id)
                    ->update(['order_completed' => 1,'customer_name' => $request->cc_firstname,'customer_lastname' => $request->cc_lastname,'order_id' => $data->id,
                        'refund_id'=>$refund_id,'completed_at' => date("Y-m-d H:i:s"), 'served'=>1]);

            }catch(\Exception $exception){
                return redirect()->route('agent.tourOrder')->with('tour_price_error', $exception->getMessage());
            }
        }else{
            return redirect()->route('agent.tourOrder')->with('tour_price_error', "credit card is declined");
        }
        //return view('bigbike.agent.agent-rent-receipt',['agent_rents_order'=>$agent_rents_order[0],'rent_success'=>'Order Completed!','barcode'=>Session::pull('order_id')]);
        return redirect()->route('agent.tourReceipt');
    }

    public function refundPP(Request $request){

        try{

            $cancel_order= DB::table('pos_tours_orders')->where('id', $request->edit_id)->first();

        }catch(\Exception $exception){
            return redirect()->route('agent.tourOrder')->with('tour_price_error', "Something is wrong, try again");

        }

        if(!$cancel_order->refund_id){
            return redirect()->route('agent.tourOrder')->with('tour_price_error', "No such Paypal Transaction");
        }

        $ac = new AgentController();
        $data = $ac->refundPP($cancel_order->refund_id, floatval($request->refund_amt));

//        dd($data);

        if(isset($data->{'name'}) && $data->{'name'}=="TRANSACTION_REFUSED"){
            return redirect()->route('agent.tourOrder')->with('tour_price_error', $data->{'message'});
        }


        if($data->{'state'}=='completed') {

            try{
                DB::table('pos_tours_orders')
                    ->where('id', $request->edit_id)
                    ->update(['refund_amt'=>$request->refund_amt,'refund_transaction_id' => $data->{'id'}]);

            }catch(\Exception $exception){
                return redirect()->route('agent.tourOrder')->with('tour_price_error', $exception->getMessage());
            }
        }else{
//            dd($data);
            return redirect()->route('agent.tourOrder')->with('tour_price_error', "not success");

        }

        session(['tour_id'=>$request->edit_id]);
        return redirect()->route('agent.tourDepoistRefundReceipt');

    }

    public function depoistRefundReceipt(){

        if(Session::has('tour_id')) {
            $agent_tours_order = DB::table('pos_tours_orders')->where('id', Session::get('tour_id'))->first();

            $agent_tours_order = json_decode(json_encode($agent_tours_order), true);
            session(['tour_success'=>'Order completed!']);
            $caisher_name = DB::table('users')->where('email', Session::get('cashierEmail'))->first();
            $location = DB::table('locations')->where('title', Session::get('location'))->first();

            return view('bigbike.agent.tour-receipt-refund', ['agent_tours_order' => $agent_tours_order, 'rent_success' => 'Order Completed!','caisher_name'=>$caisher_name->first_name.' '.$caisher_name->last_name,'location'=>$location]);
        }else{
            return redirect()->route('agent.main');
        }
    }


}
