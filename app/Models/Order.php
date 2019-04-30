<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const FILES_DIR = '/public/orders';

    const PAYMENT_CASH  = 1;
    const PAYMENT_COUNT = 2;
    const PAYMENT_CARD  = 3;

    protected $fillable = ['is_files'];

    public function getSurchargeAttribute()
    {
        return $this->amount - $this->prepayment;
    }

    public function getSurchargeFormatedAttribute()
    {
        return number_format($this->amount - $this->prepayment, 2, '.', '');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function outsource()
    {
        return $this->belongsTo('App\Models\Outsource');
    }

    public function paper()
    {
        return $this->belongsTo('App\Models\Paper');
    }

    public function service()
    {
        return $this->belongsTo('App\Models\Service');
    }

    public function status()
    {
        return $this->belongsTo('App\Models\Status');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function sms1()
    {
        return $this->hasOne('App\Models\Sms')->type1();
    }

    public function sms2()
    {
        return $this->hasOne('App\Models\Sms')->type2();
    }
}
