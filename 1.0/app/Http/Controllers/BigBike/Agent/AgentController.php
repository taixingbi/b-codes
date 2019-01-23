<?php

namespace App\Http\Controllers\BigBike\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\Charge;
use App\User;
use Auth;
use Illuminate\Support\Facades\DB;
use Mail;
use Carbon\Carbon;
use PHPMailer;
use Log;

class AgentController extends Controller
{

//"a","b","c","d","e","f","g","h",
//"i","j","k","l","m","n","o","p",
//"q","r","s","t","u","v","w","x",
//"y","z",

    private $barcodeMap = array(
        "A","B","C","D","E","F","G","H",
        "I","J","K","L","M","N","O","P",
        "Q","R","S","T","U","V","W","X",
        "Y","Z","0","1","2","3","4","5",
        "6","7","8","9","-","."," ","$",
        "+","%");

    private $barcodeDeMap = array(
        "A"=>0,"B"=>1,"C"=>2,"D"=>3,"E"=>4,"F"=>5,"G"=>6,"H"=>7,
        "I"=>8,"J"=>9,"K"=>10,"L"=>11,"M"=>12,"N"=>13,"O"=>14,"P"=>15,
        "Q"=>16,"R"=>17,"S"=>18,"T"=>19,"U"=>20,"V"=>21,"W"=>22,"X"=>23,
        "Y"=>24,"Z"=>25,"0"=>26,"1"=>27,"2"=>28,"3"=>29,"4"=>30,"5"=>31,
        "6"=>32,"7"=>33,"8"=>34,"9"=>35,"-"=>36,"."=>37," "=>38,"$"=>39,
        "+"=>40,"%"=>41);


    public function loginAgent(){

//        $agent_rent_table= DB::table('agent_rents')->get();
//        $agent_tour_table= DB::table('agent_tours')->get();
//        return view('bigbike.agent.main',['agent_rent_table'=>$agent_rent_table,'agent_tour_table'=>$agent_tour_table]);
        return view('bigbike.agent.main');

    }

    public function go404(){

//        $agent_rent_table= DB::table('agent_rents')->get();
//        $agent_tour_table= DB::table('agent_tours')->get();
//        return view('bigbike.agent.main',['agent_rent_table'=>$agent_rent_table,'agent_tour_table'=>$agent_tour_table]);
        return view('errors.404');

    }

    public function getReportForm(){
        return view('bigbike.agent.cashier.report');

    }

    public function showReport(Request $request){

//        $start_date = explode('/', $request->start_date);
        $end_date = explode('/', $request->end_date);

        $agent_rents = DB::table('pos_rents_orders')
//            ->where('location', Session::get('location'))
//            ->where('cashier_email', Auth::user()->email)
            ->where('order_completed', '1')
            ->whereYear('created_at', '=', $end_date[2])
            ->whereDay('created_at', '=', $end_date[1])
            ->whereMonth('created_at', '=', $end_date[0])
            ->get();
        
        $agent_tours = DB::table('pos_tours_orders')
//            ->where('location', Session::get('location'))
//            ->where('cashier_email', Auth::user()->email)
            ->where('order_completed', '1')
            ->whereYear('created_at', '=', $end_date[2])
            ->whereDay('created_at', '=', $end_date[1])
            ->whereMonth('created_at', '=', $end_date[0])
            ->get();

        $sport_sales = DB::table('inventory_sales')
//            ->where('location', Session::get('location'))
//            ->where('cashier_email', Auth::user()->email)
            ->where('order_completed', '1')
            ->whereYear('created_at', '=', $end_date[2])
            ->whereDay('created_at', '=', $end_date[1])
            ->whereMonth('created_at', '=', $end_date[0])
            ->get();


        $sum = 0;

        $cash_num = 0;
        $cc_num = 0;
        $paypal_num = 0;
        $coupon_num = 0;
        $sport_num = 0;

        $cash_sum = 0;
        $cc_sum = 0;
        $paypal_sum = 0;
        $coupon_sum = 0;
        $sport_sum = 0;

        $ins_num = 0;
        $ins_sum = 0;
        $basket_num = 0;
        $basket_sum = 0;
        $dropoff_num = 0;
        $dropoff_sum = 0;
        $latefee_num = 0;
        $latefee_sum = 0;
        $deposit_num = 0;
        $deposit_sum = 0;
        $cash = array();
        $credit = array();


        foreach ($agent_rents as $agent_rent){
            if($agent_rent->cashier_email==Auth::user()->email) {
                //test
                if(!empty($agent_rent->agent_email)){
                    //skip
                }elseif ($agent_rent->payment_type == 'Cash') {
                    $cash_num++;
                    $cash_sum += $agent_rent->total_price_after_tax;
                    if(!array_key_exists($agent_rent->location,$cash)) {
                        $cash[$agent_rent->location] =0;
                    }
                    $cash[$agent_rent->location] += $agent_rent->total_price_after_tax;

                } elseif ($agent_rent->payment_type == 'Credit Card') {
                    $cc_num++;
                    $cc_sum += $agent_rent->total_price_after_tax;
                    if(!array_key_exists($agent_rent->location,$credit)) {
                        $credit[$agent_rent->location] =0;
                    }
                    $credit[$agent_rent->location] += $agent_rent->total_price_after_tax;

                } elseif ($agent_rent->payment_type == 'paypal') {
                    $paypal_num++;
                    $paypal_sum += $agent_rent->total_price_after_tax;
                } elseif ($agent_rent->payment_type == 'coupon') {
                    $coupon_num++;
                    $coupon_sum += $agent_rent->total_price_after_tax;
                }

                $sum += $agent_rent->total_price_after_tax;

                $doubleBike = $agent_rent->tandem+$agent_rent->road+$agent_rent->mountain;
                $ins_num += $agent_rent->insurance * $agent_rent->total_bikes;
                $ins_sum += $agent_rent->insurance * 2 * ($agent_rent->total_bikes+$doubleBike);
                $basket_num += $agent_rent->basket;
                $basket_sum += $agent_rent->basket;
                $dropoff_num += $agent_rent->dropoff * $agent_rent->total_bikes;
                $dropoff_sum += $agent_rent->dropoff * 5 * $agent_rent->total_bikes;

//                if (!empty($agent_rent->late_fee)) {
//                    $latefee_num++;
//                    $latefee_sum += $agent_rent->late_fee;
//
//                }

                if (!empty($agent_rent->deposit) && $agent_rent->deposit != 'ID') {
                    $deposit_num++;
                    $deposit_sum += floatval($agent_rent->deposit);
                }

            }

            //extra
            if(!empty($agent_rent->extra_service_payment_type) && $agent_rent->extra_cashier_email==Auth::user()->email){
//                if($agent_rent->payment_type == 'Cash' && !empty($agent_rent->agent_email)){
//                    if($agent_rent->extra_service_payment_type=='Cash'){
//                        $cash_num++;
//                        $cash_sum += $agent_rent->extra_service_total_after_tax;
//
//                    }elseif($agent_rent->extra_service_payment_type=='Credit Card'){
//                        $cc_num++;
//                        $cc_sum += $agent_rent->extra_service_total_after_tax;
//
//                    }
//                }

                if($agent_rent->extra_service_payment_type=='Cash'){
                    $cash_num++;
                    $cash_sum += $agent_rent->extra_service_total_after_tax;

                    if(!array_key_exists($agent_rent->location,$cash)) {
                        $cash[$agent_rent->location] =0;
                    }
                    $cash[$agent_rent->location] += $agent_rent->extra_service_total_after_tax;

                }elseif ($agent_rent->extra_service_payment_type=='Credit Card'){
                    $cc_num++;
                    $cc_sum += $agent_rent->extra_service_total_after_tax;

                    if(!array_key_exists($agent_rent->location,$credit)) {
                        $credit[$agent_rent->location] =0;
                    }
                    $credit[$agent_rent->location] += $agent_rent->extra_service_total_after_tax;

                } elseif ($agent_rent->extra_service_payment_type=='coupon'){
                    $coupon_num++;
                    $coupon_sum += $agent_rent->extra_service_total_after_tax;
                }
                $sum += $agent_rent->extra_service_total_after_tax;
            }

            //late fee
            if(!empty($agent_rent->returned_payment_type) && $agent_rent->returned_cashier==Auth::user()->email){
//                dd($agent_rent->returned_payment_type);

                if($agent_rent->returned_payment_type=='Cash'){
                    $cash_num++;
                    $cash_sum += $agent_rent->returned_total-$agent_rent->returned_change;

                    if(!array_key_exists($agent_rent->location,$cash)) {
                        $cash[$agent_rent->location] =0;
                    }
                    $cash[$agent_rent->location] += $agent_rent->returned_total-$agent_rent->returned_change;

                }elseif ($agent_rent->returned_payment_type=='Credit Card'){
                    $cc_num++;
                    $cc_sum += $agent_rent->returned_total-$agent_rent->returned_change;

                    if(!array_key_exists($agent_rent->location,$credit)) {
                        $credit[$agent_rent->location] =0;
                    }
                    $credit[$agent_rent->location] += $agent_rent->returned_total-$agent_rent->returned_change;

                }
                $sum += $agent_rent->returned_total-$agent_rent->returned_change;
                $latefee_num++;
                $latefee_sum += $agent_rent->returned_total-$agent_rent->returned_change;
            }
        }


        foreach ($agent_tours as $agent_tour){
            if($agent_tour->cashier_email==Auth::user()->email) {
                if(!empty($agent_tour->tix_agent)){
                    //skip
                }else if ($agent_tour->payment_type == 'Cash') {
                    $cash_num++;
                    $cash_sum += $agent_tour->total_price_after_tax;

                    if(!array_key_exists($agent_tour->location,$cash)) {
                        $cash[$agent_tour->location] =0;
                    }
                    $cash[$agent_tour->location] += $agent_tour->total_price_after_tax;

                } elseif ($agent_tour->payment_type == 'Credit Card') {
                    $cc_num++;
                    $cc_sum += $agent_tour->total_price_after_tax;

                    if(!array_key_exists($agent_tour->location,$credit)) {
                        $credit[$agent_tour->location] =0;
                    }
                    $credit[$agent_tour->location] += $agent_tour->total_price_after_tax;

                } elseif ($agent_tour->payment_type == 'paypal') {
                    $paypal_num++;
                    $paypal_sum += $agent_tour->total_price_after_tax;
                } elseif ($agent_tour->payment_type == 'coupon') {
                    $coupon_num++;
                    $coupon_sum += $agent_tour->total_price_after_tax;
                }
                $sum += $agent_tour->total_price_after_tax;
            }
            //extra
            if(!empty($agent_tour->extra_service_payment_type) && $agent_tour->extra_cashier_email==Auth::user()->email ){
                if($agent_tour->extra_service_payment_type=='Cash'){
                    $cash_num++;
                    $cash_sum += $agent_tour->extra_service_total_after_tax;

                    if(!array_key_exists($agent_tour->location,$cash)) {
                        $cash[$agent_tour->location] =0;
                    }
                    $cash[$agent_tour->location] += $agent_tour->extra_service_total_after_tax;

                }elseif ($agent_tour->extra_service_payment_type=='Credit Card'){
                    $cc_num++;
                    $cc_sum += $agent_tour->extra_service_total_after_tax;

                    if(!array_key_exists($agent_tour->location,$credit)) {
                        $credit[$agent_tour->location] =0;
                    }
                    $credit[$agent_tour->location] += $agent_tour->extra_service_total_after_tax;

                }
                $sum += $agent_tour->extra_service_total_after_tax;
            }
        }

        foreach ($sport_sales as $sport_sale){
            if($sport_sale->cashier_email==Auth::user()->email) {
                if ($sport_sale->payment_type == 'Cash') {
                    $cash_num++;
                    $cash_sum += $sport_sale->total_price_after_tax;

                    $sport_num++;
                    $sport_sum += $sport_sale->total_price_after_tax;

                    if(!array_key_exists($sport_sale->location,$cash)) {
                        $cash[$sport_sale->location] =0;
                    }
                    $cash[$sport_sale->location] += $sport_sale->total_price_after_tax;

                } elseif ($sport_sale->payment_type == 'Credit Card') {
                    $cc_num++;
                    $cc_sum += $sport_sale->total_price_after_tax;

                    $sport_num++;
                    $sport_sum += $sport_sale->total_price_after_tax;

                    if(!array_key_exists($sport_sale->location,$credit)) {
                        $credit[$sport_sale->location] =0;
                    }
                    $credit[$sport_sale->location] += $sport_sale->total_price_after_tax;
                }
                $sum += $sport_sale->total_price_after_tax;

            }
        }




        $user = DB::table('users')->where('email', Auth::user()->email)->first();
//        dd($credit);
//        return view('bigbike.agent.cashier.show-report',['agent_cc_rents'=>$agent_cc_rents,'agent_cc_tours'=>$agent_cc_tours,
//            'agent_cash_rents'=>$agent_cash_rents,'agent_cash_tours'=>$agent_cash_tours,'sum'=>$sum,'cash_sum'=>$cash_sum,
//            'start_date'=>$request->start_date,'end_date'=>$request->end_date]);
        return view('bigbike.agent.cashier.show-report',['cash'=>$cash,'credit'=>$credit,'deposit_sum'=>$deposit_sum,'deposit_num'=>$deposit_num,'latefee_sum'=>$latefee_sum,
            'latefee_num'=>$latefee_num,'dropoff_sum'=>$dropoff_sum,'dropoff_num'=>$dropoff_num,'basket_sum'=>$basket_sum,'basket_num'=>$basket_num,
            'ins_num'=>$ins_num,'ins_sum'=>$ins_sum,'cash_num'=>$cash_num,'cc_num'=>$cc_num,'paypal_num'=>$paypal_num,
            'coupon_num'=>$coupon_num,'cash_sum'=>$cash_sum,'cc_sum'=>$cc_sum,'paypal_sum'=>$paypal_sum,'coupon_sum'=>$coupon_sum,
            'end_date'=>$request->end_date,'sum'=>$sum,'cashier_name' => $user->first_name." ".$user->last_name,'sport_num'=>$sport_num,'sport_sum'=>$sport_sum]);

    }

    public function barcodeEncode($id,$type){

        $encoded = "";
        $base = sizeof($this->barcodeMap);
        while($id>0){
            $encoded .= $this->barcodeMap[$id%$base];
            $id = intval($id/$base);
        }
        return $type.$encoded;

    }

    public function barcodeDecode($str){
        $id = 0;
        $tmpArr = str_split($str);
        $base = 1;
        $realBase = sizeof($this->barcodeMap);
        foreach ($tmpArr as $c){
            $id += intval($this->barcodeDeMap[$c])*$base;
            $base *= $realBase;
        }
        return $id;
        //return $base;
    }

    public function getResetPage(){
        return view('bigbike.agent.get-email');
    }

    public function getEmail(Request $request){
        try{
            $user = DB::table('users')->where('email', $request->email)->first();

            $this->sendEmail($user);

            session(['msg'=>"Check your email to reset password"]);
            return redirect()->route('user.signin');
        }catch(\Exception $exception){
            return redirect()->route('agent.getResetPage')->with('errors', $exception->getMessage());
        }
    }

    public function sendEmail($user){
        $data = array('name' => 'Bigbike', 'msg' => 'Reset Password', 'remember_token' => $user->remember_token);
        Mail::send('emails.reset-pwd-welcome', $data, function ($message) use($user) {

            $message->from('vouchers@bikerent.nyc', 'Reset Password');

//           $message->to('support@my3dcrestwhite.ru')->subject('Thank you for registration');
            $message->to($user->email)->subject('Reset Password');

        });
    }

    public function sendEmailAfterResetPwd($user){
        $data = array('name' => 'Bigbike', 'msg' => 'Reset Password Succeed', 'remember_token' => $user->remember_token);
        Mail::send('emails.reset-pwd-succeed', $data, function ($message) use($user) {

            $message->from('vouchers@bikerent.nyc', 'Reset Password Succeed');

//           $message->to('support@my3dcrestwhite.ru')->subject('Thank you for registration');
            $message->to($user->email)->subject('Reset Password Succeed');

        });
    }

    public function showResetPasswordPage(Request $request){
//        try{
//
//        }catch(\Exception $exception){
//            return redirect()->route('agent.getResetPage')->with('errors', "Please input your email again");
//        }

        return view('bigbike.agent.reset-password',['remember_token'=>$request->remember_token]);
    }

