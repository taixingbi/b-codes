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

class BrainTreeController extends Controller
{

//"a","b","c","d","e","f","g","h",
//"i","j","k","l","m","n","o","p",
//"q","r","s","t","u","v","w","x",
//"y","z",

    public function showBTPage(Request $request){
        $sandbox_tokenization_key = env('sandbox_tokenization_key','sandbox_gjk255w5_97fsxd2zqpc7qjsn');

        return view("bigbike.agent.braintree.payment",['sandbox_tokenization_key'=>$sandbox_tokenization_key]);
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
//        dd($data);
//        if(array_key_exists('error_description', $data) ) {
////            CREDIT_CARD_CVV_CHECK_FAILED
//            if($data->{'error_description'}=='Client Authentication failed'){
//                session(['error'=>'Client Authentication failed']);
//            }
//
//            return redirect()->route('agent.rentOrderSubmitGet');
//        }

        return $data->access_token;
    }

    public function makePPPmt(Request $request){

        $token = $this->getPPToken();
//        $tmp = explode('/', $request->cc_expiration);
//        $expire_month = intval(trim($tmp[0]));
//        dd($request->cc_expiration);
//        $expire_year = intval(trim($tmp[1]));
//
//        if(strlen(trim($tmp[1]))==2){
//            $expire_year = 2000+ intval(trim($tmp[1]));
//        }else{
//            $expire_year = intval(trim($tmp[1]));
//        }


        $expire_year = $request->cc_exp_year;
        $expire_month = $request->cc_exp_month;

        if(strlen(trim($expire_year))==2){
            $expire_year = 2000+ intval(trim($expire_year));
        }else{
            $expire_year = intval(trim($expire_year));
        }


//        dd($expire_year);

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
//                        "total"=> $request->cc_amount,
//                        "total"=> Session::get('net_price'),
                        "total"=> $tmp,

//                        "total"=> '0.10',
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
//        dd($data);

//        if(array_key_exists('message', $data) ) {
////            CREDIT_CARD_CVV_CHECK_FAILED
//            if($data->{'message'}=='Credit card was refused'){
//                session(['error'=>'Credit card was refused']);
//            }else if($data->{'message'}=='Credit card CVV check failed'){
//                session(['error'=>'Credit Card Refused']);
//            }
//
//            return redirect()->route('agent.rentOrderSubmitGet');
//        }


        return $data;
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



    public function phoneReservation(){
        session(['phoneReservation'=>'phoneReservation']);
        return view('bigbike.agent.phoneReservation.checkout');
    }


}
