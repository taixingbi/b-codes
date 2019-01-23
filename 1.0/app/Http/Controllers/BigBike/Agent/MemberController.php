<?php

namespace App\Http\Controllers\BigBike\Agent;

use App\Http\Controllers\Controller;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use Illuminate\Support\Facades\DB;
use Mail;


class MemberController extends Controller
{

    public function showMemberPage(){

        $memberships= DB::table('member_types')->get();
        $members= DB::table('memberships')->where('order_completed',1)->get();

        return view('bigbike.agent.membership.membership-register',['memberships'=>$memberships,'members'=>$members]);

    }


    public function registerMember(Request $request){

        $arr = explode("/$",$request->member_type);

//        $type = $arr[0];
//        $price = $arr[1];
//        dd($type);

        $type = DB::table('member_types')->where('title', $request->member_type)->first();


//        dd($type);
        if($request->has('credit_card')){
//            $payment_type = $request->credit_card;
            $payment_type = "Credit Card";
            $order_completed = 0;

            if(floatval($request->rent_total_pay)>floatval(session('total_price_after_tax'))){
                //price too low, can not complete the transaction
                session(['rent_price_error' => 'price is too high']);
                return redirect()->route('agent.rentOrder');
            }
        }else{
//            $payment_type = $request->cash;
            $payment_type = 'Cash';

            $order_completed = 1;
            $rent_agent_total_pay = $request->rent_tips_label;

//            if(floatval($request->rent_tips_label) > floatval(session('total_price_after_tax'))*0.3+0.01){
//                //price too low, can not complete the transaction
//                session(['rent_price_error' => 'agent price is too high to complete this order']);
////                session(['rent_price_error' => floatval($request->rent_tips_label)]);
//
//                return redirect()->route('agent.rentOrder');
//            }
        }

        try{
            try{
                $member_id = DB::table('memberships')->insertGetId([
                    'customer_name' => $request->customer_first,'customer_lastname' => $request->customer_last,'customer_email' => $request->customer_email,'member_number'=>$request->member_number,
                    'customer_phone'=>$request->customer_phone,'cashier_email' => Session::get('cashierEmail'), 'member_type'=>$type->title,'payment_type' => $payment_type,'created_at' => date("Y-m-d H:i:s"),
                    'startdate'=>$request->date, 'enddate' => $request->customer_expire_date,'order_completed'=>$order_completed,'price'=>$type->price
                ]);
            }catch(\Exception $exception){
                return redirect()->route('agent.rentOrder')->with('error', $exception->getMessage());
            }
//            DB::table('memberships')
//                ->where('id', $member_id)
//                ->update(['member_number'=>intval($member_id)*100]);

            session(['member_id'=>$member_id]);
        }catch(\Exception $exception){

//            dd($exception);
//            return view('bigbike.agent.membership.membership-register',['success'=>$exception]);
            return redirect()->route('agent.rentOrder')->with('error', $exception->getMessage());
        }

        if($request->has('cash')) {
            session(['success' => 'Membership Registered']);

            return redirect()->route('agent.memberReceipt');
        }
        session(['member' => 'member','member_price'=>$type->price,'rent_edit'=>null,'tour_edit'=>null,'rent'=>null,'tour'=>null,'net_price'=>number_format(($type->price*1.08875),2)]);

//        return view('bigbike.agent.membership.membership-register',['success'=>'success']);
//        session([ 'rent_edit'=>'rent_edit','tour_edit'=>null,'rent'=>null,'tour'=>null]);

        return view('bigbike.agent.cc-checkout',['price'=>number_format(($type->price*1.08875),2),'firstname'=>$request->customer_first,'lastname'=>$request->customer_last]);
    }

    public function memberReceipt(){
        if(!Session::has('member_id')){
            return redirect()->route('agent.404');
        }

        $member= DB::table('memberships')->where('id', session('member_id'))->first();

        $caisher_name = DB::table('users')->where('email', $member->cashier_email)->first();
        $member = json_decode(json_encode($member), true);
        $location = DB::table('locations')->where('title', Session::get('location'))->first();

        session(['rent_success'=>'rent_success']);
        return view('bigbike.agent.receipt.membership',['caisher_name'=>$caisher_name->first_name.' '.$caisher_name->last_name,'member'=>$member,'location'=>$location]);
    }

    public function ppMemberCheckout(Request $request){
        if(!Session::has('member_price')){
            return redirect()->route('agent.404');
        }

        $ac = new AgentController();
        $data = $ac->makePPPmt($request);
//        dd($ac->makePPPmt($request));

//        dd($data);
        if(array_key_exists('message', $data) ) {
//            CREDIT_CARD_CVV_CHECK_FAILED
            if($data->{'message'}=='Credit card was refused.'){
                session(['error'=>'Credit card was refused.']);
            }else if($data->{'message'}=='Credit card CVV check failed.'){
                session(['error'=>'Credit card CVV check failed.']);
            }

            return redirect()->route('agent.rentOrder');
        }

        if($data->{'state'}=='approved') {
            //update db
            $member_id = Session::get('member_id');

            try{
                DB::table('memberships')
                    ->where('id', $member_id)
                    ->update(['order_completed' => 1,'customer_ccname' => $request->cc_firstname,'customer_cclastname' => $request->cc_lastname,'order_id' => $data->id, 'completed_at' => date("Y-m-d H:i:s")]);

            }catch(\Exception $exception){
                return redirect()->route('agent.showMemberPage')->with('error', $exception->getMessage());
            }
        }else{
            return redirect()->route('agent.showMemberPage')->with('error', "credit card is declined");
        }
        Session::forget('member_price');
        //return view('bigbike.agent.agent-rent-receipt',['agent_rents_order'=>$agent_rents_order[0],'rent_success'=>'Order Completed!','barcode'=>Session::pull('order_id')]);
        session(['success' => 'success']);

        return redirect()->route('agent.memberReceipt');
    }


}