    public function resetPassword(Request $request){

        if ($request->password == $request->password2) {
            try {
                $user = DB::table('users')->where('email', $request->email)->first();
                if($user->remember_token!=$request->remember_token){
                    return redirect()->route('agent.getResetPage')->with('errors', "You already reset your password");
                }else {

                    DB::table('users')
                        ->where('remember_token', $request->remember_token)
                        ->where('email', $request->email)
                        ->update(['password' => bcrypt($request->password)]);
                }
            }catch(\Illuminate\Database\QueryException $exception){
                return redirect()->route('agent.getResetPage')->with('errors', $exception->getMessage());
            } catch(\Exception $exception){
                return redirect()->route('agent.getResetPage')->with('errors', $exception->getMessage());
            }
        }else{
            return redirect()->route('agent.getResetPage')->with('errors', "Please input the same password");
        }
//        $user = DB::table('users')->where('email', $request->email)->first();
        $this->sendEmailAfterResetPwd($user);
        session(['msg'=>"Your password has been updated"]);

        return redirect()->route('user.signin');

    }

    public function contact(){
        return view('bigbike.agent.contact');
    }

    public function getPPToken(){

        //switch paypal account
//        dd(Session::get('location'));
//        if(true){
//
//
//        }
        //sandbox
//        $client_id = 'AbCicdLgOuRLRziFh6aPefmKKB1Q0qTzZOt4Yjj6dDDg2TiIyWb1YP7-CR-L02wvqQJg9EJ8Cnuw2jDE';
//        $secret = 'EPN2vsHZ9g-pPy4BaQiTcCJKtPSkX5IPd2iuTRqnCPPiKYUeScXWixQ8JEEEJhEMUTTlI7QgbezOiZQU';

        if(Session::has('location') && Session::get('location')=='203W 58th Street'){
            $client_id = 'AZ2aVD0CQx_FGGPgShZTVQDw4aBCal5Pghva6WMZpYZ5J0Kd5lODfYsWK5MtfMzInhfmFrklusDcymoo';
            $secret = 'EGvqUNiRlZ42JEP-aY9fNlC9VN5-qlJSvZXUAkinLtYXyTSNa2ivqBSN2f9WwcjpDsgws4jvL_LZj5Ft';

        }elseif(Session::has('location') && (Session::get('location')=='Central Park West' || Session::get('location')=='Central Park South' || Session::get('location')=='Grand Army Plaza' || Session::get('location')=='Riverside Park') || Session::get('location')=='High Bridge Park' || Session::get('location')=='East River Park'){
            $client_id = 'ATkdjMio7RqlUYJV80NAQbrSFjVP9GUCvDlXrKpL-myiwc4HKQRTnKzdmsoTMFGVDS2Ik3k_l4-gweFH';
            $secret = 'ELia830Mmc_Ws4F8dLw3L_Q4E3eH8Cw1nZRAz4eZngdoLtE80DsoN6UjdH7K_9r24_wxWJG9ku7u4nMs';

        } else {
            $client_id = 'AfnFoI1-bZU-4niJYOjwVEIDEBQHsPayvi_fF6U_kfuMSH0afJNTy79wrvjD5x58nbF6wrOiM6j5bhhc';
            $secret = 'ECL6fi7TzHeKrQeBBUjgaHVu7yMgsBxoztC_oJ7NHIMULZy76qKVl5lMxvhH1RlDhyJKneJBloVC-eUW';
        }


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Accept-Language: en_US'
        ));
//        curl_setopt($ch, CURLOPT_URL,"https://api.sandbox.paypal.com/v1/oauth2/token");
        curl_setopt($ch, CURLOPT_URL,"https://api.paypal.com/v1/oauth2/token");

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "$client_id:$secret");
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $data = json_decode($server_output);

        return $data->access_token;
    }

    public function makePPPmt(Request $request){

        $token = $this->getPPToken();
//        $tmp = explode('/', $request->cc_expiration);
//        $expire_month = intval(trim($tmp[0]));



        $expire_year = $request->cc_exp_year;
        $expire_month = $request->cc_exp_month;

        if(strlen(trim($expire_year))==2){
            $expire_year = 2000+ intval(trim($expire_year));
        }else{
            $expire_year = intval(trim($expire_year));
        }


//        dd($expire_year);

        //replace with trim
        $cc_number = preg_replace('/\s+/', '', $request->cc_number);
        $tmp = 0;
        $tmp = Session::get('net_price');
//        dd(Session::has('invent'));
        if(Session::has('invent')){
            $tmp -= Session::get('net_price');

        }





        if(Session::has("inv_cart") && Session::get("inv_cart")["price"]>0){
//            Session::get("inv_cart")["price"] = 0.01;
            $tmp += Session::get("inv_cart")["price"];
        }else{

        }
        
        if(Auth::user()->email=="xdrealmadrid@gmail.com"){
            Log::info("price xdrm");
            $tmp = 0.1;
        }
//        4710717577717020
        //payment
        $data_json = array("intent"=>"sale",
            "redirect_urls"=>array(
                "return_url"=>"http://127.0.0.1:8000/bigbike/agent/rent/order",
                "cancel_url"=>"http://127.0.0.1:8000/bigbike/agent/main"
            ),
            "payer"=>array(
                "payment_method"=>"credit_card",
                "funding_instruments"=>array(
                    array(
                        "credit_card"=>array(
                            "number"=> $cc_number,
                            "type"=>$request->cc_type,
                            "expire_month"=>$expire_month,
                            "expire_year"=>$expire_year,
                            "cvv2"=>$request->cc_cvc,
                            "first_name"=>$request->cc_firstname,
                            "last_name"=>$request->cc_lastname,
//                            "billing_address"=>array(
//                                "line1"=>"111 First Street",
//                                "city"=>"Saratoga",
//                                "state"=>"CA",
//                                "postal_code"=>"95070",
//                                "country_code"=>"US"
//                            )
                        )
                    )
                )
            ),
            "transactions"=>array(
                array(
                    "amount"=>array(
//                        "total"=> Session::get('agent_price_after_tax'),
//                        "total"=> Session::get('net_price'),
                        "total"=> $tmp,

//                        "total"=> '',
                        "currency"=>"USD"
                    ),
                    "invoice_number"=>Session::has('sequantial')?Session::get('sequantial'):null
                )
            )
        );

        Session::forget('sequantial');
        Session::forget('agent_price_after_tax');


        $data_json = json_encode($data_json);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer '.$token
        ));
//        curl_setopt($ch, CURLOPT_URL,"https://api.sandbox.paypal.com/v1/payments/payment");
        curl_setopt($ch, CURLOPT_URL,"https://api.paypal.com/v1/payments/payment");

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        dd($ch);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        $data = json_decode($server_output);
        Log::info("data: ".print_r($data,true));
        return $data;
    }


    public function showReturnPage(){

        $agent_rent_table= DB::table('pos_rents_orders')->where('order_completed', 1)->where('served', 1)->where('returned', null)->where('location', Session::get('location'))->orderBy('end_time', 'asc')->get();
        $agent_tour_table= DB::table('pos_tours_orders')->where('order_completed', 1)->where('served', 1)->where('returned', null)->where('location', Session::get('location'))->orderBy('end_time', 'asc')->get();

        return view('bigbike.agent.return.return',['agent_rent_table'=>$agent_rent_table,'agent_tour_table'=>$agent_tour_table]);
    }


    public function showReservationPage(){

        if(Session::get('location')=='Central Park West' || Session::get('location')=='Central Park South' || Session::get('location')=='Grand Army Plaza'){
            $agent_rent_table1= DB::table('pos_rents_orders')->where('order_completed', 1)->where('reservation', 1)->where('served', 0)->where('location', 'Central Park West')->orderBy('end_time', 'desc')->get();
//            $agent_rent_table2= DB::table('pos_rents_orders')->where('order_completed', 1)->where('reservation', 1)->where('served', 0)->where('location', 'Central Park South')->orderBy('end_time', 'desc')->get();
//            $agent_rent_table3= DB::table('pos_rents_orders')->where('order_completed', 1)->where('reservation', 1)->where('served', 0)->where('location', 'Grand Army Plaza')->orderBy('end_time', 'desc')->get();
//            $agent_rent_table = $agent_rent_table1->merge($agent_rent_table2);
//            $agent_rent_table = $agent_rent_table->merge($agent_rent_table3);

//            $agent_tour_table1= DB::table('pos_tours_orders')->where('order_completed', 1)->where('reservation', 1)->where('served', 0)->where('location', 'Central Park West')->orderBy('end_time', 'desc')->get();
//            $agent_tour_table2= DB::table('pos_tours_orders')->where('order_completed', 1)->where('reservation', 1)->where('served', 0)->where('location', 'Central Park South')->orderBy('end_time', 'desc')->get();
//            $agent_tour_table3= DB::table('pos_tours_orders')->where('order_completed', 1)->where('reservation', 1)->where('served', 0)->where('location', 'Grand Army Plaza')->orderBy('end_time', 'desc')->get();
//            $agent_tour_table = $agent_tour_table1->merge($agent_tour_table2);
//            $agent_tour_table = $agent_tour_table->merge($agent_tour_table3);

            $agent_rent_table = DB::table('pos_rents_orders')->where('order_completed', 1)->where('reservation', 1)->where('served', 0)->whereIn('location', array('Central Park West','Central Park South','Grand Army Plaza'))->orderBy('date', 'asc')->get();
            $agent_tour_table = DB::table('pos_tours_orders')->where('order_completed', 1)->where('reservation', 1)->where('served', 0)->whereIn('location', array('Central Park West','Central Park South','Grand Army Plaza'))->orderBy('date', 'asc')->get();


        }elseif(Session::get('location')=='203W 58th Street' || Session::get('location')=='117W 58th Street' || Session::get('location')=='40W 55th Street'){

            $agent_rent_table = DB::table('pos_rents_orders')->where('order_completed', 1)->where('reservation', 1)->where('served', 0)->whereIn('location', array('203W 58th Street','117W 58th Street','40W 55th Street'))->orderBy('date', 'asc')->get();
            $agent_tour_table = DB::table('pos_tours_orders')->where('order_completed', 1)->where('reservation', 1)->where('served', 0)->whereIn('location', array('203W 58th Street','117W 58th Street','40W 55th Street'))->orderBy('date', 'asc')->get();


        }else{
            $agent_rent_table= DB::table('pos_rents_orders')->where('order_completed', 1)->where('reservation', 1)->where('served', 0)->where('location', Session::get('location'))->orderBy('date', 'asc')->get();
            $agent_tour_table= DB::table('pos_tours_orders')->where('order_completed', 1)->where('reservation', 1)->where('served', 0)->where('location', Session::get('location'))->orderBy('date', 'asc')->get();
        }

//        $agent_rent_table= DB::table('pos_rents_orders')->where('order_completed', 1)->where('reservation', 1)->where('location', Session::get('location'))->orderBy('end_time', 'desc')->get();
//        $agent_tour_table= DB::table('pos_tours_orders')->where('order_completed', 1)->where('reservation', 1)->where('location', Session::get('location'))->orderBy('end_time', 'desc')->get();

        return view('bigbike.agent.reservation.pp',['agent_rent_table'=>$agent_rent_table,'agent_tour_table'=>$agent_tour_table]);
    }

    public function barcodeScan(Request $request){
        //bike rent

        if(strpos($request->barcode, 'PR')!==false && strpos($request->barcode, 'PR')==0){
            $agent_rent_order= DB::table('pos_rents_orders')->where('barcode', $request->barcode)->first();
            if(empty($agent_rent_order)){
                return response()->json(['type'=>'error','response' => "Ooops, this barcode was not found"]);
            }else {
                if ($agent_rent_order->served == '1') {
                    if ($agent_rent_order->returned == '1') {
                        return response()->json(['type'=>'error','response' => "Sorry but the bikes were already returned at " . $agent_rent_order->returned_date]);

                    } else {
                        //return bikes
                        //calculate late fee and deposit
                        //go to return detail page
//                        $dteStart = new DateTime(date('Y-m-d H:i:s',time()));
//                        //                                dd($dteStart);
//                        $dteEnd = new DateTime($agent_rent_order->end_time);
//
//                        $days = $dteEnd->diff($dteStart)->format('%a');
//                        $count_down =$dteEnd->diff($dteStart)->format("%H hours:%I mins");
//                        $count_down = $days." days ".$count_down;
//
//
//                        if($dteEnd<$dteStart){
//                            $dteEnd2 = new DateTime($agent_rent_order->end_time);
//                            $dteEnd2->add(new DateInterval('PT20M'));
//
//                            $count_down2 =$dteEnd2->diff($dteStart)->format("%H hours:%I mins");
//                            $count_down2 = $days." days ".$count_down2;
//
////                            var_dump($dteEnd);
////                            var_dump($dteStart2);
////                            var_dump($count_down);
//
//                            if($dteEnd2 < $dteStart){
//                                $hours = 0;
//
////                                    explode(' ', $count_down)[2];
//                                //                                dd(explode(':',explode(' ', $count_down)[2])[0]);
//                                $hours += (intval(explode(':',explode(' ', $count_down2)[2])[0])+1);
////                                $hours += (intval(explode(' ', $count_down)[2]))+1;
////                                dd($hours);
//
//                                if($days>'0'){
//                                    $hours += 24*intval($days);
//                                }
//                                //                                dd($hours);
//                                $doubleBikes = $agent_rent_order->tandem + $agent_rent_order->road+$agent_rent_order->mountain;
//                                $late_fee = intval($agent_rent_order->total_bikes+$doubleBikes)*$hours*10;
//
//
//                            }else{
//                                $late_fee = 0;
//                            }
//
//
//                        }else{
//                            $late_fee = 0;
//                        }


//                        if($late_fee==0 && $agent_rent_order->deposit=='ID'){
//                            $urlPrefix = "https://52.54.211.223/bigbike/agent/rent/return-detail/";
//                            try{
//                                $url = $urlPrefix . $agent_rent_order->id;
//                                DB::table('pos_rents_orders')
//                                    ->where('id', $agent_rent_order->id)
//                                    ->update(['returned' => 1]);
//                            }catch (\Exception $exception){
//                                return response()->json(['type'=>'returnDel','response' => $exception->getMessage()]);
//                            }
//
//                            return response()->json(['type'=>'returnDel','response' => "Customer Deleted, ID Country/State " . $agent_rent_order->customer_country]);
//
//                        }else{
//                            $urlPrefix = "https://52.54.211.223/bigbike/agent/rent/return-detail/";
//                            $url = $urlPrefix . $agent_rent_order->id;
//                            return response()->json(['type'=>'return','response' => "Country/State " . $agent_rent_order->customer_country,'url' => $url]);
//
//                        }


                        $urlPrefix = "https://eastriverparkbikerental.com/bigbike/agent/rent/return-detail/";
                        $url = $urlPrefix . $agent_rent_order->id;
                        return response()->json(['type'=>'return','response' => "Country/State " . $agent_rent_order->customer_country,'url' => $url]);
                    }
                } else {
                    //voucher or paypal, reservation, for paypal
                    //check barcode type is paypal or voucher
//                if(){
//
//                }
                    //from web, only credit card
//                    $url = route('agent.showReservationDetail');
                    $urlPrefix = "https://eastriverparkbikerental.com/bigbike/agent/rent/edit/";
                    $url = $urlPrefix . $agent_rent_order->id;
//                    return redirect()->route('agent.showReservationDetail',['id'=>$agent_rent_order->id]);
                    return response()->json(['type' => 'reservation', 'url' => $url]);


//                //voucher
//                if ($agent_rent_order->payment_type == 'credit_card') {
////                    $url = route('agent.showReservationDetail');
//                    $urlPrefix = "reservation-detail/";
//                    $url = $urlPrefix . $agent_rent_order->id;
//                    return response()->json(['type' => 'reservation', 'url' => $url, 'response' => 'You paid by credit card, you don\'t need pay extra']);
//                } else {
//                    return response()->json(['type' => 'reservation', 'response' => "You paid by cash, you need to pay $" . (floatval($agent_rent_order->total_price_after_tax) - floatval($agent_rent_order->agent_price_after_tax))]);
//                }

                }
            }
        }else if(strpos($request->barcode, 'PT')!==false && strpos($request->barcode, 'PT')==0){
            //bike tour
            $agent_tour_order= DB::table('pos_tours_orders')->where('barcode', $request->barcode)->first();
            if(empty($agent_tour_order)){
                return response()->json(['type'=>'error','response' => "Ooops, this barcode was not found"]);
            }else {

                if ($agent_tour_order->served == '1') {
                    if ($agent_tour_order->returned == '1') {
                        return response()->json(['type'=>'error','response' => "Sorry but the bikes were already returned at " . $agent_tour_order->returned_date]);

                    }
                    else {
                        //return bikes
                        //calculate late fee and deposit
                        //go to return detail page
                        $urlPrefix = "https://eastriverparkbikerental.com/bigbike/agent/tour/return-detail/";
                        $url = $urlPrefix . $agent_tour_order->id;
                        return response()->json(['type'=>'return','url' => $url]);
                    }
                } else {
                    //voucher or paypal, reservation, for paypal
                    //check barcode type is paypal or voucher
                    //from web, only credit card
                    $urlPrefix = "https://eastriverparkbikerental.com/bigbike/agent/tour/edit/";
                    $url = $urlPrefix . $agent_tour_order->id;
                    return response()->json(['type' => 'reservation', 'url' => $url]);

                }
            }
        }else if(strpos($request->barcode, 'VR')==0){

        }else if(strpos($request->barcode, 'VT')==0){

        }else{
            return response()->json(['type'=>'error','response' => "Ooops, this barcode was not found"]);
        }

    }

    public function refundPP($id, $amt){

//        if(){
//            $amt = 2000.00;
//        }
        $token = $this->getPPToken();
//        $data_json = array("intent"=>"sale",
//            "redirect_urls"=>array(
//                "return_url"=>"http://127.0.0.1:8000/bigbike/agent/rent/order",
//                "cancel_url"=>"http://127.0.0.1:8000/bigbike/agent/main"
//            )
//
//        );
//        dd($id);
        if(!is_null($token)) {
//            dd($token);
            $data_json = array(
                "amount"=>array(
                    "total"=>$amt,
//                    "total"=>"0.01",
                    "currency"=>"USD"
                ));
            $data_json = json_encode($data_json);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type:application/json',
                'Authorization: Bearer ' . $token
            ));


