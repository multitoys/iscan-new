<?php

namespace App\Observers;

use App\Helpers\SmsHelper;
use App\Models\Sms;

class SmsObserver
{
    public function creating(Sms $sms)
    {
        SmsHelper::sendSms($sms);
    }
}
