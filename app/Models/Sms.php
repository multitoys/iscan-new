<?php

namespace App\Models;

use App\Helpers\SmsHelper;
use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    const MAX_ATTEMPTS = 5;

    const MESSAGE_UNKNOWN       = 0;
    const MESSAGE_IS_DELIVERED  = 1;
    const MESSAGE_IS_SENT       = 2;
    const MESSAGE_NOT_DELIVERED = 3;
    const MESSAGE_IN_QUEUE      = 4;
    const MESSAGE_NOT_EXIST     = 5;

    const STATUSES = [
        'MESSAGE_UNKNOWN'       => self::MESSAGE_UNKNOWN,
        'MESSAGE_IS_DELIVERED'  => self::MESSAGE_IS_DELIVERED,
        'MESSAGE_IS_SENT'       => self::MESSAGE_IS_SENT,
        'MESSAGE_NOT_DELIVERED' => self::MESSAGE_NOT_DELIVERED,
        'MESSAGE_IN_QUEUE'      => self::MESSAGE_IN_QUEUE,
        'MESSAGE_NOT_EXIST'     => self::MESSAGE_NOT_EXIST,
    ];

    protected $fillable = ['sms_id', 'order_id', 'type', 'message', 'is_sent', 'status', 'attempts'];

    public function getSmsStatusAttribute()
    {
        $statuses = [
            self::MESSAGE_UNKNOWN,
            self::MESSAGE_IS_SENT,
            self::MESSAGE_IN_QUEUE,
            self::MESSAGE_NOT_DELIVERED,
        ];
        if ($this->sms_id && in_array($this->status, $statuses)) {
            $this->status = SmsHelper::getStatus($this->sms_id);
            $this->save();
        }

        return $this->status;
    }

    public function getStatusTextAttribute()
    {
        if (!$this->is_sent) {
            return 'не отправлено';
        }

        switch ($this->sms_status) {
            case self::MESSAGE_IS_DELIVERED:
                $sms_status = 'доставлено';
                break;
            case self::MESSAGE_IS_SENT:
                $sms_status = 'отправлено';
                break;
            case self::MESSAGE_NOT_DELIVERED:
                $sms_status = 'не доставлено';
                break;
            case self::MESSAGE_IN_QUEUE:
                $sms_status = 'поставлено в очередь на отправку';
                break;
            case self::MESSAGE_NOT_EXIST:
                $sms_status = 'такое сообщение не существует';
                break;
            case self::MESSAGE_UNKNOWN:
            default:
                $sms_status = 'статус не известен';
                break;
        }

        return $sms_status;
    }

    public function getStatusCssAttribute()
    {
        if (!$this->is_sent) {
            return 'sms-status-default';
        }

        switch ($this->status) {
            case self::MESSAGE_IS_DELIVERED:
                return 'sms-status-delivered';
            case self::MESSAGE_NOT_DELIVERED:
                return 'sms-status-not-delivered';
            default:
                return 'sms-status-sent';
        }
    }

    public function scopeSent($query)
    {
        return $query->where('sent', 1);
    }

    public function scopeType1($query)
    {
        return $query->where('type', 1);
    }

    public function scopeType2($query)
    {
        return $query->where('type', 2);
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }
}