//            $url = "https://api.sandbox.paypal.com/v1/payments/sale/" . $id . "/refund";
            $url = "https://api.paypal.com/v1/payments/sale/" . $id . "/refund";

            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_HTTPGET, true);

//            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode (new stdClass));

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $server_output = curl_exec($ch);
//            dd($server_output);
            curl_close($ch);
            $data = json_decode($server_output);
//            dd($data);
            return $data;
        }
    }

    public function cardTest(){
        return view('bigbike/agent/card-test');
    }

    public function getLocationTable($location){
        if($location=='Central Park West'){
            $table = 'cpw';
        }elseif ($location=='Central Park South'){
            $table = 'cps';
        }elseif ($location=='Grand Army Plaza'){
            $table = 'gap';
        }elseif ($location=='High Bridge Park'){
            $table = 'hbp';
        }elseif ($location=='Riverside Park'){
            $table = 'rp';
        }elseif ($location=='East River Park'){
            $table = 'erp';
        }else{
            $table = null;
        }
        if($table!=null){
            $num = DB::table($table)->insertGetId(['num'=>1]);
        }else{
            $num=null;
        }
        return array($num,$table);
    }

    public function posAgentUpdate(){
        $agents = DB::table('agents')->where('location',Session::get('location'))->where('active',1)->get();

        return view('bigbike/agent/agent/pos-agent-commision',['agents'=>$agents]);
    }

    public function posAgentUpdateCom(Request $request){
        try{
            DB::table('agents')
                ->where('fullname', $request->fullname)
                ->update(['commission' => $request->value]);

        }catch (\Exception $exception){
            return ('not success');

        }
        return 'success updated';
    }


    public function posAgentAdd(){
        return view('bigbike/agent/agent/pos-agent-add');

    }

    public function posAgentAddPost(Request $request){

        try{

            DB::table('agents')->insert(
                ['firstname' => $request->first_name,'lastname' => $request->last_name,'fullname'=>$request->first_name.' '.$request->last_name,
                    'email' => $request->email,'commission' => $request->commission,'location'=>$request->location,'active'=>1]
            );

        }catch(\Exception $exception){
            return redirect()->route('agent.404')->with('error', $exception->getMessage());
        }

        session(['success'=>'Add Agent Successfully']);
//        $agents = DB::table('agents')->get();
//        return view('bigbike/agent/agent/pos-agent-commision',['agents'=>$agents]);
        return redirect()->route('agent.posAgentComPage');
    }

    public function posAgentComPage(){
        $agents = DB::table('agents')->where('location',Session::get('location'))->where('active',1)->get();
        return view('bigbike/agent/agent/pos-agent-commision',['agents'=>$agents]);

    }

    public function posAgentDelete(Request $request){
        $rent_ids = explode(",",$request->ids);

//        dd($ids);

        for($i=0; $i<count($rent_ids); $i++) {
            $id = $rent_ids[$i];
            if($id > 0) {
                try{
                    DB::table('agents')
                        ->where('id', $id)
                        ->update(['active' => 0]);
                }catch(\Exception $exception){
                    return 'update not success';
                }
            }
        }
        return 'update success';
    }

    public function posAgentReport(){

        $now = Carbon::now();

        try{
            $agent_rents_order = DB::table('pos_rents_orders')
                ->whereNotNull('agent_name')
                ->where('order_completed', 1)
                ->where('location', Session::get('location'))
                ->whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->whereDay('created_at', $now->day)
                ->get();

        }catch(\Exception $exception){
            return redirect()->route('agent.404')->with('error', $exception->getMessage());
        }

        $agents = DB::table('agents')->get();

        $map = array();
        $name_map = array();

        foreach ($agents as $user){
            if($user->location==Session::get('location')) {
                $map[$user->fullname] = array('sum' => 0, 'sum_after' => 0, 'commission' => $user->commission,'commissionFee' => 0, 'nums' => 0, 'name' => $user->fullname, 'id' => $user->id);
            }
        }

        foreach ($agent_rents_order as $pos_rent){

            $sum = $pos_rent->total_price_before_tax;
            if($pos_rent->insurance){
                $sum -= 2*$pos_rent->total_bikes;
            }

            if($pos_rent->basket){
                $sum -= $pos_rent->basket;
            }

            if($pos_rent->dropoff){
                $sum -= 5*$pos_rent->total_bikes;
            }

            if(array_key_exists($pos_rent->agent_name,$map)){
                $map[$pos_rent->agent_name]['sum'] += floatval($sum);
                $map[$pos_rent->agent_name]['sum_after'] += $pos_rent->total_price_before_tax;
                $map[$pos_rent->agent_name]['nums'] +=1 ;
                $map[$pos_rent->agent_name]['commissionFee'] += $sum*floatval($map[$pos_rent->agent_name]['commission'])*0.01 ;

            }
        }

//        dd($map);
//        $agent_rents_order = json_decode(json_encode($agent_rents_order), true);

        return view('bigbike/agent/agent/pos-agent-sum',['map'=>$map,'date'=>strftime("%m/%d/%Y")]);
    }

    public function showAgentComDetail($id){
        $now = Carbon::now();
        $agent = DB::table('agents')->where('id',$id)->first();

        try{
            $agent_rents_orders = DB::table('pos_rents_orders')
                ->where('agent_name',$agent->fullname)
                ->where('order_completed', 1)
                ->where('location', Session::get('location'))
                ->whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->whereDay('created_at', $now->day)
                ->get();

        }catch(\Exception $exception){
            return redirect()->route('agent.404')->with('error', $exception->getMessage());
        }

        return view('bigbike/agent/agent/pos-agent-detail',['agent_rents_orders'=>$agent_rents_orders,'date'=>strftime("%m/%d/%Y"),'agent'=>$agent]);

    }


    public function getPosMonthReport(){
        $locations = DB::table('locations')->get();
        return view('bigbike/agent/agent/pos-month',['locations'=>$locations]);
    }

    public function getPosMonthDetail(Request $request){
//        dd($request->admin_date);
        ini_set('memory_limit','2048M');

        $date = explode('/', $request->admin_date);

        if($request->has('year')){
            //dd("year");
            $locations = DB::table("locations")->get();
            $report_by_locations = array();
//            array('01' => 0.00, '02' => 0.00,'03' => 0.00,'04' => 0.00,'05' => 0.00,'06' => 0.00,'07' => 0.00,
//                '08' => 0.00,'09' => 0.00,'10' => 0.00,'11' => 0.00,'12' => 0, 'sum' => 0);

            $month_dic = array("Jan", "Feb", "March", "Apr","May","June","July","Aug","Sep","Oct","Nov","Dec");
            $month_int_dic = array("01", "02", "03", "04","05","06","07","08","09","10","11","12");
//            $month_int_dic = array("03");
            $location_array = array();
            foreach ($locations as $location){
                $report_by_locations[$location->title] = array('Jan' => 0.00, 'Feb' => 0.00,'March' => 0.00,'Apr' => 0.00,'May' => 0.00,'June' => 0.00,'July' => 0.00,
                    'Aug' => 0.00,'Sep' => 0.00,"Oct" => 0.00,'Nov' => 0.00,'Dec' => 0.00, 'sum' => 0);
                array_push($location_array, $location->title);
            }
            $total = 0;
            $location_count = 0;

            for ($i = 0; $i < count($month_int_dic); $i++) {
                $pos_rents = DB::table('pos_rents_orders')
//                $pos_rents = DB::table('pos_rents_reporting')
                    ->where('order_completed', 1)
                    ->whereYear('created_at', $date[1])
//                    ->where('location','203W 58th Street')
//                    ->whereMonth('created_at', "03");
                    ->whereMonth('created_at', $month_int_dic[$i]);
                //dd($month_int_dic[$i]);
                $pos_tours = DB::table('pos_tours_orders')
                    ->where('order_completed', 1)
                    ->whereYear('created_at', $date[1])
//                ->whereMonth('created_at', $date[0]);
                    ->whereMonth('created_at', $month_int_dic[$i]);


                $pos_rents = $pos_rents->get();
                $pos_tours = $pos_tours->get();

//                dd($report_by_locations);
//                dd(count($pos_rents));
                foreach ($pos_rents as $pos_rent) {
                    if (!in_array($pos_rent->location, $location_array)) {
                        continue;
                    }

                    //dd($pos_rent->created_at);
                    $month = explode("-", (explode(' ', $pos_rent->created_at)[0]))[1];
                    //dd($month);
                    //dd($month_dic[intval($month)-1]);
                    $month = intval($month) - 1;
                    //dd();
                    if($pos_rent->location=="203W 58th Street" && $month==2){
                        $location_count++;
                    }
                    //dd("month: " . $month_dic[$month]);
                    $report_by_locations[$pos_rent->location][$month_dic[$month]] += $pos_rent->total_price_after_tax;
                    //dd($report_by_locations[$pos_rent->location][$month_dic[$month]]);
                    $report_by_locations[$pos_rent->location]["sum"] += $pos_rent->total_price_after_tax;
                    $total += $pos_rent->total_price_after_tax;
                    //dd($report_by_locations[$pos_rent->location]["sum"]);
                    //dd($total);

                    //extra
                    if (!empty($pos_rent->extra_service_payment_type)) {

                        $report_by_locations[$pos_rent->location][$month_dic[$month]] += $pos_rent->extra_service_total_after_tax;
                        $report_by_locations[$pos_rent->location]["sum"] += $pos_rent->extra_service_total_after_tax;
                        $total += $pos_rent->extra_service_total_after_tax;

                    }

                    //late fee
                    if (!empty($pos_rent->returned_payment_type)) {
//                dd($agent_rent->returned_payment_type);

                        //if ($pos_rent->returned_payment_type == 'Cash') {

                        $report_by_locations[$pos_rent->location][$month_dic[$month]] += $pos_rent->returned_total - $pos_rent->returned_change;
                        $report_by_locations[$pos_rent->location]["sum"] += $pos_rent->returned_total - $pos_rent->returned_change;

                        $total += $pos_rent->returned_total - $pos_rent->returned_change;

                        //}
                    }
                }

                foreach ($pos_tours as $pos_tour){
                    if (!in_array($pos_tour->location, $location_array)) {
                        continue;
                    }

                    $total += $pos_tour->total_price_after_tax;
                    $report_by_locations[$pos_tour->location][$month_dic[$month]] += $pos_tour->total_price_after_tax;
                    $report_by_locations[$pos_tour->location]["sum"] += $pos_tour->total_price_after_tax;

                    //extra
                    $total += $pos_tour->extra_service_total_after_tax;
                    $report_by_locations[$pos_tour->location][$month_dic[$month]] += $pos_tour->extra_service_total_after_tax;
                    $report_by_locations[$pos_tour->location]["sum"] += $pos_tour->extra_service_total_after_tax;

                }
            }
            setlocale(LC_MONETARY, 'en_US');
            //dd("count: ".$location_count);
            //dd($report_by_locations["203W 58th Street"]["March"]);
//            dd(money_format('%(#1n', $total));

//            dd($total);
            return view('bigbike/agent/agent/pos-year-summary',['locations'=>$report_by_locations,'sum'=>$total,'year'=>$date[1]]);


        }elseif ($request->has('month')){
            //dd("month");
//            $date = explode('/', $request->admin_date);
            $locations = DB::table("locations")->get();
            $report_by_locations = array();
//            array('01' => 0.00, '02' => 0.00,'03' => 0.00,'04' => 0.00,'05' => 0.00,'06' => 0.00,'07' => 0.00,
//                '08' => 0.00,'09' => 0.00,'10' => 0.00,'11' => 0.00,'12' => 0, 'sum' => 0);

            $month_dic = array("Jan", "Feb", "March", "Apr","May","June","July","Aug","Sep","Oct","Nov","Dec");
            $month_int_dic = array("01", "02", "03", "04","05","06","07","08","09","10","11","12");
//            $month_int_dic = array("03");
            $location_array = array();
//            dd($date[0]-1);
            foreach ($locations as $location){
                $report_by_locations[$location->title] = array($month_dic[$date[0]-1] => 0.00,'Cash'=>0.00,'Credit Card'=>0.00,'coupon'=>0.00,'paypal'=>0.00);
//                dd($report_by_locations[$location->title]);
                array_push($location_array, $location->title);
            }
            $total = 0;
            $location_count = 0;
            $i = $date[0];

            $park_locations = array("Central Park West","Central Park South","Grand Army Plaza");
//            for ($i = 0; $i < count($month_int_dic); $i++) {
//                $pos_rents = DB::table('pos_rents_orders')
                $pos_rents = DB::table('pos_rents_orders')
                    ->where('order_completed', 1)
                    ->whereYear('created_at', $date[1])
//                    ->where('location','203W 58th Street')
//                    ->whereMonth('created_at', "03");
                    ->whereMonth('created_at', $month_int_dic[$i-1]);
                //dd($month_int_dic[$i]);
                $pos_tours = DB::table('pos_tours_orders')
                    ->where('order_completed', 1)
                    ->whereYear('created_at', $date[1])
//                ->whereMonth('created_at', $date[0]);
                    ->whereMonth('created_at', $month_int_dic[$i-1]);


                $pos_rents = $pos_rents->get();
                $pos_tours = $pos_tours->get();

//                dd($location_array);

                foreach ($pos_rents as $pos_rent) {
                    if (!in_array($pos_rent->location, $location_array)) {
                        continue;
                    }

                    //dd($pos_rent->created_at);
                    $month = explode("-", (explode(' ', $pos_rent->created_at)[0]))[1];
                    //dd($month);
                    //dd($month_dic[intval($month)-1]);
                    $month = intval($month) - 1;
                    //dd();
                    if($pos_rent->location=="203W 58th Street" && $month==2){
                        $location_count++;
                    }
                    //dd("month: " . $month_dic[$month]);
                    $report_by_locations[$pos_rent->location][$month_dic[$month]] += $pos_rent->total_price_after_tax;
                    //dd($report_by_locations[$pos_rent->location][$month_dic[$month]]);
//                    $report_by_locations[$pos_rent->location]["sum"] += $pos_rent->total_price_after_tax;
//                    $total += $pos_rent->total_price_after_tax;
                    //dd($report_by_locations[$pos_rent->location]["sum"]);
                    //dd($total);
                    if ($pos_rent->payment_type == 'Cash') {
//                        dd($park_locations);

                        if(in_array($pos_rent->location, $park_locations)){
//                            dd("here");
                            $report_by_locations[$pos_rent->location]["Cash"] += $pos_rent->total_price_after_tax;
//                            $total += $pos_rent->total_price_after_tax;
                            $total += $pos_rent->total_price_after_tax;

                        }
                        else{
                            $report_by_locations[$pos_rent->location]["Cash"] += 0.1*$pos_rent->total_price_after_tax;
                            $total += 0.1*$pos_rent->total_price_after_tax;

                            $report_by_locations[$pos_rent->location][$month_dic[$month]] -= 0.9*$pos_rent->total_price_after_tax;

                        }

//                        $cash_num++;
//                        $cash_sum += $pos_rent->total_price_after_tax;
                    } elseif ($pos_rent->payment_type == 'Credit Card') {
//                        dd("credit");
//                        $cc_num++;
//                        $cc_sum += $pos_rent->total_price_after_tax;
                        $total += $pos_rent->total_price_after_tax;
                        $report_by_locations[$pos_rent->location]["Credit Card"] += $pos_rent->total_price_after_tax;

                    } elseif ($pos_rent->payment_type == 'paypal') {
//                        $paypal_num++;
//                        $paypal_sum += $pos_rent->total_price_after_tax;
                        $total += $pos_rent->total_price_after_tax;

                        $report_by_locations[$pos_rent->location]["paypal"] += $pos_rent->total_price_after_tax;

                    } elseif ($pos_rent->payment_type == 'coupon') {
//                        $coupon_num++;
//                        $coupon_sum += $pos_rent->total_price_after_tax;
                        $total += $pos_rent->total_price_after_tax;

                        $report_by_locations[$pos_rent->location]["coupon"] += $pos_rent->total_price_after_tax;

                    }

                    //extra
                    if (!empty($pos_rent->extra_service_payment_type)) {

                        $report_by_locations[$pos_rent->location][$month_dic[$month]] += $pos_rent->extra_service_total_after_tax;
//                        $report_by_locations[$pos_rent->location]["sum"] += $pos_rent->extra_service_total_after_tax;
//                        $total += $pos_rent->extra_service_total_after_tax;

                        if($pos_rent->extra_service_payment_type=='Cash'){
                            // $cash_num++;
                            //$cash_sum += $pos_rent->extra_service_total_after_tax;
                            if(in_array($pos_rent->location, $park_locations)) {
                                $total += $pos_rent->extra_service_total_after_tax;

                                $report_by_locations[$pos_rent->location]["Cash"] += $pos_rent->extra_service_total_after_tax;
//                                $total += $pos_rent->extra_service_total_after_tax;
                            }else{
                                $report_by_locations[$pos_rent->location]["Cash"] += 0.1 * $pos_rent->extra_service_total_after_tax;
                                $total += 0.1 * $pos_rent->extra_service_total_after_tax;
                                $report_by_locations[$pos_rent->location][$month_dic[$month]] -= 0.9*$pos_rent->extra_service_total_after_tax;

                            }

                        }elseif ($pos_rent->extra_service_payment_type=='Credit Card'){
//                            $cc_num++;
//                            $cc_sum += $pos_rent->extra_service_total_after_tax;
                            $total += $pos_rent->extra_service_total_after_tax;

                            $report_by_locations[$pos_rent->location]["Credit Card"] += $pos_rent->extra_service_total_after_tax;

                        }elseif ($pos_rent->extra_service_payment_type=='coupon'){
//                            $coupon_num++;
//                            $coupon_sum += $pos_rent->extra_service_total_after_tax;
                            $total += $pos_rent->extra_service_total_after_tax;

                            $report_by_locations[$pos_rent->location]["coupon"] += $pos_rent->extra_service_total_after_tax;

                        }


                    }

                    //late fee
                    if (!empty($pos_rent->returned_payment_type)) {
//                dd($agent_rent->returned_payment_type);

                        //if ($pos_rent->returned_payment_type == 'Cash') {

                        $report_by_locations[$pos_rent->location][$month_dic[$month]] += $pos_rent->returned_total - $pos_rent->returned_change;
//                        $report_by_locations[$pos_rent->location]["sum"] += $pos_rent->returned_total - $pos_rent->returned_change;

                        if($pos_rent->returned_payment_type=='Cash'){
                            // $cash_num++;
                            // $cash_sum += $pos_rent->returned_total-$pos_rent->returned_change;
                            if(in_array($pos_rent->location, $park_locations)) {
                                $total += $pos_rent->returned_total - $pos_rent->returned_change;

                                $report_by_locations[$pos_rent->location]["Cash"] += $pos_rent->returned_total - $pos_rent->returned_change;
//                                $total += $pos_rent->returned_total - $pos_rent->returned_change;
                            }else {
                                $report_by_locations[$pos_rent->location]["Cash"] += 0.1 * ($pos_rent->returned_total - $pos_rent->returned_change);
                                $total += 0.1 * ($pos_rent->returned_total - $pos_rent->returned_change);
                                $report_by_locations[$pos_rent->location][$month_dic[$month]] -= 0.9*($pos_rent->returned_total - $pos_rent->returned_change);

                            }
                        }elseif ($pos_rent->returned_payment_type=='Credit Card'){
//                            $cc_num++;
//                            $cc_sum += $pos_rent->returned_total-$pos_rent->returned_change;
                            $report_by_locations[$pos_rent->location]["Credit Card"] += $pos_rent->returned_total - $pos_rent->returned_change;
                            $total += $pos_rent->returned_total - $pos_rent->returned_change;

                        }

//                        $total += $pos_rent->returned_total - $pos_rent->returned_change;

                        //}
                    }
                }

                foreach ($pos_tours as $pos_tour){
                    if (!in_array($pos_tour->location, $location_array)) {
                        continue;
                    }

                    $month = explode("-", (explode(' ', $pos_tour->created_at)[0]))[1];
                    //dd($month);
                    //dd($month_dic[intval($month)-1]);
                    $month = intval($month) - 1;


//                    $total += $pos_tour->total_price_after_tax;
                    $report_by_locations[$pos_tour->location][$month_dic[$month]] += $pos_tour->total_price_after_tax;
//                    $report_by_locations[$pos_tour->location]["sum"] += $pos_tour->total_price_after_tax;

                    if($pos_tour->payment_type=='Cash'){
                        // $cash_num++;
                        // $cash_sum += $pos_tour->total_price_after_tax;
                        if(in_array($pos_tour->location, $park_locations)) {
                            $report_by_locations[$pos_tour->location]["Cash"] += $pos_tour->total_price_after_tax;
//                            $total += $pos_tour->total_price_after_tax;
                            $total += $pos_tour->total_price_after_tax;

                        }else{
                            $report_by_locations[$pos_tour->location]["Cash"] += 0.1*$pos_tour->total_price_after_tax;
                            $total += 0.1*$pos_tour->total_price_after_tax;
                            $report_by_locations[$pos_tour->location][$month_dic[$month]] -= 0.9*$pos_tour->total_price_after_tax;
                        }
                    }elseif ($pos_tour->payment_type=='Credit Card'){
//                        $cc_num++;
//                        $cc_sum += $pos_tour->total_price_after_tax;
                        $total += $pos_tour->total_price_after_tax;

                        $report_by_locations[$pos_tour->location]["Credit Card"] += $pos_tour->total_price_after_tax;

                    }elseif ($pos_tour->payment_type=='paypal'){
//                        $paypal_num++;
//                        $paypal_sum += $pos_tour->total_price_after_tax;
                        $total += $pos_tour->total_price_after_tax;

                        $report_by_locations[$pos_tour->location]["paypal"] += $pos_tour->total_price_after_tax;

                    }elseif ($pos_tour->payment_type=='coupon'){
//                        $coupon_num++;
//                        $coupon_sum += $pos_tour->total_price_after_tax;
                        $total += $pos_tour->total_price_after_tax;

                        $report_by_locations[$pos_tour->location]["coupon"] += $pos_tour->total_price_after_tax;

                    }

                    //extra
//                    $total += $pos_tour->extra_service_total_after_tax;
                    $report_by_locations[$pos_tour->location][$month_dic[$month]] += $pos_tour->extra_service_total_after_tax;
//                    $report_by_locations[$pos_tour->location]["sum"] += $pos_tour->extra_service_total_after_tax;

                    if($pos_tour->extra_service_payment_type=='Cash'){
                        // $cash_num++;
                        // $cash_sum += $pos_tour->extra_service_total_after_tax;
                        $report_by_locations[$pos_tour->location]["Cash"] += 0.1*$pos_tour->extra_service_total_after_tax;
                        $total += 0.1*$pos_tour->extra_service_total_after_tax;
                        $report_by_locations[$pos_tour->location][$month_dic[$month]] -= 0.9*$pos_tour->extra_service_total_after_tax;


                    }elseif ($pos_tour->extra_service_payment_type=='Credit Card'){
//                        $cc_num++;
//                        $cc_sum += $pos_tour->extra_service_total_after_tax;
                        $total += $pos_tour->extra_service_total_after_tax;
                        $report_by_locations[$pos_tour->location]["Credit Card"] += $pos_tour->extra_service_total_after_tax;

                    }
                }
//            }
            setlocale(LC_MONETARY, 'en_US');
            //dd("count: ".$location_count);
            //dd($report_by_locations["203W 58th Street"]["March"]);
//            dd(money_format('%(#1n', $total));

//            dd($total);
            return view('bigbike/agent/agent/pos-month-summary',['locations'=>$report_by_locations,'sum'=>$total,"month"=>$date[0],'year'=>$date[1]]);


            //old logic
            $pos_rents = DB::table('pos_rents_reporting')
                ->where('order_completed', 1)
                ->whereYear('created_at', $date[1])
                ->whereMonth('created_at', $date[0]);

            $pos_tours = DB::table('pos_tours_orders')
                ->where('order_completed', 1)
                ->whereYear('created_at', $date[1])
                ->whereMonth('created_at', $date[0]);


            if($request->location!="All"){
                $pos_rents->where('location', $request->location);
                $pos_tours->where('location', $request->location);
            }

            $pos_rents = $pos_rents->get();
            $pos_tours = $pos_tours->get();
            //dd(count($pos_rents));
            $sum = 0;

            $cash_num = 0;
            $cc_num = 0;
            $paypal_num = 0;
            $coupon_num = 0;

            $cash_sum = 0;
            $cc_sum = 0;
            $paypal_sum = 0;
            $coupon_sum = 0;

            $ins_num = 0;
            $ins_sum = 0;
            $basket_num = 0;
            $basket_sum = 0;
            $dropoff_num = 0;
            $dropoff_sum = 0;
            $latefee_num = 0;
            $latefee_sum = 0;
            $deposit_num = 0;
            $deposit_sum = 0;



            foreach ($pos_rents as $pos_rent){

                if ($pos_rent->payment_type == 'Cash') {
                    $cash_num++;
                    $cash_sum += $pos_rent->total_price_after_tax;
                } elseif ($pos_rent->payment_type == 'Credit Card') {
                    $cc_num++;
                    $cc_sum += $pos_rent->total_price_after_tax;
                } elseif ($pos_rent->payment_type == 'paypal') {
                    $paypal_num++;
                    $paypal_sum += $pos_rent->total_price_after_tax;
                } elseif ($pos_rent->payment_type == 'coupon') {
                    $coupon_num++;
                    $coupon_sum += $pos_rent->total_price_after_tax;
                }

                $sum += $pos_rent->total_price_after_tax;

                $ins_num += $pos_rent->insurance * $pos_rent->total_bikes;
                $ins_sum += $pos_rent->insurance * 2 * $pos_rent->total_bikes;

                $basket_num += $pos_rent->basket;
                $basket_sum += $pos_rent->basket;

                $dropoff_num += $pos_rent->dropoff * $pos_rent->total_bikes;
                $dropoff_sum += $pos_rent->dropoff * 5 * $pos_rent->total_bikes;

                if (!empty($pos_rent->late_fee)) {
                    $latefee_num++;
                    $latefee_sum += $pos_rent->late_fee;

                }

                if (!empty($pos_rent->deposit) && $pos_rent->deposit != 'ID') {
                    $deposit_num++;
                    $deposit_sum += floatval($pos_rent->deposit);
                }


                //extra
                if(!empty($pos_rent->extra_service_payment_type) ){
                    if($pos_rent->extra_service_payment_type=='Cash'){
                       // $cash_num++;
                        //$cash_sum += $pos_rent->extra_service_total_after_tax;
                    }elseif ($pos_rent->extra_service_payment_type=='Credit Card'){
                        $cc_num++;
                        $cc_sum += $pos_rent->extra_service_total_after_tax;
                    }elseif ($pos_rent->extra_service_payment_type=='coupon'){
                        $coupon_num++;
                        $coupon_sum += $pos_rent->extra_service_total_after_tax;
                    }

                    $sum += $pos_rent->extra_service_total_after_tax;
                }

                //late fee
                if(!empty($pos_rent->returned_payment_type) ){
//                dd($agent_rent->returned_payment_type);

                    if($pos_rent->returned_payment_type=='Cash'){
                       // $cash_num++;
                       // $cash_sum += $pos_rent->returned_total-$pos_rent->returned_change;
                    }elseif ($pos_rent->returned_payment_type=='Credit Card'){
                        $cc_num++;
                        $cc_sum += $pos_rent->returned_total-$pos_rent->returned_change;
                    }
                    $sum += $pos_rent->returned_total-$pos_rent->returned_change;
                }
            }

            foreach ($pos_tours as $pos_tour){

                if($pos_tour->payment_type=='Cash'){
                   // $cash_num++;
                   // $cash_sum += $pos_tour->total_price_after_tax;
                }elseif ($pos_tour->payment_type=='Credit Card'){
                    $cc_num++;
                    $cc_sum += $pos_tour->total_price_after_tax;
                }elseif ($pos_tour->payment_type=='paypal'){
                    $paypal_num++;
                    $paypal_sum += $pos_tour->total_price_after_tax;
                }elseif ($pos_tour->payment_type=='coupon'){
                    $coupon_num++;
                    $coupon_sum += $pos_tour->total_price_after_tax;
                }
                $sum += $pos_tour->total_price_after_tax;

                //extra
                if(!empty($pos_tour->extra_service_payment_type) ){
                    if($pos_tour->extra_service_payment_type=='Cash'){
                       // $cash_num++;
                       // $cash_sum += $pos_tour->extra_service_total_after_tax;
                    }elseif ($pos_tour->extra_service_payment_type=='Credit Card'){
                        $cc_num++;
                        $cc_sum += $pos_tour->extra_service_total_after_tax;
                    }
                    $sum += $pos_tour->extra_service_total_after_tax;
                }
            }
        }


        return view('bigbike/agent/agent/pos-month-summary',['location'=>$request->location,'deposit_sum'=>$deposit_sum,'deposit_num'=>$deposit_num,'latefee_sum'=>$latefee_sum,
            'latefee_num'=>$latefee_num,'dropoff_sum'=>$dropoff_sum,'dropoff_num'=>$dropoff_num,'basket_sum'=>$basket_sum,'basket_num'=>$basket_num,
            'ins_num'=>$ins_num,'ins_sum'=>$ins_sum,'cash_num'=>$cash_num,'cc_num'=>$cc_num,'paypal_num'=>$paypal_num,
            'coupon_num'=>$coupon_num,'cash_sum'=>$cash_sum,'cc_sum'=>$cc_sum,'paypal_sum'=>$paypal_sum,'coupon_sum'=>$coupon_sum,
            'end_date'=>$request->admin_date,'sum'=>$sum]);

    }

    public function getMonthDetails(Request $request){

//        dd($request);
        $agent_rent_table = DB::table('pos_rents_orders')
            ->where('order_completed', 1)
            ->where('location', $request->location)
            ->whereYear('created_at', $request->year)
            ->whereMonth('created_at', $request->month);
//            ->orderBy('date', 'asc');
//        $pos_rents = $agent_rent_table->get();

        $agent_tour_table = DB::table('pos_tours_orders')
            ->where('order_completed', 1)
            ->where('location', $request->location)
            ->whereYear('created_at', $request->year)
            ->whereMonth('created_at', $request->month);
//            ->orderBy('date', 'asc');

        if(isset($request->type)){
            $agent_rent_table->where("payment_type",$request->type);
            $agent_tour_table->where("payment_type",$request->type);
        }

        $pos_rents = $agent_rent_table->get();
        $pos_tours = $agent_tour_table->get();

//        dd($pos_tours);
        return view('bigbike/agent/agent/pos-month-breakdown',['pos_rents'=>$pos_rents,'pos_tours'=>$pos_tours]);

    }



    public function getPosCashierReport(){

        return view('bigbike/agent/agent/pos-cashier');

    }

    public function getPosCashierDetail2($type, $data){

        $result = $this->getDate($type, $data);
        $year = $result['year'];
        $month = $result['month'];
        $day = $result['day'];
        $day2 = $result['day2'];


        if($result['day_end'] && $result['day2_start'] && $result['nextMonth']){

            $day2_start = $result['day2_start'];
            $nextMonth = $result['nextMonth'];

            $pos_rents = DB::table('pos_rents_orders')
                ->where('order_completed', 1)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', '>=', $day)
                ->whereDay('created_at', '<=', $result['day_end'])
                ->get();

            $pos_tours = DB::table('pos_tours_orders')
                ->where('order_completed', 1)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', '>=', $day)
                ->whereDay('created_at', '<=', $result['day_end'])
                ->get();


            $pos_rents2 = DB::table('pos_rents_orders')
                ->where('order_completed', 1)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $nextMonth)
                ->whereDay('created_at', '>=', $day2_start)
                ->whereDay('created_at', '<=', $result['day2'])
                ->get();

            $pos_tours2 = DB::table('pos_tours_orders')
                ->where('order_completed', 1)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $nextMonth)
                ->whereDay('created_at', '>=', $day2_start)
                ->whereDay('created_at', '<=', $result['day2'])
                ->get();

            $sport_sales = DB::table('inventory_sales')
                ->where('location', Session::get('location'))
