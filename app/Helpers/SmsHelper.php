<?php

    namespace App\Helpers;

    use App\Models\Order;
    use App\Models\Sms;
    use LetsAds;

    class SmsHelper
    {
        public static function getStatus($sms_id)
        {
            switch (LetsAds::status($sms_id)->description) {
                case 'MESSAGE_IS_DELIVERED':
                    $sms_status = 'доставлено';
                    break;
                case 'MESSAGE_IS_SENT':
                    $sms_status = 'отправлено';
                    break;
                case 'MESSAGE_NOT_DELIVERED':
                    $sms_status = 'не доставлено';
                    break;
                case 'MESSAGE_IN_QUEUE':
                    $sms_status = 'поставлено в очередь на отправку';
                    break;
                case 'MESSAGE_IN_QUEUE':
                    $sms_status = 'поставлено в очередь на отправку';
                    break;
                case 'MESSAGE_NOT_EXIST':
                    $sms_status = 'такое сообщение не существует';
                    break;
                default:
                    $sms_status = 'статус не известен';
                    break;
            }

            return $sms_status;
        }

        public static function sendSms(Order $order, $type)
        {
            switch ($type) {
                case 1:
                    $message = 'Ваш заказ №'.$order->id.' принят. Центр полиграфии, пр. Науки,7, тел 0671066500';
                    break;
                case 2:
                    $message = 'Ваш заказ №'.$order->id.' готов!Центр полиграфии,пр.Науки 7.';
                    if ($order->surcharge > 0) {
                        $message .= 'К оплате '.$order->surcharge_formated.' грн.';
                    }
                    break;
            }

            $send = LetsAds::send($message, env('LETSADS_SENDER'), '38'.$order->client->phone);
            $sms  = new Sms();

            if (isset($send->sms_id)) {
                $sms->sms_id  = $send->sms_id;
                $sms->is_sent = true;
            }
            $sms->type     = $type;
            $sms->order_id = $order->id;
            $sms->message  = $message;
            $sms->save();
        }
    }