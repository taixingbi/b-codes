<?php

namespace App\Http\Controllers\BigBike\Agent;

use App\Http\Controllers\Controller;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use Illuminate\Support\Facades\DB;
use Mail;
use Carbon\Carbon;


class ClockController extends Controller
{

    public function main(){
        try{
            $bikes = DB::table('bike_inventory')->get();
            $locations = DB::table('locations')->where('bike_inventory', 1)->get();

        }catch(\Exception $exception){
            return redirect()->route('agent.rentOrder')->with('error', $exception->getMessage());
        }
        //test controller
        return view('bigbike.agent.bike.main',['bikes'=>$bikes,'locations'=>$locations]);
    }

//    public function update($name)
    public function clockin($name)
    {

        $error_msg = "error, try agian";
        if($name=="Unknown") return $error_msg;

        try {
            $user = DB::table('clock')->where('name', $name)->orderBy('in_time','desc')->first();
            if($user===null){
                try{
                    DB::table('clock')->insert(
                        ['name' => $name,'cur_date'=>date("Y-m-d"),'in_time' => date("Y-m-d H:i:s")]
                    );

                }catch(\Exception $exception){
                    return $error_msg;
                }
                return $name." clock in at ".(string)date("Y-m-d H:i:s");

            }
            if($user->out_time!=null){
                try{
                    DB::table('clock')->insert(
                        ['name' => $name, 'cur_date'=>date("Y-m-d"),'in_time' => date("Y-m-d H:i:s")]
                    );

                }catch(\Exception $exception){
                    return $error_msg;
                }
            }else{

                return " already clock in at ";
                return $name." alrea at ".$user->in_time;
                return $name." alreck in at ".$user->in_time;
                return $name." already  in at ".$user->in_time;
                return $name."  in at ".$user->in_time;

            }


        }catch(\Exception $exception){
            return $error_msg;
        }

        return $name." clock in at ".(string)date("Y-m-d H:i:s");
    }

    public function clockout($name){

        $error_msg = "error, try agian";
        if($name=="Unknown") return $error_msg;

        try {
            $user = DB::table('clock')->where('name', $name)->orderBy('in_time','desc')->first();
            if($user===null){
                return $name." is a new user, please clock in first";
            }
            if($user->out_time!=null){
                return $name." already clock out at ".$user->out_time;
            }else{

                $start = date_create($user->in_time);
                $end = date_create(date("Y-m-d H:i:s"));
                $diff=date_diff($end,$start)->format('%i');


                try{
                    DB::table('clock')
                        ->where('id', $user->id)
                        ->update(['out_time' => date("Y-m-d H:i:s"),'mins'=>$diff,'hours'=>$diff/60.0]);

                }catch(\Exception $exception){
                    return $exception->getMessage();
                }
            }

        }catch(\Exception $exception){
            return $error_msg;
        }
        return $name." clock out at ".(string)date("Y-m-d H:i:s");
    }

    public function clocksystemMain(){
        $users = DB::table('clock_employees')->get();

        return view('bigbike/agent/clock/main',['users'=>$users]);

    }

    public function getClockSummary(Request $request){

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

        if($result['day_end'] && $result['day2_start'] && $result['nextMonth']){

            $day2_start = $result['day2_start'];
            $nextMonth = $result['nextMonth'];

            $pos_rents = DB::table('clock')
                ->where('name', $request->agent_name)
//                ->where('agent_name', $request->agent_name)
                ->whereYear('in_time', $year)
                ->whereMonth('in_time', $month)
                ->whereDay('in_time', '>=', $day)
                ->whereDay('in_time', '<=', $result['day_end']);
//                ->get();

            $pos_rents2 = DB::table('clock')
                ->where('name', $request->agent_name)
                ->whereYear('in_time', $year)
                ->whereMonth('in_time', $nextMonth)
                ->whereDay('in_time', '>=', $day2_start)
                ->whereDay('in_time', '<=', $result['day2']);
//                ->get();

            if($request->agent_name!='All'){
                $pos_rents->where('name', $request->agent_name);
                $pos_rents2->where('name', $request->agent_name);
            }else{
                $pos_rents->whereNotNull('name');
                $pos_rents2->whereNotNull('name');
            }

            $pos_rents = $pos_rents->get();
            $pos_rents2 = $pos_rents2->get();

            $pos_rents = $pos_rents->merge($pos_rents2);

        }else{
            $pos_rents = DB::table('clock')
                ->where('name', $request->agent_name)
                ->whereYear('in_time', $year)
                ->whereMonth('in_time', $month)
                ->whereDay('in_time', '>=', $day)
                ->whereDay('in_time', '<=', $day2);
//                ->get();
//            dd($pos_rents);


            if($request->agent_name!='All'){
                $pos_rents->where('name', $request->agent_name);
            }else{
                $pos_rents->whereNotNull('name');

            }
            $pos_rents = $pos_rents->get();
//            dd($pos_tours);
        }


//        $users = DB::table('users')->get();

        $map = array();
        $name_map = array();
        $manager_map = array();

//        foreach ($users as $user){
//            $name_map[$user->id] = $user->fullname;
////            if($user->level<3){
////                $manager_map[$user->email] = $user->first_name." ".$user->last_name;
////            }
//        }

        $cashiers = DB::table('users')->get();

        $cashierMap = array();
        foreach ($cashiers as $cashier){
            $cashierMap[$cashier->email] = $cashier->first_name.' '.$cashier->last_name;
        }
        $count = 0;


//        dd($count);
        return view('bigbike/agent/clock/summary',['map'=>$map,'name_map'=>$name_map,'type'=>$type,'data'=>$data,'manager_map'=>$manager_map,'pos_rents'=>$pos_rents,'cashierMap'=>$cashierMap]);
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

    public function add(Request $request){
        try{
            DB::table('clock_employees')->insert(
                ['firstname' => $request->rent_customer, 'lastname'=>$request->rent_customer_last,'fullname'=>$request->rent_customer." ".$request->rent_customer_last,'phone' => $request->rent_phone]
            );

        }catch(\Exception $exception){
            session(['error' => $exception->getMessage()]);
            return redirect()->route("agent.clockMain");
        }
        session(['error' => "Add Successfully"]);
        return redirect()->route("agent.clockMain");
    }

    public function noIdeal(Request $request){

        try{
            $test = DB::table('test')->get();
        }catch (\Exception $e){
            Log::info("error: ".$e->getMessage());
        }

    }

}
