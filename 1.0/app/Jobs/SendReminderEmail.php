<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class SendReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send('emails.test', [
//            'toUser' => $this->toUser,
//            'Account' => $this->Account,
        ], function ($message) {
//            $message->from(env('MAIL_FROM'));
            $message->from('reservations@bikerent.nyc');
            $message->to("dx285@nyu.edu");
            $message->subject('Bike Rent Coupon');
        });
    }
}
