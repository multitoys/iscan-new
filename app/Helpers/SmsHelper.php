<?php

    namespace App\Helpers;

    use App\Models\Order;
    use App\Models\Sms;
    use LetsAds;

    class SmsHelper
    {
        public static function getStatus($sms_id)
        {
            try {
                $status = LetsAds::status($sms_id);
                switch ($status->description) {
                    case 'MESSAGE_IS_DELIVERED':
                    case 'MESSAGE_IS_SENT':
                    case 'MESSAGE_NOT_DELIVERED':
                    case 'MESSAGE_IN_QUEUE':
                    case 'MESSAGE_NOT_EXIST':
                        $sms_status = Sms::$status;
                        break;
                    default:
                        $sms_status = Sms::MESSAGE_UNKNOWN;
                        break;
                }
            } catch (\Exception $e) {
                $sms_status = Sms::MESSAGE_UNKNOWN;
            }
    
            return $sms_status;
        }

        public static function sendSms(Sms $sms)
        {
            $order = Order::findOrFail($sms->order_id);
            switch ($sms->type) {
                case 1:
                    $message = 'Ваш заказ №'.$order->id.' принят. Центр полиграфии, пр. Науки,7, тел 0671066500';
                    break;
                case 2:
                    $message = 'Ваш заказ №'.$order->id.' готов!Центр полиграфии,пр.Науки 7.';
                    if ($order->surcharge > 0) {
                        $message .= 'К оплате '.$order->surcharge_formated.' грн';
                    }
                    break;
            }

            try {
                $send = LetsAds::send($message, env('LETSADS_SENDER'), '38' . $order->client->phone);
            } catch (\Exception $e) {
            }

            if (isset($send->sms_id)) {
                $sms->sms_id  = $send->sms_id;
                $sms->is_sent = true;
            }
            $sms->message = $message;
        }
    }