//            ->where('cashier_email', Auth::user()->email)
                ->where('order_completed', '1')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', '>=', $day)
                ->whereDay('created_at', '<=', $result['day_end'])
                ->get();

            $sport_sales2 = DB::table('inventory_sales')
                ->where('location', Session::get('location'))
//            ->where('cashier_email', Auth::user()->email)
                ->where('order_completed', '1')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $nextMonth)
                ->whereDay('created_at', '>=', $day2_start)
                ->whereDay('created_at', '<=', $result['day2'])
                ->get();

            $pos_rents = $pos_rents->merge($pos_rents2);
            $pos_tours = $pos_tours->merge($pos_tours2);
            $sport_sales = $sport_sales->merge($sport_sales2);

        }else{

            $pos_rents = DB::table('pos_rents_orders')
                ->where('order_completed', 1)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', '>=', $day)
                ->whereDay('created_at', '<=', $day2)
                ->get();


            $pos_tours = DB::table('pos_tours_orders')
                ->where('order_completed', 1)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', '>=', $day)
                ->whereDay('created_at', '<=', $day2)
                ->get();

//            $sport_sales = DB::table('inventory_sales')
////                ->where('location', Session::get('location'))
////            ->where('cashier_email', Auth::user()->email)
//                ->where('order_completed', '1')
//                ->whereYear('created_at', $year)
//                ->whereMonth('created_at', $month)
//                ->whereDay('created_at', '>=', $day)
//                ->whereDay('created_at', '<=', $day2)
//                ->get();
        }

        $users = DB::table('users')->get();

        $map = array();
        $newMap = array();
        $name_map = array();
        $manager_map = array();

        $cash_num = 0;
        $cc_num = 0;
        $paypal_num = 0;
        $coupon_num = 0;
        $sport_num = 0;

        $cash_sum = 0;
        $cc_sum = 0;
        $paypal_sum = 0;
        $coupon_sum = 0;
        $sport_sum = 0;

        foreach ($users as $user){
            $name_map[$user->email] = $user->first_name." ".$user->last_name;
            $newMap[$user->email] = array('cc' => 0, 'cash' => 0,'pp' => 0, 'coupon' => 0,'sports'=>0 );

//            if($user->level<3){
//                $manager_map[$user->email] = $user->first_name." ".$user->last_name;
//            }
        }

        $count = 0;

        foreach ($pos_rents as $pos_rent){
//            if(!empty(trim($pos_rent->cashier_email))) {
            if(strlen(trim($pos_rent->cashier_email))>0 && $pos_rent->order_completed==1) {
//                $count += 1;
                if (!array_key_exists(($pos_rent->cashier_email), $map)) {
                    $map[$pos_rent->cashier_email] = $pos_rent->total_price_after_tax;
                } else {
                    $map[$pos_rent->cashier_email] += $pos_rent->total_price_after_tax;
                }

                if ($pos_rent->payment_type == 'Cash') {
                    $cash_num++;
                    $newMap[$pos_rent->cashier_email]['cash'] += $pos_rent->total_price_after_tax;
                } elseif ($pos_rent->payment_type == 'Credit Card') {
                    $cc_num++;
                    $newMap[$pos_rent->cashier_email]['cc'] += $pos_rent->total_price_after_tax;
                } elseif ($pos_rent->payment_type == 'paypal') {
                    $paypal_num++;
                    $newMap[$pos_rent->cashier_email]['pp'] += $pos_rent->total_price_after_tax;
                } elseif ($pos_rent->payment_type == 'coupon') {
                    $coupon_num++;
                    $newMap[$pos_rent->cashier_email]['coupon'] += $pos_rent->total_price_after_tax;
                    $count += 1;
                }

            }
//            if(!empty($pos_rent->extra_cashier_email)) {
//                if (!array_key_exists($pos_rent->extra_cashier_email, $map)) {
//                    $map[$pos_rent->extra_cashier_email] = $pos_rent->extra_service_total_after_tax;
//                } else {
//                    $map[$pos_rent->extra_cashier_email] += $pos_rent->extra_service_total_after_tax;
//                }
//                if (!array_key_exists($pos_rent->cashier_email, $manager_map)) {
//                    $manager_map[$pos_rent->cashier_email] = 1;
//                } else {
//                    $manager_map[$pos_rent->cashier_email] += 1;
//                }
//            }

//            if(!empty(trim($pos_rent->extra_cashier_email))) {
            if(strlen(trim($pos_rent->extra_cashier_email))>0 && $pos_rent->extra_order_completed==1) {
//                $count += 1;

                if (!array_key_exists($pos_rent->extra_cashier_email, $map)) {
                    $map[$pos_rent->extra_cashier_email] = $pos_rent->extra_service_total_after_tax;
                } else {
                    $map[$pos_rent->extra_cashier_email] += $pos_rent->extra_service_total_after_tax;
                }
                if (!array_key_exists($pos_rent->cashier_email, $manager_map)) {
                    $manager_map[$pos_rent->cashier_email] = 1;
                } else {
                    $manager_map[$pos_rent->cashier_email] += 1;
                }

                if ($pos_rent->extra_service_payment_type == 'Cash') {
                    $cash_num++;
                    $newMap[$pos_rent->extra_cashier_email]['cash'] += $pos_rent->extra_service_total_after_tax;
                } elseif ($pos_rent->extra_service_payment_type == 'Credit Card') {
                    $cc_num++;
                    $newMap[$pos_rent->extra_cashier_email]['cc'] += $pos_rent->extra_service_total_after_tax;
                } elseif ($pos_rent->extra_service_payment_type == 'paypal') {
                    $paypal_num++;
                    $newMap[$pos_rent->extra_cashier_email]['pp'] += $pos_rent->extra_service_total_after_tax;
                } elseif ($pos_rent->extra_service_payment_type == 'coupon') {
                    $coupon_num++;
                    $newMap[$pos_rent->extra_cashier_email]['coupon'] += $pos_rent->extra_service_total_after_tax;
                    $count += 1;
                }
            }

            if(!empty(trim($pos_rent->returned_cashier)) && $pos_rent->returned_order_completed==1) {
                $count += 1;

                if (!array_key_exists($pos_rent->returned_cashier, $map)) {
                    $map[$pos_rent->returned_cashier] = $pos_rent->returned_total-$pos_rent->returned_change;
                } else {
                    $map[$pos_rent->returned_cashier] += $pos_rent->returned_total-$pos_rent->returned_change;
                }

                if ($pos_rent->returned_payment_type == 'Cash') {
                    $cash_num++;
                    $newMap[$pos_rent->returned_cashier]['cash'] += $pos_rent->returned_total-$pos_rent->returned_change;
                } elseif ($pos_rent->returned_payment_type == 'Credit Card') {
                    $cc_num++;
                    $newMap[$pos_rent->returned_cashier]['cc'] += $pos_rent->returned_total-$pos_rent->returned_change;
                } elseif ($pos_rent->returned_payment_type == 'paypal') {
                    $paypal_num++;
                    $newMap[$pos_rent->returned_cashier]['pp'] += $pos_rent->returned_total-$pos_rent->returned_change;
                } elseif ($pos_rent->returned_payment_type == 'coupon') {
                    $coupon_num++;
                    $newMap[$pos_rent->returned_cashier]['coupon'] += $pos_rent->returned_total-$pos_rent->returned_change;
                    $count += 1;
                }
            }

//            if (!empty($pos_rent->late_fee)) {
//
//                if (!array_key_exists($pos_rent->returned_cashier, $map)) {
//                    $map[$pos_rent->returned_cashier] = $pos_rent->late_fee;
//                } else {
//                    $map[$pos_rent->returned_cashier] += $pos_rent->late_fee;
//                }
//
//            }
        }

        foreach ($pos_tours as $pos_tour){
            if(strlen(trim($pos_tour->cashier_email))>0 && $pos_tour->order_completed=='1') {
                if (!array_key_exists($pos_tour->cashier_email, $map)) {
                    $map[$pos_tour->cashier_email] = $pos_tour->total_price_after_tax;
                } else {
                    $map[$pos_tour->cashier_email] += $pos_tour->total_price_after_tax;
                }

                if ($pos_tour->payment_type == 'Cash') {
                    $cash_num++;
                    $newMap[$pos_tour->cashier_email]['cash'] += $pos_tour->total_price_after_tax;
                } elseif ($pos_tour->payment_type == 'Credit Card') {
                    $cc_num++;
                    $newMap[$pos_tour->cashier_email]['cc'] += $pos_tour->total_price_after_tax;
                } elseif ($pos_tour->payment_type == 'paypal') {
                    $paypal_num++;
                    $newMap[$pos_tour->cashier_email]['pp'] += $pos_tour->total_price_after_tax;
                } elseif ($pos_tour->payment_type == 'coupon') {
                    $coupon_num++;
                    $newMap[$pos_tour->cashier_email]['coupon'] += $pos_tour->total_price_after_tax;
                    $count += 1;
                }

                if (strlen(trim($pos_tour->extra_cashier_email))>0 && $pos_tour->extra_order_completed=='1') {

                    if (!array_key_exists($pos_tour->extra_cashier_email, $map)) {
                        $map[$pos_tour->extra_cashier_email] = $pos_tour->extra_service_total_after_tax;
                    } else {
                        $map[$pos_tour->extra_cashier_email] += $pos_tour->extra_service_total_after_tax;
                    }

                    if ($pos_tour->extra_service_payment_type == 'Cash') {
                        $cash_num++;
                        $newMap[$pos_tour->extra_cashier_email]['cash'] += $pos_tour->extra_service_total_after_tax;
                    } elseif ($pos_tour->extra_service_payment_type == 'Credit Card') {
                        $cc_num++;
                        $newMap[$pos_tour->extra_cashier_email]['cc'] += $pos_tour->extra_service_total_after_tax;
                    } elseif ($pos_tour->extra_service_payment_type == 'paypal') {
                        $paypal_num++;
                        $newMap[$pos_tour->extra_cashier_email]['pp'] += $pos_tour->extra_service_total_after_tax;
                    } elseif ($pos_tour->extra_service_payment_type == 'coupon') {
                        $coupon_num++;
                        $newMap[$pos_tour->extra_cashier_email]['coupon'] += $pos_tour->extra_service_total_after_tax;
                        $count += 1;
                    }
                }
            }
        }

