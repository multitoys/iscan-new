<?php

namespace App\Listeners;

use App\Models\EntryLog;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RecordEntryLog
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
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        EntryLog::create([
            'user_id' => $event->user->id,
            'ip'      => request()->ip(),
        ]);
    }
}
