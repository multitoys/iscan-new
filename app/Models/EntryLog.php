<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class EntryLog extends Model
{
    protected $fillable = ['user_id', 'ip'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
