<?php

namespace App\Listeners;

use App\Mail\LockoutMail;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Mail;

class SendEmailLockoutNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Lockout  $event
     * @return void
     */
    public function handle(Lockout $event)
    {
        try {
            Mail::to('alarm@iscan.com.ua')
                ->send(new LockoutMail($event->request));
        } catch (\Exception $e) {
            info($e->getMessage());
        }
    }
}
