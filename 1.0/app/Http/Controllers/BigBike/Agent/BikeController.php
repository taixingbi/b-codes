<?php

namespace App\Http\Controllers\BigBike\Agent;

use App\Http\Controllers\Controller;
use Dompdf\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use Illuminate\Support\Facades\DB;
use Mail;


class BikeController extends Controller
{

    public function main(){
        try{
            $bikes = DB::table('bike_inventory')->get();
            $locations = DB::table('locations')->where('bike_inventory', 1)->get();

        }catch(\Exception $exception){
            return redirect()->route('agent.rentOrder')->with('error', $exception->getMessage());
        }
        return view('bigbike.agent.bike.main',['bikes'=>$bikes,'locations'=>$locations]);
    }

    public function update($qrcode)
    {
        try {
            $bike = DB::table('bike_inventory')->where('qrcode', $qrcode)->first();

        }catch(\Exception $exception){
            return "error";
        }

        $curTime = date_create('now');
        $diff = date_diff($curTime, date_create($bike->date));
        $status = $bike->status;

        $min = $diff->d * 24 * 60 + $diff->h * 60 + $diff->i;
        if ($min < 15) {
            return "less";
        } else {
            try {
                DB::table('bike_inventory')
                    ->where('qrcode', $qrcode)
                    ->update(['status' => $status == 0 ? 1 : 0, 'date' => date("Y-m-d H:i:s")]);
                return "success";
            } catch (\Exception $exception) {
                return "error";
            }
        }
    }

    public function getList(){
        DB::table("rents")->where("name","test")->get();
        return "error";
    }

}