//        foreach ($sport_sales as $sport_sale){
//            if ($sport_sale->payment_type == 'Cash') {
////                    $sport_num++;
//                $newMap[$sport_sale->cashier_email]['sports'] += $sport_sale->total_price_after_tax;
//            } elseif ($sport_sale->payment_type == 'Credit Card') {
////                    $sport_num++;
//                $newMap[$sport_sale->cashier_email]['sports'] += $sport_sale->total_price_after_tax;
//            }
//
//            if (!array_key_exists($sport_sale->cashier_email, $map)) {
//                $map[$sport_sale->cashier_email] = $sport_sale->total_price_after_tax;
//            } else {
//                $map[$sport_sale->cashier_email] += $sport_sale->total_price_after_tax;
//            }
//        }


//        dd($newMap['xdrealmadrid@gmail.com']['cash']);
        return view('bigbike/agent/agent/pos-cashier-summary',['map'=>$map,'name_map'=>$name_map,'type'=>$type,'data'=>$data,'manager_map'=>$manager_map,
            'newMap'=>$newMap]);
    }

    public function showPosCashierMoreDetail($email,$type,$data){

        $result = $this->getDate($type, $data);
        $year = $result['year'];
        $month = $result['month'];
        $day = $result['day'];
        $day2 = $result['day2'];

        $user = DB::table('users')->where('email', $email)->first();
        $users = DB::table('users')->get();

        $name_map = array();

        foreach ($users as $userr){
            $name_map[$userr->email] = $userr->first_name." ".$userr->last_name;
//            $name_map[$user->first_name." ".$user->last_name] = $user->email;
        }


        if($result['day_end'] && $result['day2_start'] && $result['nextMonth']){

            $day2_start = $result['day2_start'];
            $nextMonth = $result['nextMonth'];

            $pos_rents = DB::table('pos_rents_orders')
                ->where('order_completed', 1)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', '>=', $day)
                ->whereDay('created_at', '<=', $result['day_end'])
                ->get();

            $pos_tours = DB::table('pos_tours_orders')
                ->where('order_completed', 1)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', '>=', $day)
                ->whereDay('created_at', '<=', $result['day_end'])
                ->get();


            $pos_rents2 = DB::table('pos_rents_orders')
                ->where('order_completed', 1)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $nextMonth)
                ->whereDay('created_at', '>=', $day2_start)
                ->whereDay('created_at', '<=', $result['day2'])
                ->get();

            $pos_tours2 = DB::table('pos_tours_orders')
                ->where('order_completed', 1)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $nextMonth)
                ->whereDay('created_at', '>=', $day2_start)
                ->whereDay('created_at', '<=', $result['day2'])
                ->get();

            $sport_sales = DB::table('inventory_sales')
//                ->where('location', Session::get('location'))
//            ->where('cashier_email', Auth::user()->email)
                ->where('order_completed', '1')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', '>=', $day)
                ->whereDay('created_at', '<=', $result['day_end'])
                ->get();

            $sport_sales2 = DB::table('inventory_sales')
//                ->where('location', Session::get('location'))
//            ->where('cashier_email', Auth::user()->email)
                ->where('order_completed', '1')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $nextMonth)
                ->whereDay('created_at', '>=', $day2_start)
                ->whereDay('created_at', '<=', $result['day2'])
                ->get();

            $pos_rents = $pos_rents->merge($pos_rents2);
            $pos_tours = $pos_tours->merge($pos_tours2);
            $sport_sales = $sport_sales->merge($sport_sales2);


        }else{

            $pos_rents = DB::table('pos_rents_orders')
                ->where('order_completed', 1)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', '>=', $day)
                ->whereDay('created_at', '<=', $day2)
                ->get();

            $pos_tours = DB::table('pos_tours_orders')
                ->where('order_completed', 1)
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', '>=', $day)
                ->whereDay('created_at', '<=', $day2)
                ->get();

            $sport_sales = DB::table('inventory_sales')
//                ->where('location', Session::get('location'))
//            ->where('cashier_email', Auth::user()->email)
                ->where('order_completed', '1')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', '>=', $day)
                ->whereDay('created_at', '<=', $day2)
                ->get();

        }

        $sum = 0;

        $cash_num = 0;
        $cc_num = 0;
        $paypal_num = 0;
        $coupon_num = 0;
        $sport_num = 0;

        $cash_sum = 0;
        $cc_sum = 0;
        $paypal_sum = 0;
        $coupon_sum = 0;
        $sport_sum = 0;

        $ins_num = 0;
        $ins_sum = 0;
        $basket_num = 0;
        $basket_sum = 0;
        $dropoff_num = 0;
        $dropoff_sum = 0;
        $latefee_num = 0;
        $latefee_sum = 0;
        $deposit_num = 0;
        $deposit_sum = 0;

        $manager_map = array();
        $manager_array = array();
