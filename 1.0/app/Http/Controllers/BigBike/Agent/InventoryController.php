<?php

namespace App\Http\Controllers\BigBike\Agent;

use App\Http\Controllers\Controller;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use Illuminate\Support\Facades\DB;
use Mail;
use GuzzleHttp\Client;
use Log;


class InventoryController extends Controller
{
    protected $location_url = "https://inventory.bikerent.nyc/api/v1/locations";
    protected $location_inventory_url = "https://inventory.bikerent.nyc/api/v1/locations/";
    protected $location_inventory_delete_url = "https://inventory.bikerent.nyc/api/v1/purchase/";
    protected $location_id = 1;
//    protected session(['cart'=>array()]);
    public function main(){
//        Session::forget('inv_cart');
        try{
            $location_inventory = $this->getInventory();
//            dd($location_inventory);
//            dd($location_inventory->stocks);
//            dd($cur_location);
//            dd($locations[$location_obj->inventory_id]);
//            dd($locations[$location_obj->inventory_id]->id);
//            if($locations[$location_obj->inventory_id]["id"]){
//                $inventory_location = $locations[$location_obj->inventory_id];
//            }
//            $location =
//            $bikes = DB::table('bike_inventory')->get();
//            $locations = DB::table('locations')->where('bike_inventory', 1)->get();
//            dd($location_inventory->stocks);
        }catch(\Exception $exception){
            return redirect()->route('agent.rentOrder')->with('error', $exception->getMessage());
        }
        return view('bigbike.agent.inventory.main',['inventories'=>$location_inventory->stocks]);
    }

    public function updateCart2(Request $request)
    {
        if(!Session::has('inv_cart')){
            session(["inv_cart"=>array()]);
        }

        $location_inventory = $this->getInventory();
//        dd($location_inventory);
        //dd($request->id);
//        dd(Session::get("cart"));
//        $inventory_price = 0;
        $p = 0;
        foreach ($location_inventory->stocks as $item){
//            dd($item->title);
//            if($item->status && $item->qty>=$request->qty){
            if($request->id==$item->id){
                if($item->qty>=$request->qty) {
                    Log::info("qty: " . $request->qty);
                    Log::info("status: " . $item->status);
                    Log::info("price: " . $item->price);
//                array_push(Session::get("cart"),[$item->title,$request->qty,$request->price]);
                    $tmp = Session::get("inv_cart");
//                dd($tmp);
                    if (!array_key_exists($item->title, $tmp)) {
//                    dd("empty");
                        $tmp[$item->title] = ["price" => $item->price, "qty" => $request->qty,"id"=>$item->id];
                    } else {
//                    dd("not empty");
                        //                    $tmp[$item->title]["qty"] += $item->qty;
                        $tmp[$item->title]["qty"] += $request->qty;
                    }
                    if (!isset($tmp["price"])) {
                        $tmp["price"] = 0;
                    }
                    $tmp["price"] += floatval($item->price) * intval($request->qty);
                    $p = $tmp["price"];

                    session(['inv_cart' => $tmp]);

//                dd(Session::get("cart"));
                    break;
                }else{
                    $data = array();
                    $messages[0]['code'] = 100;
                    $messages[0]['message'] = 'Quantity is too large';
//                    $messages[0]['price'] = $p;

                    $data['messages'] = $messages;
                    //Log::info("error: ".$validator->errors());
//            Log::info("type: ".type($validator->errors()));

                    $data['data']['flag'] = 1;
//        $data['data']['Error'] = $validator->errors();
//            $data['data']['refresh_token'] = $tmp[1];

                    return response(\GuzzleHttp\json_encode((object) $data), 200)
                        ->header('Content-Type', 'application/json');

                }
            }

        }
//        return Session::get("cart");
//        return "sadf";
//        return $p;
//        $validator->fails()){
        $data = array();
        $messages[0]['code'] = 200;
        $messages[0]['message'] = 'Added';
        $messages[0]['price'] = $p;

        $data['messages'] = $messages;
        //Log::info("error: ".$validator->errors());
//            Log::info("type: ".type($validator->errors()));

        $data['data']['flag'] = 0;
//        $data['data']['Error'] = $validator->errors();
//            $data['data']['refresh_token'] = $tmp[1];

        return response(\GuzzleHttp\json_encode((object) $data), 200)
            ->header('Content-Type', 'application/json');
    }


