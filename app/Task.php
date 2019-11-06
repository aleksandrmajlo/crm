<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    protected $fillable = [
        'ip',
        'port',
        'domain',
        'login',
        'password',
        'weight',
        'status',
        'user_id',
        'comments',
        'flag',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function order()
    {
        return $this->hasOne('App\Order');
    }

    public function serials()
    {
        return $this->hasMany('App\Serial');
    }
    public function orderlogs()
    {
        return $this->hasMany('App\Orderlog');
    }
}