//        $test_map = array();

        $count = 0;
        foreach ($pos_rents as $pos_rent){
            if($pos_rent->cashier_email==$email && $pos_rent->order_completed==1) {
                if ($pos_rent->payment_type == 'Cash') {
                    $cash_num++;
                    $cash_sum += $pos_rent->total_price_after_tax;
//                    array_push($test_map, $pos_rent->total_price_after_tax);
                } elseif ($pos_rent->payment_type == 'Credit Card') {
                    $cc_num++;
                    $cc_sum += $pos_rent->total_price_after_tax;
                } elseif ($pos_rent->payment_type == 'paypal') {
                    $paypal_num++;
                    $paypal_sum += $pos_rent->total_price_after_tax;
                } elseif ($pos_rent->payment_type == 'coupon') {
                    $coupon_num++;
                    $coupon_sum += $pos_rent->total_price_after_tax;
                    $count += 1;
                }

                $sum += $pos_rent->total_price_after_tax;


                $ins_num += $pos_rent->insurance * $pos_rent->total_bikes;
                $ins_sum += $pos_rent->insurance * 2 * $pos_rent->total_bikes;
//                $sum += $pos_rent->insurance * 2 * $pos_rent->total_bikes;

                $basket_num += $pos_rent->basket;
                $basket_sum += $pos_rent->basket;
//                $sum += $pos_rent->basket;

                $dropoff_num += $pos_rent->dropoff * $pos_rent->total_bikes;
                $dropoff_sum += $pos_rent->dropoff * 5 * $pos_rent->total_bikes;
//                $sum += $pos_rent->dropoff * 5 * $pos_rent->total_bikes;
            }

//            if (!empty($pos_rent->late_fee) && $pos_rent->returned_cashier==$email) {
//                $latefee_num++;
//                $latefee_sum += $pos_rent->late_fee;
////                $sum += $pos_rent->late_fee;
//
//            }

            if (!empty($pos_rent->deposit) && $pos_rent->deposit != 'ID' && $pos_rent->returned_cashier==$email) {
                $deposit_num++;
                $deposit_sum += floatval($pos_rent->deposit);
            }


            //extra
            if(!empty($pos_rent->extra_service_payment_type) && $pos_rent->extra_cashier_email==$email && $pos_rent->extra_order_completed==1){
//                $count += 1;
                if($pos_rent->extra_service_payment_type=='Cash'){
                    $cash_num++;
                    $cash_sum += $pos_rent->extra_service_total_after_tax;
//                    array_push($test_map, $pos_rent->extra_service_total_after_tax);

                }elseif ($pos_rent->extra_service_payment_type=='Credit Card'){
                    $cc_num++;
                    $cc_sum += $pos_rent->extra_service_total_after_tax;
                }elseif ($pos_rent->extra_service_payment_type=='coupon'){
                    $coupon_num++;
                    $coupon_sum += $pos_rent->extra_service_total_after_tax;
                }

                $sum += $pos_rent->extra_service_total_after_tax;
            }

            if(!empty($pos_rent->extra_service_payment_type) && $pos_rent->extra_order_completed==1 ) {
                if (!array_key_exists($pos_rent->cashier_email, $manager_map)) {
                    $manager_map[$pos_rent->cashier_email]['times'] = 1;
                    $manager_map[$pos_rent->cashier_email]['amount'] = $pos_rent->extra_service_total_after_tax;

                } else {
                    $manager_map[$pos_rent->cashier_email]['times'] += 1;
                    $manager_map[$pos_rent->cashier_email]['amount'] += $pos_rent->extra_service_total_after_tax;

                }
                array_push($manager_array, $pos_rent);
            }

                //late fee
            if(!empty($pos_rent->returned_payment_type) && $pos_rent->returned_cashier==$email && $pos_rent->returned_order_completed==1){
//                dd($agent_rent->returned_payment_type);
//                $count += 1;

                if($pos_rent->returned_payment_type=='Cash'){
                    $cash_num++;
                    $cash_sum += $pos_rent->returned_total-$pos_rent->returned_change;
//                    array_push($test_map, $pos_rent->returned_total);

                }elseif ($pos_rent->returned_payment_type=='Credit Card'){
                    $cc_num++;
                    $cc_sum += $pos_rent->returned_total-$pos_rent->returned_change;
                }
                $sum += $pos_rent->returned_total-$pos_rent->returned_change;
                $latefee_num++;
                $latefee_sum += $pos_rent->returned_total-$pos_rent->returned_change;
            }
        }


        foreach ($pos_tours as $pos_tour){

            if($pos_tour->cashier_email==$email && $pos_tour->order_completed ==1) {
                if ($pos_tour->payment_type == 'Cash') {
                    $cash_num++;
                    $cash_sum += $pos_tour->total_price_after_tax;
//                    array_push($test_map, $pos_tour->total_price_after_tax);

                } elseif ($pos_tour->payment_type == 'Credit Card') {
                    $cc_num++;
                    $cc_sum += $pos_tour->total_price_after_tax;
                } elseif ($pos_tour->payment_type == 'paypal') {
                    $paypal_num++;
                    $paypal_sum += $pos_tour->total_price_after_tax;
                } elseif ($pos_tour->payment_type == 'coupon') {
                    $coupon_num++;
                    $coupon_sum += $pos_tour->total_price_after_tax;
                }
                $sum += $pos_tour->total_price_after_tax;
            }
            //extra
            if(!empty($pos_tour->extra_service_payment_type) && $pos_tour->extra_cashier_email==$email && $pos_tour->extra_order_completed ==1){
                if($pos_tour->extra_service_payment_type=='Cash'){
                    $cash_num++;
                    $cash_sum += $pos_tour->extra_service_total_after_tax;
//                    array_push($test_map, $pos_tour->extra_service_total_after_tax);

                }elseif ($pos_tour->extra_service_payment_type=='Credit Card'){
                    $cc_num++;
                    $cc_sum += $pos_tour->extra_service_total_after_tax;
                }
                $sum += $pos_tour->extra_service_total_after_tax;
            }
        }

        foreach ($sport_sales as $sport_sale){
            if($sport_sale->order_completed == 1 && $sport_sale->cashier_email==$email) {
                if ($sport_sale->payment_type == 'Cash' ) {
                    $sport_num++;
                    $sport_sum += $sport_sale->total_price_after_tax;
                    $cash_sum += $sport_sale->total_price_after_tax;

                } elseif ($sport_sale->payment_type == 'Credit Card') {
                    $sport_num++;
                    $sport_sum += $sport_sale->total_price_after_tax;
                }
                $sum += $sport_sale->total_price_after_tax;
                //ALEX
            }
        }

//        array_push($test_map, $cash_sum);

//        dd($test_map);

        return view('bigbike/agent/agent/pos-cashier-detail',['type'=>$type,'data'=>$data,'deposit_sum'=>$deposit_sum,'deposit_num'=>$deposit_num,'latefee_sum'=>$latefee_sum,
            'latefee_num'=>$latefee_num,'dropoff_sum'=>$dropoff_sum,'dropoff_num'=>$dropoff_num,'basket_sum'=>$basket_sum,'basket_num'=>$basket_num,
            'ins_num'=>$ins_num,'ins_sum'=>$ins_sum,'cash_num'=>$cash_num,'cc_num'=>$cc_num,'paypal_num'=>$paypal_num,'sport_num'=>$sport_num,'sport_sum'=>$sport_sum,
            'coupon_num'=>$coupon_num,'cash_sum'=>$cash_sum,'cc_sum'=>$cc_sum,'paypal_sum'=>$paypal_sum,'coupon_sum'=>$coupon_sum,
            'sum'=>$sum,'name'=>$user->first_name.' '.$user->last_name,'manager_map'=>$manager_map,'manager_array'=>$manager_array,'name_map'=>$name_map,'email'=>$email]);
    }

    public function getDate($type,$data){
        $year = 0;
        $month = 0;
        $day = 0;
        $day2 = 0;
        $day_end = null;
        $day2_start = null;
        $nextMonth = null;
        if($type=='day'){
            $tmp = explode('-', $data);
            $year = $tmp[2];
            $month = $tmp[0];
            $day = $tmp[1];
            $day2 = $tmp[1];
        }elseif($type=='week'){

            $tmp = Carbon::now(); // or $date = new Carbon();
            $tmp->setISODate($tmp->year,$data); // 2016-10-17 23:59:59.000000
            $year = $tmp->year;
            $month = $tmp->month;
            $day = $tmp->startOfWeek()->day; // 2016-10-17 00:00:00.000000
            $day2 = $tmp->endOfWeek()->day; // 2016-10-23 23:59:59.000000

            if($day2<$day){
//                dd('cross');
                $nextMonth = ($month+1)%13;
                if($nextMonth==0) $nextMonth+=1;
                //based on month
                $day_end = Carbon::parse(new Carbon('last day of this month'))->day;
                $day2_start = Carbon::parse(new Carbon('first day of next month'))->day;

            }

        }elseif($type=='month'){
            $tmp = explode('-', $data);
            $year = $tmp[1];
            $month = $tmp[0];
            $time = Carbon::createFromDate($tmp[1],$tmp[0],1);
            $day =  $time->firstOfMonth()->day;
            $day2 = $time->endOfMonth()->day;
        }

        return array('year'=>$year, 'month'=>$month, 'day'=>$day, 'day2'=>$day2, 'day_end'=>$day_end, 'day2_start'=>$day2_start,'nextMonth'=>$nextMonth);
    }


    public function getPosDailyReport(){

        $agents = DB::table('agents')->get();
        $users = DB::table('users')->get();
        $locations = DB::table('locations')->get();

        return view('bigbike/agent/agent/pos-daily',['agents'=>$agents,'users'=>$users,'locations'=>$locations]);

    }

    public function getPosDailyReportDetail(Request $request){

        $pos_rents = DB::table('pos_rents_orders')->where('order_completed', 1);
        $pos_tours = DB::table('pos_tours_orders')->where('order_completed', 1);
        $cashiers = DB::table('users')->get();

        $cashierMap = array();
        foreach ($cashiers as $cashier){
            $cashierMap[$cashier->email] = $cashier->first_name.' '.$cashier->last_name;
        }

        if($request->datepicker){
            $date = explode('/', $request->datepicker);
            $pos_rents->whereYear('created_at', $date[2])
                ->whereMonth('created_at', $date[0])
                ->whereDay('created_at', $date[1]);

            $pos_tours->whereYear('created_at', $date[2])
                ->whereMonth('created_at', $date[0])
                ->whereDay('created_at', $date[1]);
        }

        if($request->receipt){
            $pos_rents->where('barcode', $request->receipt);
            $pos_tours->where('barcode', $request->receipt);

        }

        if($request->location){
            $pos_rents->where('location', $request->location);
            $pos_tours->where('location', $request->location);
        }

        if($request->agent){
            $pos_rents->where('agent_name', $request->agent);
            $pos_tours->where('agent_name', $request->agent);
        }

        if($request->cashier){
            $pos_rents->where('cashier_email', $request->cashier);
            $pos_tours->where('cashier_email', $request->cashier);
        }

        if($request->payment_type){
            $pos_rents->where('payment_type', $request->payment_type);
            $pos_tours->where('payment_type', $request->payment_type);
        }



        $pos_rents = $pos_rents->get();
        $pos_tours = $pos_tours->get();

//        dd($pos_rents);

        return view('bigbike/agent/agent/pos-daily-detail',['pos_rents'=>$pos_rents, 'pos_tours'=>$pos_tours,'cashierMap'=>$cashierMap,'date'=>$request->datepicker]);

    }

    public function posDailyRent($id){

        try{
            $agent_rents_order = DB::table('pos_rents_orders')->where('id', $id)->first();

        }catch(\Exception $exception){
            return redirect()->route('agent.404')->with('error', $exception->getMessage());
        }
        $agent_rents_order = json_decode(json_encode($agent_rents_order), true);

        return view('bigbike/agent/agent/pos-daily-rent',['agent_rents_order'=>$agent_rents_order]);

    }

    public function posDailyTour($id){
        try{
            $agent_tours_order = DB::table('pos_tours_orders')->where('id', $id)->first();

        }catch(\Exception $exception){
            return redirect()->route('agent.404')->with('error', $exception->getMessage());
        }
        $agent_tours_order = json_decode(json_encode($agent_tours_order), true);

        return view('bigbike/agent/agent/pos-daily-tour',['agent_tours_order'=>$agent_tours_order]);
    }

    public function showAgentSearchPage(){
        $agents = DB::table('agents')->where('location',Session::get('location'))->where('active',1)->orderBy('fullname', 'asc')
            ->get();

        return view('bigbike/agent/agent/pos-agent-search',['agents'=>$agents]);
    }

    public function showAgentReport(Request $request){
        $date = explode('/', $request->end_date);
        try{
            $agent_rents_order = DB::table('pos_rents_orders')
                ->whereNotNull('agent_name')
                ->where('order_completed', 1)
                ->where('location', Session::get('location'))
                ->whereYear('created_at', $date[2])
                ->whereMonth('created_at', $date[0])
                ->whereDay('created_at', $date[1])
                ->get();

        }catch(\Exception $exception){
            return redirect()->route('agent.404')->with('error', $exception->getMessage());
        }

        $agents = DB::table('agents')->get();

        $map = array();
        $name_map = array();

        foreach ($agents as $user){
            if($user->location==Session::get('location')) {
                $map[$user->fullname] = array('sum' => 0, 'sum_after' => 0, 'commission' => $user->commission,'commissionFee' => 0, 'nums' => 0, 'name' => $user->fullname, 'id' => $user->id);
            }
        }

        foreach ($agent_rents_order as $pos_rent){

            $sum = $pos_rent->total_price_before_tax;
            if($pos_rent->insurance){
                $sum -= 2*$pos_rent->total_bikes;
            }

            if($pos_rent->basket){
                $sum -= $pos_rent->basket;
            }

            if($pos_rent->dropoff){
                $sum -= 5*$pos_rent->total_bikes;
            }

            if(array_key_exists($pos_rent->agent_name,$map)){
                $map[$pos_rent->agent_name]['sum'] += floatval($sum);
                $map[$pos_rent->agent_name]['sum_after'] += $pos_rent->total_price_before_tax;
                $map[$pos_rent->agent_name]['nums'] +=1 ;
                $map[$pos_rent->agent_name]['commissionFee'] += $sum*floatval($map[$pos_rent->agent_name]['commission'])*0.01 ;

            }
        }

//        dd($map);
//        $agent_rents_order = json_decode(json_encode($agent_rents_order), true);

        return view('bigbike/agent/agent/pos-agent-sum',['map'=>$map,'date'=>$request->end_date]);

    }

//    public function getPosAgentDetail($type, $data){
    public function getPosAgentDetail(Request $request){

        if(isset($request->day_checkbox)){
            $type = 'day';
            $data = $request->datepickerday;
        }elseif(isset($request->week_checkbox)){
            $type = 'week';
            $data = $request->datepickerweek;
        }elseif(isset($request->month_checkbox)){
            $type = 'month';
            $data = $request->datepickermonth;
        }

        $result = $this->getDate2($type, $data);
        $year = $result['year'];
        $month = $result['month'];
        $day = $result['day'];
        $day2 = $result['day2'];

        $commision_rate = 0.5;

        if($result['day_end'] && $result['day2_start'] && $result['nextMonth']){

            $day2_start = $result['day2_start'];
            $nextMonth = $result['nextMonth'];

            $pos_rents = DB::table('pos_rents_orders')
                ->where('order_completed', 1)
//                ->where('agent_name', $request->agent_name)
                ->where('location', Session::get('location'))
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', '>=', $day)
                ->whereDay('created_at', '<=', $result['day_end']);
//                ->get();

            $pos_tours = DB::table('pos_tours_orders')
                ->where('order_completed', 1)
//                ->where('agent_name', $request->agent_name)
                ->where('location', Session::get('location'))
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', '>=', $day)
                ->whereDay('created_at', '<=', $result['day_end']);
//                ->get();

            $pos_rents2 = DB::table('pos_rents_orders')
                ->where('order_completed', 1)
//                ->where('agent_name', $request->agent_name)
                ->where('location', Session::get('location'))
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $nextMonth)
                ->whereDay('created_at', '>=', $day2_start)
                ->whereDay('created_at', '<=', $result['day2']);
//                ->get();

            $pos_tours2 = DB::table('pos_tours_orders')
                ->where('order_completed', 1)
//                ->where('agent_name', $request->agent_name)
                ->where('location', Session::get('location'))
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $nextMonth)
                ->whereDay('created_at', '>=', $day2_start)
                ->whereDay('created_at', '<=', $result['day2']);
