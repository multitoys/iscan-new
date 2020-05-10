<?php

    namespace App\Helpers;

    use App\Models\Order;
    use App\Models\Sms;
    use LetsAds;

    class SmsHelper
    {
        public static function getStatus($sms_id)
        {
            if (SmsHelper::isConnected()) {
                try {
                    $status = (string)LetsAds::status($sms_id)->description;
                    switch ($status) {
                        case 'MESSAGE_IS_DELIVERED':
                        case 'MESSAGE_IS_SENT':
                        case 'MESSAGE_NOT_DELIVERED':
                        case 'MESSAGE_IN_QUEUE':
                        case 'MESSAGE_NOT_EXIST':
                            $sms_status = Sms::STATUSES[$status];
                            break;
                        default:
                            $sms_status = Sms::MESSAGE_UNKNOWN;
                            break;
                    }
                } catch (\Exception $e) {
                    $sms_status = Sms::MESSAGE_UNKNOWN;
                }
            } else {
                $sms_status = Sms::MESSAGE_UNKNOWN;
            }

            return $sms_status;
        }

        public static function sendSms(Sms $sms)
        {
            $order = Order::findOrFail($sms->order_id);

            switch ($sms->type) {
                case 1:
                    $sms->message = 'Ваш заказ №'.$order->id.' принят. Центр полиграфии, пр. Науки,7, тел 0671066500';
                    break;
                case 2:
                    if ($order->surcharge > 0) {
                        $sms->message = 'Ваш заказ №'.$order->id.' готов!Центр полиграфии,пр.Науки 7.К оплате '.$order->surcharge_formated.' грн';
                    } else {
                        $sms->message = 'Ваш заказ №'.$order->id.' готов! Центр полиграфии, пр.Науки 7.';
                    }
                    break;
            }

            try {
                if (SmsHelper::isConnected()) {
                    $send = LetsAds::send($sms->message, env('LETSADS_SENDER'), '38' . $order->client->phone);
                }
            } catch (\Exception $e) {
                info($e->getMessage());
            }

            if (isset($send->sms_id)) {
                $sms->sms_id  = $send->sms_id;
                $sms->is_sent = true;
            }
        }

        public static function isConnected()
        {
            $connected = @fsockopen("www.example.com", 80);
            //website, port  (try 80 or 443)
            if ($connected){
                $is_conn = true; //action when connected
                fclose($connected);
            }else{
                $is_conn = false; //action in connection failure
            }
            return $is_conn;
        }
    }
