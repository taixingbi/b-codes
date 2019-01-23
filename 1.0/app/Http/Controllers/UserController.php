<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use Mail;
use App\Http\Requests;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Cookie;


class UserController extends Controller
{
    
    public function getSignup(Request $request){

        $response = new \Illuminate\Http\Response(view('user.signup'));
        $response->cookie('loc', "eyJpdiI6IkZSTTJ3RXorKzI0VDZEMitRRVJMdGc9PSIsInZhbHVlIjoiaHhUWEpJODk4VFwvTGY3N0VRdldMWFE9PSIsIm1hYyI6IjkxYjA3N2MwMGM1N2I3MWExMjg5NjM0NmFhZjVjZGI1MTgzMGI1ODdmZDlkNGFhOWJmOTQyMjE1N2M4YjNmMjIifQ==", 365*24*60);

        return $response;

//        return view('user.signup');
    }


    public function postSignup(Request $request){

        $this->validate($request,[
            'email' => 'email|required|unique:users',
            'password' => 'required|min:4'
        ]);

        $user = new User([
            'email' => strtolower($request->input('email')),
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'password' => bcrypt($request->input('password')),
            'level' => 3
        ]);

        $user->save();

//        $data = array('name' => 'BigBike', 'msg' => 'Welcome to BigBike');
//        Mail::send('emails.signup-welcome', $data, function ($message) use($user) {
//
//            $message->from('alexrussian1001@gmail.com', 'Welcome to BigBike');
//
////           $message->to('support@my3dcrestwhite.ru')->subject('Thank you for registration');
//            $message->to($user->email)->subject('Welcome to BigBike');
//
//        });
//        $this->signupWel($user);
        session(['msg'=>"Successfully created new account, please sign in"]);

        return redirect()->route('user.signin');

//        try {
//            $cashier = DB::table('users')->where('email', $request->input('email'))->first();
//
//        }catch(\Illuminate\Database\QueryException $exception){
//            return redirect()->back()->with('error', $exception->getMessage());
//        }
//
//        if($cashier->level==1 || $cashier->level==2){
//            $title = 'Manager';
//        }elseif($cashier->level==3){
//            $title = 'Cashier';
//        }
////
//        session(['cashierEmail'=>strtolower($request->input('email')),'location'=>$request->location,'cashier'=>$cashier->first_name.' '.$cashier->last_name,'title'=>$title,'level'=>$cashier->level]);
//
//
//        Auth::login($user);
//        if(Session::has('oldUrl')) {
//            $oldUrl = Session::get('oldUrl');
//            Session::forget('oldUrl');
//            return redirect()->to($oldUrl);
//        }
//        //return redirect()->route('user.profile');
//
//        return redirect()->route('agent.rentOrder');
    }

    public function signupWel($user){
        $data = array('name' => 'BigBike', 'msg' => 'Welcome to BigBike');
        Mail::send('emails.signup-welcome', $data, function ($message) use($user) {

            $message->from('vouchers@bikerent.nyc', 'Welcome to BikeRent tickets system');

//           $message->to('support@my3dcrestwhite.ru')->subject('Thank you for registration');
            $message->to($user->email)->subject('Thank you for registration');

        });
    }

    public function getSignin(){
        Auth::logout();

        $locations= DB::table('locations')->get();


        return view('user.signin',['locations'=>$locations]);
    }

    public function postSignin(Request $request){
//        dd("test");
//        dd(request()->cookie('loc'));
        if(($request->location=="203W 58th Street"
            || $request->location=="117W 58th Street"
            || $request->location=="Central Park South"
            || $request->location=="Central Park West"
            || $request->location=="Grand Army Plaza"
            || $request->location=="40W 55th Street"
            || $request->location=="145 Nassau Street")&& ($request->email!="xdrealmadrid@gmail.com" && $request->email!="melissa@bikerent.nyc")){
            $location_check = DB::table("locations")->where("title",$request->location)->where("pw",request()->cookie("loc"))->first();
            if(empty($location_check)) {
                return redirect()->back()->with('error', "Email or Password is invalid");
            }
        }




        $this->validate($request,[
            'email' => 'email|required',
            'password' => 'required|min:4'
        ]);


        if(strtolower($request->email)=='bermudezcrystal@gmail.com' && ($request->location!='Central Park South' && $request->location!='Central Park West')){
            return redirect()->back()->with('error', "Wrong Location");
        }

        if(strtolower($request->email)=='josesimen@yahoo.com' && ($request->location!='Central Park West'  && $request->location!='Central Park South')){
            return redirect()->back()->with('error', "Wrong Location");
        }

//        if(strtolower($request->email)=='marketing@bikerent.nyc' && $request->location!='203W 58th Street'){
//            return redirect()->back()->with('error', "Wrong Location");
//        }


        if(Auth::attempt([
            'email' => strtolower($request->input('email')),
            'password' => $request->input('password')
        ])){
            try {
                $user = DB::table('users')->where('email', $request->email)->first();

            }catch(\Illuminate\Database\QueryException $exception){
                return redirect()->back()->with('error', $exception->getMessage());
            }

            if($user->level==1 || $user->level==2){
                $title = 'Manager';
            }elseif($user->level==3){
                $title = 'Cashier';
            }elseif($user->level==4){
                $title = 'User';
            }

            if($user->level==1 || $user->level==2){
                $title = 'Manager';
            }elseif($user->level==3){
                $title = 'Cashier';
            }elseif($user->level==4){
                $title = 'User';
            }elseif($user->level==0){
                $title = 'Super User';
            }
            
//            session(['cashierEmail'=>strtolower($request->input('email')),'location'=>$request->location,'cashier'=>$user->first_name.' '.$user->last_name,'title'=>$title,'level'=>$user->level,'cart'=>array()]);
            session(['cashierEmail'=>strtolower($request->input('email')),'location'=>$request->location,'cashier'=>$user->first_name.' '.$user->last_name,'title'=>$title,'level'=>$user->level]);
//            Session::forget('cart');


            
            if(strtolower($request->email)=='reservations@bikerent.nyc' ) {
                session(['phoneReservation'=>'phoneReservation']);
                return redirect()->route('agent.phoneReservation');
            }
            if(Session::has('oldUrl')) {
                $oldUrl = Session::get('oldUrl');
                Session::forget('oldUrl');
                return redirect()->to($oldUrl);
            }
            //return redirect()->route('user.profile');



//            session(['location'=>$request->location,'cashier'=>$user->first_name.' '.$user->last_name,'title'=>$title,'level'=>$user->level]);
            return redirect()->route('agent.rentOrder');
        }
        return redirect()->back()->with('error', "Email or Password is invalid");

        //return redirect()->route('');
    }

    public function getProfile(){
        $orders = Auth::user()->orders;
        $orders = $orders->transform(function($order, $key){
            $order->cart = unserialize($order->cart);
            return $order;
        });

        return view('user.profile', ['orders' => $orders]);
    }

    public function getLogout(){
//        Session::forget('location');
        Session::forget('cashier');
        Session::forget('title');
        Session::forget('level');
        Session::forget('location');
        Session::forget('cashierEmail');


        Auth::logout();
        //return redirect()->back();
        return redirect()->route('user.signin');
    }
}
