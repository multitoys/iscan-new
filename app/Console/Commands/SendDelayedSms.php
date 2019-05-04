<?php

namespace App\Console\Commands;

use App\Helpers\SmsHelper;
use App\Models\Sms;
use Illuminate\Console\Command;
use LetsAds;

class SendDelayedSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:delayed:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send delayed sms';

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
        $messsages = Sms::where('is_sent', false)->where('attempts', '<', Sms::MAX_ATTEMPTS)
                        ->with('order.client')->get();

        if (count($messsages)) {
            foreach ($messsages as $sms) {
                SmsHelper::sendSms($sms);
                $sms->attempts = ++$sms->attempts;
                $sms->save();
            }
        }
    }
}
