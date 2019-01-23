<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Log;
use App\Jobs\SendReminderEmail;
//use App\Jobs\SendRemidEmails;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\User;
use Carbon\Carbon;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'email test';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
//        DB::table('test2')->insert(
//            ['name' => 'jjjjj']
//        );

//        Log::info('start sending');
//        $users = User::where('is_registered',0)->whereNull('password')->whereNull('deleted_at')->get();
//        foreach ($users as $user){
////            Log::info('receiver info: '.$user->firstname." ".$user->lastname);
//            $clink = Clink::where('to_user_id', $user->id)
//                ->first();
////            Log::info('clink info: '.$clink->from_user_id." to ".$clink->to_user_id);
//            $now = Carbon::now();
//            $date = new Carbon($clink->created_at);
////            Log::info('now: '.$now);
//
////            Log::info('create at: '.$date);
//            $diff = $now->diffInDays($date);
//
////            Log::info('length: '.$diff);
//
//            $User = User::where('id',$clink->from_user_id)->first();
////            Log::info('sender info: '.$User->firstname);
//            if(($diff==1 || $diff==2 || $diff==5 || $diff%30==0) && $diff>0 && $diff <=365){
////                Log::info('create at: '.$date);
////                Log::info('now: '.$now);
//
////                Log::info('its been a day or a wwek or a month');
//
//
//                if (!empty($user->email)) {
//                    //                Log::info('email: '.$user->email);
//                    //Log::info('clink email to user: '.$toUser->firstname);
//                    //Log::info('clink email from user: '.$User->firstname);
//                    if(!empty($User) && !empty($clink)) {
//                        $job1 = (new SendRemidEmails($user, $User, $clink, null, null, null,$diff))->onConnection('sync');
//                        dispatch($job1);
//                    }
//                }
//                elseif (!empty($user->phone)) {
//                    //Log::info('clink phone');
//
//                    // $job5 = (new SendClinkNonRegisteredCongratsSMS($toUser, $User, $Clink, $Merchant, $userType, $receiptUser))->onConnection('sync');
//                    // dispatch($job5);
//                    if(!empty($User) && !empty($clink)) {
//
//                        $job2 = (new SendClinkNonRegisteredSMS($user, $User, $clink, null, null, null))->onConnection('sync');
//                        dispatch($job2);
//                    }
//                }
//            }
//
//        }
//
//
//        Log::info('num users: '.count($users));
        $job1 = (new SendReminderEmail())->onConnection('sync');
        dispatch($job1);

    }
}