    public function updateCart(Request $request)
    {
        if(!Session::has('inv_cart')){
            session(["inv_cart"=>array()]);
        }

        $location_inventory = $this->getInventory();
//        dd($location_inventory);
        //dd($request->id);
//        dd(Session::get("cart"));
//        $inventory_price = 0;
        $p = 0;
        foreach ($location_inventory->stocks as $item){
//            dd($item->title);
//            if($item->status && $item->qty>=$request->qty){
            if($request->id==$item->id){
                if($item->qty>=$request->qty) {
                    Log::info("qty: " . $request->qty);
                    Log::info("status: " . $item->status);
                    Log::info("price: " . $item->price);
//                array_push(Session::get("cart"),[$item->title,$request->qty,$request->price]);
                    $tmp = Session::get("inv_cart");
//                dd($tmp);
                    if (!array_key_exists($item->id, $tmp)) {
//                    dd("empty");
                        $tmp[$item->id] = ["price" => $item->price, "qty" => $request->qty,"id"=>$item->id,"title"=>$item->title];
                    } else {
//                    dd("not empty");
                        //                    $tmp[$item->title]["qty"] += $item->qty;
                        $tmp[$item->id]["qty"] += $request->qty;
                    }
                    if (!isset($tmp["price"])) {
                        $tmp["price"] = 0;
                    }
                    $tmp["price"] += floatval($item->price) * intval($request->qty);
                    $p = $tmp["price"];

                    session(['inv_cart' => $tmp]);

//                dd(Session::get("cart"));
                    break;
                }else{
                    $data = array();
                    $messages[0]['code'] = 100;
                    $messages[0]['message'] = 'Quantity is too large';
//                    $messages[0]['price'] = $p;

                    $data['messages'] = $messages;
                    //Log::info("error: ".$validator->errors());
//            Log::info("type: ".type($validator->errors()));

                    $data['data']['flag'] = 1;
//        $data['data']['Error'] = $validator->errors();
//            $data['data']['refresh_token'] = $tmp[1];

                    return response(\GuzzleHttp\json_encode((object) $data), 200)
                        ->header('Content-Type', 'application/json');

                }
            }

        }
//        return Session::get("cart");
//        return "sadf";
//        return $p;
//        $validator->fails()){
        $data = array();
        $messages[0]['code'] = 200;
        $messages[0]['message'] = 'Added';
        $messages[0]['price'] = $p;

        $data['messages'] = $messages;
        //Log::info("error: ".$validator->errors());
//            Log::info("type: ".type($validator->errors()));

        $data['data']['flag'] = 0;
//        $data['data']['Error'] = $validator->errors();
//            $data['data']['refresh_token'] = $tmp[1];

        return response(\GuzzleHttp\json_encode((object) $data), 200)
            ->header('Content-Type', 'application/json');
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

    public function checkout(){
//        dd("here".print_r(Session::get("inv_cart")["price"],true));

        if(Session::get("inv_cart")["price"]==0){
            $this->main();
            //dd("empty");
        }
        //Session::get("inv_cart");
        //dd("here");

//        return view('bigbike.agent.cc-checkout',['price'=>Session::get("inv_cart")["price"]]);
        return view('bigbike.agent.cc-checkout',['price'=>0.00]);

    }

    public function postppCheckout(Request $request){
        if(!Session::has('cashier')){
//            $uc = new UserController();
//            return $uc->getLogout();
            return redirect()->route('user.logout');
        }

        if(!Session::has('inv_cart') || Session::get("inv_cart")["price"]==0){
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
            Session::forget('invent');
            try{
                return $this->updateDBHelper($request);
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


    public function updateDB($firstname,$lastname,$payment_type,$order_id,$refund_id,$other_type){
//        dd("update db");
        try{
            $price = doubleval(Session::get("inv_cart")["price"]);
            $int_id = DB::table('inventory_sales')->insertGetId([
                'location' => session('location'),
                'customer_name' => $firstname,
                'customer_lastname' => $lastname,
                'cashier_email' => Session::get('cashierEmail'), 'order_completed' => 1,
                'payment_type' => $payment_type,
                'order_id' => $order_id,
                'original_price'=>$price,
//                'total_price_before_tax'=>$adjust_price==null?doubleval(session('inventory_total_before_tax')):$adjust_price,
//                'total_price_after_tax' => $adjust_price==null?doubleval(session('inventory_total')):number_format($adjust_price*(1.08875),2),
                'total_price_before_tax'=>$price,
                'total_price_after_tax' =>$price,

                'rendered_cash'=>0,'created_at'=>date("Y-m-d H:i:s"),
//                'comment' => $request->comment,
//                'adjust_price'=>$adjust_price,
                'comment' => "",
                'adjust_price'=>0,

//                'adjust_percentage'=>$request->rent_discount,
//                'sequantial'=>strtoupper($table).$num
                'adjust_percentage'=>0,
                'sequantial'=>0

            ]);

            try{
                $oldCart = Session::get('inv_cart');
//                $cart = new Cart($oldCart);
//                $products = $cart->items;
//                foreach ($products as $item) {
//                    DB::table('inventory_sale_item')->insert([
//                        'sale_id' => $int_id, 'name' => $item['item'], 'quantity' => $item['qty'],'cashier_email'=>Session::get('cashierEmail'),
//                        'price' => $item['price'],'size'=>$item['size'],'created_at'=>date("Y-m-d H:i:s"),'location'=>session('location')
//                    ]);
//                }
                $client = new Client();

                foreach ($oldCart as $key=> $value){
                    if($key!="price"){

                        $response = $client->request('POST', $this->location_inventory_delete_url, [
                            'form_params' => [
                                'stock_id' => $value['id'],
                                'location_id' => $this->location_id,
                                'quantity' => trim($value['qty'])
                            ]
                        ]);
                        $code = $response->getStatusCode(); // 200
                        $reason = $response->getReasonPhrase(); // OK
                        $body = $response->getBody();
                        $tenBytes = $body->read(36);
                        if($tenBytes=="purchase sucessful reduced stock qty"){
//                        dd("here");
                            Log::info("inventory delete success");
                        }else{
                            Log::info("inventory delete failed");

                        }

                        DB::table('inventory_sale_item')->insert([
//                            'sale_id' => $int_id, 'name' => $key, 'quantity' => $value['qty'],'cashier_email'=>Session::get('cashierEmail'),
                            'sale_id' => $int_id, 'name' => $value['title'], 'quantity' => $value['qty'],'cashier_email'=>Session::get('cashierEmail'),
                            'price' => $value['price'],'created_at'=>date("Y-m-d H:i:s"),'location'=>session('location')
                        ]);
                    }
                }

            }catch(\Exception $exception){
                return;
//                return redirect()->route('bigbike.agent.inventory.main')->with('rent_price_error', $exception->getMessage());
            }


        }catch(\Exception $exception){
            return;
//            return redirect()->route('bigbike.agent.inventory.main')->with('rent_price_error', $exception->getMessage());
        }
    }

//    public function updateDBCash($firstname,$lastname,$payment_type="",$order_id,$refund_id){
    public function updateDBCash(Request $request){
        $Cart = Session::get('inv_cart');
        $Cart["firstname"] = $request->firstname;
        $Cart["lastname"] = $request->lastname;
        session(["inv_cart" => $Cart]);
//        dd(Session::get('inv_cart'));
//        dd('dd');
        if($request->has('credit_card')) {
//            dd(Session::get("inv_cart")["price"]);
//            return $this->updateDBHelper($request);
            session(['invent'=>'invent']);
//            dd(Session::get('inventory'));
            return view('bigbike.agent.cc-checkout',['price'=>Session::get("inv_cart")["price"],'firstname'=>$request->firstname,'lastname'=>$request->lastname,'inventory'=>1]);

        }else{
            return $this->updateDBHelper($request);
        }
            //dd("update db");

    }

    public function updateDBHelper(Request $request){

//        dd("here");
        try{
            //delete from inventory
            $price = doubleval(Session::get("inv_cart")["price"]);
            $int_id = DB::table('inventory_sales')->insertGetId([
                'location' => session('location'),'customer_name' => Session::get('inv_cart')["firstname"],'customer_lastname' => Session::get('inv_cart')["lastname"],
                'cashier_email' => Session::get('cashierEmail'), 'order_completed' => 1,
                'payment_type' => "Cash", 'order_id' => null,
                'original_price'=>$price,
//                'total_price_before_tax'=>$adjust_price==null?doubleval(session('inventory_total_before_tax')):$adjust_price,
//                'total_price_after_tax' => $adjust_price==null?doubleval(session('inventory_total')):number_format($adjust_price*(1.08875),2),
                'total_price_before_tax'=>$price,
                'total_price_after_tax' =>$price,

                'rendered_cash'=>0,'created_at'=>date("Y-m-d H:i:s"),
//                'comment' => $request->comment,
//                'adjust_price'=>$adjust_price,
                'comment' => "",
                'adjust_price'=>0,
//                'adjust_percentage'=>$request->rent_discount,
//                'sequantial'=>strtoupper($table).$num
                'adjust_percentage'=>0,
                'sequantial'=>0

            ]);

            try{
                $oldCart = Session::get('inv_cart');
//                $cart = new Cart($oldCart);
//                $products = $cart->items;
//                foreach ($products as $item) {
//                    DB::table('inventory_sale_item')->insert([
//                        'sale_id' => $int_id, 'name' => $item['item'], 'quantity' => $item['qty'],'cashier_email'=>Session::get('cashierEmail'),
//                        'price' => $item['price'],'size'=>$item['size'],'created_at'=>date("Y-m-d H:i:s"),'location'=>session('location')
//                    ]);
//                }
                $client = new Client();
                foreach ($oldCart as $key=> $value){
//        $res = $client->get($this->location_inventory_delete_url);

//            echo $res->getStatusCode(); // 200

//            echo $res->getBody(); // { "type": "User", ....
//        $locations = json_decode($res->getBody());
//            $request = $client->post($this->location_inventory_delete_url,
//                ['body' => json_encode(
//                    [
//                        'stock_id' => $value['id'],
//                        'location_id' => $this->location_id,
//                        'quantity' => trim($value['qty'])
//                    ]
//                )]);
//

//        $request->setBody($data); #set body!
//                    $response = $request->send();
//                    dd($response);
                    if($key!="price" && $key!="firstname" && $key!="lastname"){


                        $response = $client->request('POST', $this->location_inventory_delete_url, [
                            'form_params' => [
                                'stock_id' => $value['id'],
                                'location_id' => $this->location_id,
                                'quantity' => trim($value['qty'])
                            ]
                        ]);
                        $code = $response->getStatusCode(); // 200
                        $reason = $response->getReasonPhrase(); // OK
                        $body = $response->getBody();
                        $tenBytes = $body->read(36);
                        if($tenBytes=="purchase sucessful reduced stock qty"){
//                        dd("here");
                            Log::info("inventory delete success");
                        }else{
                            Log::info("inventory delete failed");

                        }


//                        dd($value['qty']);
                        DB::table('inventory_sale_item')->insert([
                            'sale_id' => $int_id, 'name' => $key, 'quantity' => intval($value['qty']),'cashier_email'=>Session::get('cashierEmail'),
                            'price' => doubleval($value['price']),'product_id'=>intval($value['id']),'created_at'=>date("Y-m-d H:i:s"),'location'=>session('location')
                        ]);
                    }
                }

            }catch(\Exception $exception){

                return redirect()->route('agent.inventory.main')->with('rent_price_error', $exception->getMessage());
            }
        }catch(\Exception $exception){

            return redirect()->route('agent.inventory.main')->with('rent_price_error', $exception->getMessage());
        }
        //print receipt

        return redirect()->route('agent.inventory.receipt');
    }

    public function receipt(){
        $location = DB::table('locations')->where('title', Session::get('location'))->first();
        $caisher_name = DB::table('users')->where('email', Auth::user()->email)->first();
        session(["inventory_success"=>true]);
        return view('bigbike.agent.inventory.receipt', ['agent_rents_order' => "", 'caisher_name'=>$caisher_name->first_name.' '.$caisher_name->last_name,'user'=>$caisher_name,'location'=>$location]);

    }

    public function updateQTY(Request $request){

        $oldCart = Session::get('inv_cart');

        $old_qty = 0;
        $price = 0;
        foreach ($oldCart as $key=> $value){

            if($key==$request->id){
                //update item qty
                $old_qty = $value['qty'];
                $price = $value["price"];

//                if($old_qty==intval($request->qty)){
                if(0==intval($request->qty)){

                        //
//                    dd("same old key:".$key);
                    unset($oldCart[$key]);

                }else {
//                    $value['qty'];
                    $oldCart[$key]["qty"] = intval($request->qty);
//                    dd($oldCart[$key]["qty"]);
                }
//                dd($price*($old_qty-intval($request->qty)));
//                dd($oldCart);
                $oldCart["price"] -= $price*($old_qty-intval($request->qty));
                session(["inv_cart" => $oldCart]);
//                dd(Session::get("inv_cart")["price"]);
                break;
            }
        }
        //update total price
//        Session::get('inv_cart')["price"] -= $price*($old_qty-intval($request->qty));
        return redirect()->route('agent.inventory.main');

    }

    public function purchase(){
        $client = new Client();
//        $request = $client->request($this->location_inventory_delete_url,
//            ['body' => json_encode(
//                [
////                    'stock_id' => $value['id'],
////                    'location_id' => $this->location_id,
////                    'quantity' => trim($value['qty'])
//                    'stock_id' => 46,
//                    'location_id' => 1,
//                    'quantity' => trim(1)
//                ]
//            )]);

        $response = $client->request('POST', $this->location_inventory_delete_url, [
            'form_params' => [
                'stock_id' => 46,
                'location_id' => 1,
                'quantity' => trim(1)
            ]
        ]);
        $code = $response->getStatusCode(); // 200
        $reason = $response->getReasonPhrase(); // OK
        $body = $response->getBody();
        $tenBytes = $body->read(36);
        if($tenBytes=="purchase sucessful reduced stock qty"){
//            dd("here");
        }else{

        }
//        dd($tenBytes);
//        dd($code);
//        dd($response->getBody());

//        $locations = json_decode($res->getBody());
//        Log::info(print_r($response->getBody(),true));
    }


    public function clearCart(){
        $tmp = Session::get("inv_cart");
        Session::forget('inv_cart');
        $tmp = null;
        $tmp = array();
        $tmp["price"] = 0.00;
        session(['inv_cart' => $tmp]);
        return redirect()->route('agent.inventory.main');
    }

}
