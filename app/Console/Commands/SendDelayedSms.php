<?php

namespace App\Console\Commands;

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
        $messsages = Sms::where('is_sent', false)->with('order.client')->get();

        if (count($messsages)) {
            foreach ($messsages as $sms) {
                try {
                    $send = LetsAds::send($sms->message, env('LETSADS_SENDER'), '38' . $sms->order->client->phone);
                } catch (\Exception $e) {
                }
                if (isset($send->sms_id)) {
                    $sms->sms_id  = $send->sms_id;
                    $sms->is_sent = true;
                    $sms->save();
                }
            }
        }
    }
}
