<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sms extends Model
{
    protected $fillable = ['sms_id', 'order_id', 'type', 'message', 'is_sent'];

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
