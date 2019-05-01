<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['name', 'phone', 'email'];

    public function ordersReady()
    {
        return $this->hasMany(Order::class)->where('status_id', 5);
    }
}
