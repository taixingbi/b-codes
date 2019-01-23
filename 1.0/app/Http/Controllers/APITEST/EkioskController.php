<?php

namespace App\Http\Controllers\APITEST;

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
class EkioskController extends Controller{
    public function getRentIndex(){


        $agent_rent_table= DB::table('agent_rents')->get();


        $data = array();
        $messages[0]['code'] = 200;
        $messages[0]['message'] = 'Success';
        $data['messages'] = $messages;

//        $data['data']['title'] = $agent_rent_table->title;
        $cnt = 0;
        foreach ($agent_rent_table as $list){
            $data['data'][$list->id] = $list;
//            $cnt++;
        }

//        $data['data']['access_token'] = $tmp[0];
//        $data['data']['refresh_token'] = $tmp[1];

        return response(\GuzzleHttp\json_encode((object) $data), 200)
            ->header('Content-Type', 'application/json');

        //eturn view('bigbike.agent.rent-main',['agent_rent_table'=>$agent_rent_table,'memberships'=>$memberships,'agents'=>$agents,'agent_rents_order'=>null,'agent_rents_order_cc'=>$agent_rents_order_cc,'user'=>$user]);

    }
}