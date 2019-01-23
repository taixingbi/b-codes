<?php

namespace App\Http\Controllers\BigBike\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Stripe\Charge;
use Stripe\Stripe;
use Auth;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{

    public function getMainPage(){
        return view('bigbike.admin.admin');
    }

    public function getReport(){

        $agent_list = DB::table('users')->get();

        return view('bigbike.admin.report',['agent_list'=>$agent_list]);
    }

    public function getAgentReport(Request $request){
        $date = explode('/', $request->admin_date);

        $agent_rent_cc = DB::table('agent_rents_orders')
            ->where('agent_email', $request->admin_agent)
            ->where('payment_type', 'credit_card')
            ->where('order_completed', 1)
            ->whereYear('created_at', '=', $date[1])
            ->whereMonth('created_at', '=', $date[0])
            ->sum('total_price_after_tax');

        $agent_rent_cash = DB::table('agent_rents_orders')
            ->where('agent_email', $request->admin_agent)
            ->where('payment_type', 'cash')
            ->whereYear('created_at', '=', $date[1])
            ->whereMonth('created_at', '=', $date[0])
            ->sum('total_price_after_tax');

        $agent_tour_cc = DB::table('agent_tours_orders')
            ->where('agent_email', $request->admin_agent)
            ->where('payment_type', 'credit_card')
            ->where('order_completed', 1)
            ->whereYear('created_at', '=', $date[1])
            ->whereMonth('created_at', '=', $date[0])
            ->sum('total_price_after_tax');

        $agent_tour_cash = DB::table('agent_tours_orders')
            ->where('agent_email', $request->admin_agent)
            ->where('payment_type', 'cash')
            ->whereYear('created_at', '=', $date[1])
            ->whereMonth('created_at', '=', $date[0])
            ->sum('total_price_after_tax');


//        $agents = DB::table('agent_rents_orders')->where('agent_email', $request->admin_agent)->where('payment_type', $payment)->get();
//        $agents = DB::table('agent_rents_orders')->where('agent_email', $request->admin_agent)->where('payment_type', '')->get();
//        return view('bigbike.admin.agent_report',['agent_cc'=>$agent_cc, 'agent_cash'=>$agent_cash,'date'=>$request->admin_date]);
        return ['agent_rent_cc'=>$agent_rent_cc, 'agent_rent_cash'=>$agent_rent_cash,'agent_tour_cc'=>$agent_tour_cc, 'agent_tour_cash'=>$agent_tour_cash,'date'=>$request->admin_date];
    }

    public function getMonthForm(){
        return view('bigbike/admin/monthly');
    }

    public function getMonthReport(Request $request){
        $date = explode('/', $request->admin_date);


        $agent_cc_rents = DB::table('agent_rents_orders')
//            ->select('agent_email',DB::raw('total_price_after_tax'))
            ->where('payment_type', 'credit_card')
            ->where('order_completed', 1)
            ->whereYear('created_at', '=', $date[1])
            ->whereMonth('created_at', '=', $date[0])
//            ->groupBy('agent_email')
            ->get();

        $agent_cc_tours = DB::table('agent_tours_orders')
//            ->select('agent_email',DB::raw('total_price_after_tax'))
            ->where('payment_type', 'credit_card')
            ->where('order_completed', 1)
            ->whereYear('created_at', '=', $date[1])
            ->whereMonth('created_at', '=', $date[0])
//            ->groupBy('agent_email')
            ->get();

        $cc_arr = [];
        foreach($agent_cc_rents as $agent_cc_rent){
            if(array_key_exists($agent_cc_rent->agent_email,$cc_arr)){
                $cc_arr[$agent_cc_rent->agent_email] += (float)$agent_cc_rent->total_price_after_tax;
            }else{
                $cc_arr[$agent_cc_rent->agent_email] = (float)$agent_cc_rent->total_price_after_tax;
            }
        }
        foreach($agent_cc_tours as $agent_cc_tour){
            if(array_key_exists($agent_cc_tour->agent_email,$cc_arr)){
                $cc_arr[$agent_cc_tour->agent_email] += (float)$agent_cc_tour->total_price_after_tax;
            }else{
                $cc_arr[$agent_cc_tour->agent_email] = (float)$agent_cc_tour->total_price_after_tax;
            }
        }


        $agent_cash_rents = DB::table('agent_rents_orders')
            ->select('agent_email',DB::raw('SUM(total_price_after_tax) as total_price_after_tax'),DB::raw('SUM(agent_price_after_tax) as agent_price_after_tax'))
            ->where('payment_type', 'cash')
            ->whereYear('created_at', '=', $date[1])
            ->whereMonth('created_at', '=', $date[0])
            ->groupBy('agent_email')
            ->get();

        $agent_cash_tours = DB::table('agent_tours_orders')
            ->select('agent_email',DB::raw('SUM(total_price_after_tax) as total_price_after_tax'),DB::raw('SUM(agent_price_after_tax) as agent_price_after_tax'))
            ->where('payment_type', 'cash')
            ->whereYear('created_at', '=', $date[1])
            ->whereMonth('created_at', '=', $date[0])
            ->groupBy('agent_email')
            ->get();

        $cash_arr = [];
        $cash_agent_arr = [];
        foreach($agent_cash_rents as $agent_cash_rent){
            if(array_key_exists($agent_cash_rent->agent_email,$cash_arr)){
                $cash_arr[$agent_cash_rent->agent_email] += (float)$agent_cash_rent->total_price_after_tax-(float)$agent_cash_rent->agent_price_after_tax;
//                $cash_agent_arr[$agent_cash_rent->agent_email] += (float)$agent_cash_rent->agent_price_after_tax;
            }else {
                $cash_arr[$agent_cash_rent->agent_email] = (float)$agent_cash_rent->total_price_after_tax-(float)$agent_cash_rent->agent_price_after_tax;
//                $cash_agent_arr[$agent_cash_rent->agent_email] = (float)$agent_cash_rent->agent_price_after_tax;
            }
        }
        foreach($agent_cash_tours as $agent_cash_tour){
            if(array_key_exists($agent_cash_tour->agent_email,$cash_arr)){
                $cash_arr[$agent_cash_tour->agent_email] += (float)$agent_cash_tour->total_price_after_tax-(float)$agent_cash_tour->agent_price_after_tax;
//                $cash_agent_arr[$agent_cash_tour->agent_email] += (float)$agent_cash_tour->agent_price_after_tax;

            }else{
                $cash_arr[$agent_cash_tour->agent_email] = (float)$agent_cash_tour->total_price_after_tax-(float)$agent_cash_tour->agent_price_after_tax;
//                $cash_agent_arr[$agent_cash_tour->agent_email] = (float)$agent_cash_tour->agent_price_after_tax;
            }
        }

        return view('bigbike/admin/month-detail',['cc_arr'=>$cc_arr,'cash_arr'=>$cash_arr,'cash_agent_arr'=>$cash_agent_arr,'date'=>$request->admin_date]);
//        return view('bigbike/admin/month-detail');
    }

    public function getAgentMonthlyDetail(Request $request){

        $date = explode('/', $request->date_pay);

        $agent_cc_rents = DB::table('agent_rents_orders')
            ->where('agent_email', $request->agent_pay)
            ->where('payment_type', 'credit_card')
            ->where('order_completed', 1)
            ->whereYear('created_at', '=', $date[1])
            ->whereMonth('created_at', '=', $date[0])->get();

        $agent_cc_tours = DB::table('agent_tours_orders')
            ->where('agent_email', $request->agent_pay)
            ->where('payment_type', 'credit_card')
            ->where('order_completed', 1)
            ->whereYear('created_at', '=', $date[1])
            ->whereMonth('created_at', '=', $date[0])->get();

        $sum = 0;
        $agent_sum = 0;
        foreach ($agent_cc_rents as $agent_cc_rent){
            $sum += (float)$agent_cc_rent->total_price_after_tax;
            $agent_sum += number_format(0.3*(float)$agent_cc_rent->total_price_after_tax,2);
        }

        foreach ($agent_cc_tours as $agent_cc_tour){
            $sum += (float)$agent_cc_tour->total_price_after_tax;
            $agent_sum += number_format(0.3*(float)$agent_cc_tour->total_price_after_tax,2);
        }

        $sum = number_format($sum*0.7,2);

        $agent_cash_rents = DB::table('agent_rents_orders')
            ->where('agent_email', $request->agent_pay)
            ->where('payment_type', 'cash')
            ->whereYear('created_at', '=', $date[1])
            ->whereMonth('created_at', '=', $date[0])->get();

        $agent_cash_tours = DB::table('agent_tours_orders')
            ->where('agent_email', $request->agent_pay)
            ->where('payment_type', 'cash')
            ->whereYear('created_at', '=', $date[1])
            ->whereMonth('created_at', '=', $date[0])->get();

        $cash_sum = 0;
        foreach ($agent_cash_rents as $agent_cash_rent){
            $cash_sum += (float)$agent_cash_rent->total_price_after_tax;
        }

        foreach ($agent_cash_tours as $agent_cash_tour){
            $cash_sum += (float)$agent_cash_tour->total_price_after_tax;
        }

        return view('bigbike/admin/agent-detail',['agent_cc_rents'=>$agent_cc_rents, 'agent_cc_tours'=>$agent_cc_tours,
            'agent_cash_rents'=>$agent_cash_rents, 'agent_cash_tours'=>$agent_cash_tours,'sum'=>$sum,'agent_sum'=>$agent_sum,
            'date_pay'=>$request->date_pay, 'cash_sum'=>$cash_sum,'agent_pay'=>$request->agent_pay]);
    }



}