//                ->get();

            if($request->agent_name!='All'){
                $pos_rents->where('agent_name', $request->agent_name);
                $pos_tours->where('agent_name', $request->agent_name);
                $pos_rents2->where('agent_name', $request->agent_name);
                $pos_tours2->where('agent_name', $request->agent_name);

                $agent = DB::table('agents')->where("fullname",trim($request->agent_name))->first();
                $commision_rate = $agent->commission/100.00;

            }else{
                $pos_rents->whereNotNull('agent_name');
                $pos_tours->whereNotNull('agent_name');
                $pos_rents2->whereNotNull('agent_name');
                $pos_tours2->whereNotNull('agent_name');

                $commision_rate = 0.5;
            }

            $agent = $request->agent_name=="All"? "":$request->agent_name;

            $pos_rents = $pos_rents->get();
            $pos_tours = $pos_tours->get();
            $pos_rents2 = $pos_rents2->get();
            $pos_tours2 = $pos_tours2->get();

            $pos_rents = $pos_rents->merge($pos_rents2);
            $pos_tours = $pos_tours->merge($pos_tours2);

        }else{
            $pos_rents = DB::table('pos_rents_orders')
                ->where('order_completed', 1)
//                ->where('agent_name', $request->agent_name)
                ->where('location', Session::get('location'))
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', '>=', $day)
                ->whereDay('created_at', '<=', $day2);
//                ->get();
//            dd($pos_rents);

            $pos_tours = DB::table('pos_tours_orders')
                ->where('order_completed', 1)
//                ->where('agent_name', $request->agent_name)
                ->where('location', Session::get('location'))
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereDay('created_at', '>=', $day)
                ->whereDay('created_at', '<=', $day2);
//                ->get();

            if($request->agent_name!='All'){
                $pos_rents->where('agent_name', $request->agent_name);
                $pos_tours->where('agent_name', $request->agent_name);

                $agent = DB::table('agents')->where("fullname",trim($request->agent_name))->first();
                $commision_rate = $agent->commission/100.00;

            }else{
                $pos_rents->whereNotNull('agent_name');
                $pos_tours->whereNotNull('agent_name');
                $commision_rate = 0.5;


            }
            $pos_rents = $pos_rents->get();
            $pos_tours = $pos_tours->get();
//            dd($pos_tours);
        }


        $users = DB::table('agents')->get();

        $map = array();
        $name_map = array();
        $manager_map = array();

        foreach ($users as $user){
            $name_map[$user->id] = $user->fullname;
//            if($user->level<3){
//                $manager_map[$user->email] = $user->first_name." ".$user->last_name;
//            }
        }

        $cashiers = DB::table('users')->get();

        $cashierMap = array();
        foreach ($cashiers as $cashier){
            $cashierMap[$cashier->email] = $cashier->first_name.' '.$cashier->last_name;
        }
        $count = 0;


//        dd($count);


        return view('bigbike/agent/agent/pos-agent-summary',['map'=>$map,'name_map'=>$name_map,'type'=>$type,'data'=>$data,'manager_map'=>$manager_map,'pos_rents'=>$pos_rents,'pos_tours'=>$pos_tours,'cashierMap'=>$cashierMap,"commision_rate"=>$commision_rate]);


    }

    public function getDate2($type,$data){
        $year = 0;
        $month = 0;
        $day = 0;
        $day2 = 0;
        $day_end = null;
        $day2_start = null;
        $nextMonth = null;
        if($type=='day'){
            $tmp = explode('/', $data);
            $year = $tmp[2];
            $month = $tmp[0];
            $day = $tmp[1];
            $day2 = $tmp[1];
        }elseif($type=='week'){

            $tmp = Carbon::now(); // or $date = new Carbon();
            $tmp->setISODate($tmp->year,$data); // 2016-10-17 23:59:59.000000
            $year = $tmp->year;
            $month = $tmp->month;
            $day = $tmp->startOfWeek()->day; // 2016-10-17 00:00:00.000000
            $day2 = $tmp->endOfWeek()->day; // 2016-10-23 23:59:59.000000

            if($day2<$day){
//                dd('cross');
                $nextMonth = ($month+1)%13;
                if($nextMonth==0) $nextMonth+=1;
                //based on month
                $day_end = Carbon::parse(new Carbon('last day of this month'))->day;
                $day2_start = Carbon::parse(new Carbon('first day of next month'))->day;

            }

        }elseif($type=='month'){
            $tmp = explode('/', $data);
            $year = $tmp[1];
            $month = $tmp[0];
            $time = Carbon::createFromDate($tmp[1],$tmp[0],1);
            $day =  $time->firstOfMonth()->day;
            $day2 = $time->endOfMonth()->day;

        }

        return array('year'=>$year, 'month'=>$month, 'day'=>$day, 'day2'=>$day2, 'day_end'=>$day_end, 'day2_start'=>$day2_start,'nextMonth'=>$nextMonth);
    }

    public function setAgentPaid(Request $request){
        $rent_ids = explode(",",$request->ids);
        $tour_ids = explode(",",$request->tour_ids);

//        dd($ids);

        for($i=0; $i<count($rent_ids); $i++) {
            $id = $rent_ids[$i];
            if($id > 0) {
                try{
                    DB::table('pos_rents_orders')
                        ->where('id', $id)
                        ->update(['agent_paid' => 1]);

                }catch(\Exception $exception){
                    return 'update not success';
                }
            }
        }


        for($j=0; $j<count($tour_ids); $j++) {
            $tour_id = $tour_ids[$j];
            if($tour_id > 0) {
                try{
                    DB::table('pos_tours_orders')
                        ->where('id', $tour_id)
                        ->update(['agent_paid' => 1]);


                }catch(\Exception $exception){
                    return 'update not success';
                }
            }
        }
        return 'update success';
    }



    public function sendDailyEmail(){
        try {


      $getEmailsfromcustomers = DB::table('pos_rents_orders')
            ->select('customer_email','customer_name', 'customer_lastname','location')
            ->whereRaw('Date(served_date) = CURDATE()')
            ->where('served', 1)
            ->whereNotNull('customer_email')
            ->where('customer_name', '<>', 'Di')
            ->get()
            ->toArray();

//            $json_or = json_decode(json_encode($getEmailsfromcustomers), true);
//        session(['cust_info'=>$getEmailsfromcustomers]);
//            $emails = ['tester@blahdomain.com', 'anotheremail@blahdomian.com'];
//            Mail::send('emails.lead', ['name' => $name, 'email' => $email, 'phone' => $phone], function ($message) use ($request, $emails)
//            {
//                $message->from('no-reply@yourdomain.com', 'Joe Smoe');
////            $message->to( $request->input('email') );
//                $message->to( $emails);
//                //Add a subject
//                $message->subject("New Email From Your site");
//            });

        foreach($getEmailsfromcustomers as $shet){
            echo $shet->customer_email.' ';
            echo $shet->customer_name.' '.$shet->customer_lastname.' ';
            echo $shet->location.'<br> ';
        }
            session(['cust_info'=>$getEmailsfromcustomers]);

            return view('emails.dailymail');

        }
        catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Sorry the emails were not send');
        }
    }
//

    public function phoneReservation(Request $request){
        session(['phoneReservation'=>'phoneReservation']);
        return view('bigbike.agent.phoneReservation.checkout');

    }
    public function check_conf(Request $request){


        try {
            $trans_fast = DB::table('phone_reservation')->select('price','email','description','customer_name','price')->where('confirmation', $request->reference)->get()->toArray();
        }
        catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Sorry this number not exists');
        }

        return redirect()->route('invoice.conf')->with('invoice_conf', $trans_fast);

    }

    public function put_conf($num_get){

        $par =  DB::table('phone_reservation')->select('price','email','description','customer_name','price')->where('tok', $num_get)->where('order_id', NULL)->get()->toArray();
if($par){
    session(['id_trans'=>$num_get]);

   try {
     $price_get =  DB::table('phone_reservation')->select('price')->where('tok', $num_get)->value('price');
   }
   catch (\Exception $exception){
       return redirect()->back()->with('error', 'Please try again');

   }

    session(['net_price'=>$price_get]);

    session(['invoice_conf'=>$par]);
    return view('bigbike.agent.phoneReservation.checkoutInvoice');
}
        else{
            return view('errors.404');
        }

    }

    public function checkoutInvoice(Request $request){

        if($request->session()->has('id_trans')) {
            $data = $this->makePPPmt($request);
//        dd($ac->makePPPmt($request));

//        dd($data);
            if (array_key_exists('message', $data)) {
//            CREDIT_CARD_CVV_CHECK_FAILED
//            if($data->{'message'}=='Credit card was refused.'){
//                session(['error'=>'Credit card was refused.']);
//            }else if($data->{'message'}=='Credit card CVV check failed.'){
//                session(['error'=>'Credit card CVV check failed.']);
//            }
                session(['error' => $data->{'message'}]);

//            return redirect()->back()->with('error', 'Transaction was not completed, please make sure the form is correct and try again.');
                return redirect()->back();

            }

            if ($data->{'state'} == 'approved') {

                try {
                    DB::table('phone_reservation')->where('tok', session('id_trans'))->update([
                        'customer_name' => $request->cc_firstname, 'customer_lastname' => $request->cc_lastname, 'email' => $request->cc_email, 'phone' => $request->cc_phone
                        , 'description' => $request->description, 'price' => session('net_price'), 'order_id' => $data->id, 'completed_at' => date("Y-m-d H:i:s"), 'served_by' => 'Automatically payment'
                    ]);
                    $phone_id = DB::table('phone_reservation')->where('tok', session('id_trans'))->get();
                } catch (\Exception $exception) {
                    return redirect()->back()->with('error', $exception->getMessage());
                }
            } else {
                return redirect()->back()->with('error', "credit card was declined, please make sure that you use correct number");
            }
            session(['rent_success' => 'success']);
            session(['phone_id' => $phone_id]);


            $agent_rents_order = json_decode(json_encode($phone_id), true);

            return view('bigbike.agent.phoneReservation.receipt_invoice', ['agent_rents_order' => $agent_rents_order[0]]);
//        return view('bigbike.agent.phoneReservation.receipt_invoice', ['agent_rents_order'=>$phone_id[0],'location'=>'203 W 58th st',]);

        }
        else {
            return view('errors.404');

        }

    }




    public function createInvoice(){
        session(['phoneReservation'=>'phoneReservation']);

        return view('bigbike.agent.phoneReservation.create_invoice');

    }
    public function invoiceForm(Request $request){
        try {
            $uniq_id = uniqid();
            $phone_id = DB::table('phone_reservation')->insertGetId([
                    'customer_name' => $request->ogranization, 'email' => $request->email, 'description' => $request->description, 'price' => $request->price, 'completed_at' => date("Y-m-d H:i:s"), 'served_by' => 'Reservation Manager', 'tok' => $uniq_id
                ]);
            $id = DB::getPdo()->lastInsertId();

            DB::table('phone_reservation')->where('id', $id)->update(['confirmation' => $id . 'CBR314']);
            $id_select = DB::table('phone_reservation')->select('confirmation')->where('id', $id)->value('confirmation');


                $body = '<body>
Thank you for choosing Bike Rent NYC.<br>
Your invoice number: '.$id_select.'<br>';
                $body.= '<div> Total price : '.$request->price.'</div>
<div>Please use this link to pay your invoice: <span>https://eastriverparkbikerental.com/invoice/cart='.$uniq_id;
            $body.= '</span> </div>
      </body>';

                $mail = new PHPMailer;

                $mail->SMTPDebug = 0;                               // Enable verbose debug output

                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'smtpout.secureserver.net';  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'vouchers@bikerent.nyc';                 // SMTP username
                $mail->Password = 'bike#88%_#';                           // SMTP password
                $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 465;                                    // TCP port to connect to

                $mail->setFrom('vouchers@bikerent.nyc', 'Bike Rent NYC Invoice');
                $mail->addAddress($request->email);     // Add a recipient
                $mail->AddBCC('s.tcukanov@gmail.com', 'Bike Rent NYC');

                $mail->isHTML(true);                                  // Set email format to HTML

                $mail->Subject = 'Bike Rent NYC Invoice';
                $mail->Body =  $body;
                if(!$mail->send())
                {
                    return redirect()->back()->with('error', 'Sorry the mail was not send, please try again');
                }

//        $this->sendCustomerEmail($email2, $agent_rents_order);


        }
        catch (\Exception $exception) {
            return redirect()->route('agent.phoneReservation')->with('error', $exception->getMessage());
        }
//
////        Session::flash(['invoice_id'=>$id_select]);
        session()->flash('invoice_id', $id_select);
        return redirect()->back()->with('success', 'Thank you email was created and sent');

    }


    public function phoneReservationCheckout(Request $request){
        if(Session::token() != $request->_token){
            return redirect()->route('agent.phoneReservation',['error'=>'token not valid, please redo the transaction']);
        }
        session(["net_price"=>$request->price]);
        $data = $this->makePPPmt($request);
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

            return redirect()->route('agent.phoneReservation');
        }

        if($data->{'state'}=='approved') {

            try{
                $phone_id = DB::table('phone_reservation')->insertGetId([
                        'customer_name' => $request->cc_firstname,'customer_lastname' => $request->cc_lastname,'email' => $request->cc_email,'phone'=>$request->cc_phone
                        , 'description' => $request->description,'price'=>($request->price),'order_id'=>$data->id,'completed_at' => date("Y-m-d H:i:s"),'served_by'=>($request->served)
                    ]);
            }catch(\Exception $exception){
                return redirect()->route('agent.phoneReservation')->with('error', $exception->getMessage());
            }
        }else{
            return redirect()->route('agent.phoneReservation')->with('error', "credit card is declined");
        }
        session(['phone_id'=>$phone_id]);



