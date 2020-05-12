<?php

namespace App\Console\Commands;

use App\Helpers\SmsHelper;
use App\Models\Sms;
use Illuminate\Console\Command;
use LetsAds;

class UpdateSmsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:update:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update sms status';

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
        if (SmsHelper::isConnected()) {
            $messages = Sms::whereIsSent(true)
                ->where('sms_id', '>', 0)
                ->whereStatus(Sms::MESSAGE_UNKNOWN)
                ->orderByDesc('id')
                ->select('id', 'sms_id', 'order_id', 'type')
                ->limit(20)
                ->get();
            if ($messages->count()) {
                foreach ($messages as $sms) {
                    $status = SmsHelper::getStatus($sms->sms_id);
                    if ($status) {
                        $sms->status = $status;
                        $sms->save();
                    }
                    $this->info('Заказ №'.$sms->order_id.' статус смс'.$sms->type.': '.$status);
                }
            }
        }
    }
}