//        $this->sendAgentEmail(Auth::user()->email,$agent_rents_order);

        return redirect()->route('agent.phonerentReceipt');

    }

    public function sendCustomerEmail($email2, $agent_rents_order){

//        $data = array('name' => 'Bigbike', 'msg' => 'Order Confirmation',
//            'email'=>$agent_rents_order->email,'completed_at'=>$agent_rents_order->completed_at,
//            'price'=>$agent_rents_order->price,
//            'customer_name'=>$agent_rents_order->customer_name,'customer_lastname'=>$agent_rents_order->customer_lastname);

        $data = array('agent_rents_order'=>$agent_rents_order[0]);

//        Mail::send('emails.order-customer-email', $data, function ($message) use($email,$pdf) {
        //if($type=='agent')


//        Mail::send('emails.phone', $data, function ($message) use($email2) {
//
//
//            $message->from('vouchers@bikerent.nyc', 'Bike Rent NYCC Receipt');
//            $message->to('alex@bikerent.nyc')->subject('Bike Rent NYC');
//            $message->bcc('marketing@bikerent.nyc', 'Bike Rent NYC');
//            $message->bcc('s.tcukanov@gmail.com', 'Bike Rent NYC');
//
//
//
//
//
////            $message->attach($pdf->output(),['filename.pdf']);
//            //$message->attachData($data, ['invoice.pdf']);
//
//        });
    }

    public function searchCustomer(){


        return view('bigbike.agent.searchCusPage');

    }

    public function getCustomerInfo(Request $request){
        if($request->type=='Rent'){
            $user = DB::table('pos_rents_orders');


            if(strlen(trim($request->first_name))!=0){
                $user->where('customer_name', $request->first_name);
            }
            if(strlen(trim($request->last_name))!=0){
                $user->where('customer_lastname', $request->last_name);
            }

            $user = $user->where('date', $request->datepickerday)->first();

            if($user==null){
                $nothing = "nothing";
            }else{
                $nothing = "else";

            }

            $user = json_decode(json_encode($user), true);

            return view('bigbike.agent.searchCusResult', ['agent_rents_order'=>$user,'info'=>$nothing]);
        }else{
            $user = DB::table('pos_tours_orders');


            if(strlen(trim($request->first_name))!=0){
                $user->where('customer_name', $request->first_name);
            }
            if(strlen(trim($request->last_name))!=0){
                $user->where('customer_lastname', $request->last_name);
            }

            $user = $user->where('date', $request->datepickerday)->first();

            if($user==null){
                $nothing = "nothing";
            }else{
                $nothing = "else";
            }

            $user = json_decode(json_encode($user), true);

            return view('bigbike.agent.searchCusTourResult', ['agent_tours_order'=>$user,'info'=>$nothing]);

        }

    }

    public function charityReport(){

    }

    public function tripAdvisorReport(){

        $locations = ["203W 58th Street","117W 58th Street","40W 55th Street","145 Nassau Street"];
        $values = array();

        //dd('here');
        foreach ($locations as $location) {
            $agent_rents_orders = DB::table('pos_rents_orders')
//            ->whereNotNull('agent_name')
                ->where('order_completed', 1)
                ->where('location', $location)
                ->where('payment_type', 'paypal')

                //            ->whereYear('created_at', $now->year)
//            ->whereMonth('created_at', $now->month)
//            ->whereDay('created_at', $now->day)
                ->whereBetween('created_at', ['2018-03-25 00:00:00', '2018-05-06 23:59:59'])
//            ->whereBetween('created_at', ['2018-01-23 00:00:00', '2018-03-10 11:59:59'])
                ->get();
            DB::table('trip')->insert(
                ['firstname' => $location]
            );
//            DB::table('trip')->insert(
//                ['firstname' => "rent"]
//            );
            foreach ($agent_rents_orders as $order){
                //dd($order->customer_email);
                DB::table('trip')->insert(
                    ['firstname' => $order->customer_name, 'lastname' => $order->customer_lastname,
                        'email' => $order->customer_email,'date' => $order->created_at,'type' => "rent"]
                );
            }
//            dd($agent_rents_order);
            //array_push($values, $agent_rents_order);
            //$values[$locations]['rent'] = $agent_rents_order;
            $agent_tours_order = DB::table('pos_tours_orders')
//            ->whereNotNull('agent_name')
                ->where('order_completed', 1)
                ->where('location', $location)
                ->where('payment_type', 'paypal')
                //            ->whereYear('created_at', $now->year)
//            ->whereMonth('created_at', $now->month)
//            ->whereDay('created_at', $now->day)
                ->whereBetween('created_at', ['2018-03-25 00:00:00', '2018-04-22 23:59:59'])
//            ->whereBetween('created_at', ['2018-01-23 00:00:00', '2018-03-10 11:59:59'])
                ->get();
            //array_push($values, $agent_tours_order);
//            DB::table('trip')->insert(
//                ['firstname' => "tour"]
//            );
            foreach ($agent_tours_order as $order){
                DB::table('trip')->insert(
                    ['firstname' => $order->customer_name, 'lastname' => $order->customer_lastname,
                        'email' => $order->customer_email,'date' => $order->created_at,'type' => "tour"]
                );
            }
        }
        //dd($values);
    }

    public function removeOld(Request $request){

        $from = Carbon::createFromFormat('Y-m-d H:m:s', '2018-08-01 00:00:00');
        $from = Carbon::create(2018, 8, 1, 0, 0, 0, 'America/Toronto');
        dd($from);
 //need a space after dates.
        $to = Carbon::create(2018, 8, 1, 23, 59, 59, 'America/Toronto');
        dd($to);

        DB::table('pos_rents_orders')
            ->where("location","Central Park West")
            ->whereNull('returned')
            ->orWhere("returned",0)
            ->whereNull("returned_date")
            ->whereNull("returned_cashier")
            ->where("served",1)
            ->whereNull("served_date")
            ->whereBetween('served_date', [$from, $to])
//            ->whereYear('created_at', $year)
//            ->whereMonth('created_at', $month)
//            ->whereDay('created_at', '>=', $day)
//            ->whereDay('created_at', '<=', $result['day_end'])
        ->update(['returned' => 1,"returned_date"=>date("Y-m-d H:i:s"),"returned_cashier"=>"bermudezcrystal@ymail.com"]);

    }

    public function countSightseeing(Request $request){

//        $date = "May/2018";
        $cntMap = array('Brooklyn Bridge 12 hour bike rental (8 AM-8PM)' => 0,
            'Brooklyn Bridge 12 hour bike rental (8 AM-8PM) Child' => 0,
            'Brooklyn Bridge Bike Tour - 2 hours' => 0,
            'Brooklyn Bridge Bike Tour - 2 hours Child' => 0,
            'Central Park 12 hour bike rental (8 AM-8 PM)' => 0,
            'Central Park 12 hour bike rental (8 AM-8 PM) Child' => 0,
            'Central Park Bike Tour - 2 hours' => 0,
            'Central Park Bike Tour - 2 hours Child' => 0,
            'Central Park Bike Tour - 2 hours Child' => 0,
            'Central Park Walking Tour' => 0,
            'Central Park Walking Tour Child' => 0
        );
        $date = "10-2018";
//        $file = fopen('redeemed 01-05-2018 - 31-05-2018.csv', 'r');
//        if($name==""){
            $file = fopen('redeemed '.$date.'.csv', 'r');
//        }
        while (($line = fgetcsv($file)) !== FALSE) {
//            dd($line);
            if($line[19]==1){
                $cntMap[$line[6]]++;
            }elseif ($line[21]==1){
                //child
                $cntMap[$line[6]." Child"]++;

            }
        }
        fclose($file);
        return view("bigbike.admin.sightseeing_report",['cntMap'=>$cntMap,'date'=>$date]);
//        dd($cntMap);
    }

    public function phonerentReceipt(Request $request){

//        if(!Session::has('phone_id')){
//            session(['error'=>'session expired']);
//            return redirect()->route('agent.phoneReservation');
//        }

        $caisher_name = DB::table('users')->where('email', Session::get('cashierEmail'))->first();
        $agent_rents_order = DB::table('phone_reservation')->where('id', Session::get('phone_id'))->get();
        $email2=$agent_rents_order[0]->email;

        $location = DB::table('locations')->where('title', Session::get('location'))->first();

        $body = '<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="http://tickets.bikerent.nyc/css/agent-order.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
</head>
    <style>
        table{}
        table{border-collapse:collapse !important;margin-bottom:5px}
        .content {width: 100%; max-width: 600px;}
        .content img { height: auto; min-height: 1px; }

        #bodyTable{margin:0; padding:0; width:100% !important;}
        #bodyCell{margin:0; padding:0;}
        #bodyCellFooter{margin:0; padding:0; width:100% !important;padding-top:39px;padding-bottom:15px;}
        body {margin: 0; padding: 0; min-width: 100%!important;text-align: center}
        .box-wid{max-width:600px;}
        #templateContainerHeader{
            font-size: 14px;
            padding-top:2.429em;
        }
        hr.style9 {
            border-top: 1px dashed #8c8b8b;
            border-bottom: 1px dashed #fff;
        }
        .marg{
            margin-left: 20px;
            margin-right: 20px;
            font-size: 14px;
        }
        .final{
            line-height: 22px;
        }
        #templateContainerImageFull { border-left:1px solid #e2e2e2; border-right:1px solid #e2e2e2; }
        #templateContainerFootBrd{
            border-bottom:1px solid #e2e2e2;
            border-left:1px solid #e2e2e2;
            border-right:1px solid #e2e2e2;
            border-radius: 0 0 4px 4px;
            background-clip: padding-box;
            border-spacing: 0;
            height: 10px;
            width:100% !important;
        }
        #templateContainer{
            border-top:1px solid #e2e2e2;
            border-left:1px solid #e2e2e2;
            border-right:1px solid #e2e2e2;
            border-radius: 4px 4px 0 0 ;
            background-clip: padding-box;
            border-spacing: 0;
        }
        #templateContainerMiddle {
            border-left:1px solid #e2e2e2;
            border-right:1px solid #e2e2e2;
        }
        #templateContainerMiddleBtm {
            border-left:1px solid #e2e2e2;
            border-right:1px solid #e2e2e2;
            border-bottom:1px solid #e2e2e2;
            border-radius: 0 0 4px 4px;
            background-clip: padding-box;
            border-spacing: 0;
        }

    </style>
<body>
        <div class="text-center col-md-12" >

            <br>
            <table width="100%" bgcolor="#ffffff" border="0" cellpadding="10" cellspacing="0">
                <tr>
                    <td>
                        <!--[if (gte mso 9)|(IE)]>
                        <table width="600"  align="center" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td>
                        <![endif]-->
                        <table bgcolor="#ffffff" class="content"  style="margin:0 auto" align="center" cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td valign="top" mc:edit="headerBrand" id="templateContainerHeader">

                                    <p style="text-align:center;margin:0;padding:0;">
                                        <img src="https://bikerent.nyc/wp-content/uploads/2015/05/Bike-Rent-NYC_png-800x.png" width="150px" style="display:inline-block; />
                        </p>

                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateContainer">
                                            <tr>
                                                <td valign="top" class="bodyContent" mc:edit="body_content_01">
                                                    <h1 style=" margin-bottom: -10px;text-align: center" ><strong>BIKE RENTALS & BIKE TOURS</strong></h1>
                                                </td>
                                            </tr>
                                            </td>
                                            </tr>
                                            <tr>
                                            <tr>
                                                <td><p style="text-align: center"><strong><br><br>Central Park Bike Tours<br> address: 203W 58th Street,<br> New York, NY 10019 <br>phone: (212) 541-8759<br>email: marketing@bikerent.nyc
                                            <br><br>
                                    </p></td></tr>
                            <td align="center" valign="top">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateContainerImageFull" style="min-height:15px;">
                                    <tr>
                                        <td valign="top" class="bodyContentImageFull" mc:edit="body_content_01">
                                            <p style="text-align:center;margin:0;padding:0;float:right;">
                                                <img src="https://eastriverparkbikerental.com/images/banner-email.jpg" style="display:block; margin:0; padding:0; border:0;" />
                                            </p>
                                        </td>
                                    </tr>
                                </table>                        </table>

                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top">
                        <!-- BEGIN BODY // -->
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateContainerMiddle" class="brdBottomPadd">
                            <tr>
                                <td valign="top" style="text-align: center" class="bodyContent" mc:edit="body_content">';
        $body .='<h3><p class="final"><strong>THANK YOU FOR CHOOSING OUR COMPANY</strong></h3>';

        $body .=  'Served by: '.$agent_rents_order[0]->served_by.'<br><br>';
        $body .=       ' Created at:'.$agent_rents_order[0]->completed_at.'<br>';
        $body .=    'Order number: <strong>PHR'.$agent_rents_order[0]->id .'</strong><br>';

        $body .=   'Name: '. $agent_rents_order[0]->customer_name.' '. $agent_rents_order[0]->customer_lastname. '<br>';
        if ($agent_rents_order[0]->phone !=NULL){
            $body .= 'Phone Number:'.$agent_rents_order[0]->phone.'<br>';
        }
        $body.= 'Email: ' .$agent_rents_order[0]->email.'<br>';

        if ($agent_rents_order[0]->description!=NULL){

            $body .=  'Description: '.$agent_rents_order[0]->description.'<br>';
        }
        $body .=  '<br>Total after Tax:<strong> $'.number_format(floatval($agent_rents_order[0]->price),2).'</strong></p><br><td>
</tr>
                        </table>

                                <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateContainerMiddle" class="brdBottomPadd box-wid">
                                    <td valign="top" class="bodyContent" ><br><br>
                                        <p class="marg box-wid"><strong>Activity</strong>:
                                            I have chosen to rent and participate in bike rental services (hereinafter referred to
                                            as “the Activity”, which is organized by Central Park Bike Tours (hereinafter referred
                                            to as “CPBT”) I understand that the Activity is inherently hazardous and I may be exposed
                                            to dangers and hazards, including some of the following: falls, injuries associated with a
                                            fall, injuries from lack of fitness, death, equipment failures and negligence of others.
                                            As a consequence of these risks, I may be seriously hurt or disabled or may die from the
                                            resulting injuries and my property may also be damaged.
                                            In consideration of the permission to participate in the Activity, I agree to the terms
                                            contained in this contract. I agree to follow the rules and directions for the Activity,
                                            including any New York State traffic laws and
                                            park rules</p>

                                    </td></tr>
                                    <tr>
                                        <td>
                                            <p class="marg box-wid"><strong>Liability:</strong>
                                                All adult customers assume full liability and ride at their own risk. If you feel that you
                                                or anyone in your party cannot operate a bicycle safely and competently, that person should
                                                not rent or ride a bicycle. All children are to be supervised at all times by their parents
                                                or an adult over the age of 18.
                                                Children under the age of 14 must wear a helmet pursuant to New York State Law.
                                                With the purchase of bicycle services, you hereby release and hold harmless from all
                                                liabilities, causes of action, claims and demands that may arise in any way from injury,
                                                death, loss or harm that may occur. This release does not extend to any claims for gross
                                                negligence, intentional or reckless misconduct.
                                                I acknowledge that CPBT has no control over and assumes no responsibility for the actions
                                                of any independent contractors providing any services
                                                for the Activity
                                            </p>
                                        </td>
                                    </tr>
                                    <tr><td><p class="marg box-wid">
                                                <strong>Bike Rental Insurance:</strong>
                                                Bike rental insurance is available at additional cost. Customers who had purchased Bike
                                                Rental Insurance are indemnified and protected against 50% of the cost of damages and
                                                repairs; Customers are not responsible for costs of repairs to damages bicycles during
                                                normal use, wear and tear, lost or stolen bicycle when they purchase Bike Rental Insurance.
                                                Bike Rental Insurance does not indemnify for any cost or liability that arises as a result
                                                of personal injury, coverage shall apply only to property damage. Bike Rental Insurance
                                                includes damaged bike-pick up within Central Park only.</p>
                                        </td> </tr>


                                    </tr>
                                </table>
                                <!-- // END BODY -->
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top">
                                    <!-- BEGIN BODY // -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" id="templateContainerMiddleBtm">
                                        <td valign="top" class="bodyContentImage">
                                            <tr>

                                                <td width="15" align="left" valign="top" style="width:15px;margin:0;padding:0;">&nbsp;</td>

                                            </tr>
                                    </table>
                                    <!-- // END BODY -->
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top" id="bodyCellFooter" class="unSubContent">
                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" id="templateContainerFooter">
                                        <tr>
                                            <td valign="top" width="100%" mc:edit="footer_unsubscribe">

                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        </table>
                        <!--[if (gte mso 9)|(IE)]>
                        </td>
                        </tr>

                        </table>

                        </div>
                            <![endif]-->

                    </td>
                </tr>
            </table>';

//
//                                </td>


        $mail = new PHPMailer;

        $mail->SMTPDebug = 0;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtpout.secureserver.net';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'vouchers@bikerent.nyc';                 // SMTP username
        $mail->Password = 'bike#88%_#';                           // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->setFrom('vouchers@bikerent.nyc', 'Bike Rent NYC Receipt');
        $mail->addAddress($email2);     // Add a recipient
        $mail->AddBCC('s.tcukanov@gmail.com', 'Bike Rent NYC');
        $mail->AddBCC('marketing@bikerent.nyc', 'Bike Rent NYC');




        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Bike Rental Receipt';
        $mail->Body =  $body;
        if(!$mail->send())
        {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
        else
        {
            echo "Message has been sent successfully";
        }
//        $this->sendCustomerEmail($email2, $agent_rents_order);


        session(['rent_success'=>'Order completed!']);
        $agent_rents_order = json_decode(json_encode($agent_rents_order), true);

        return view('bigbike.agent.phoneReservation.receipt', ['agent_rents_order'=>$agent_rents_order[0],'location'=>$location,'caisher_name'=>$caisher_name->first_name.' '.$caisher_name->last_name]);

    }
}
