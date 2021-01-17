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
    public function admincomments()
    {
        return $this->hasMany('App\Admincomment', 'task_id');
    }

    public function getColorAttribute()
    {
        if(isset($this->user->color)){
            $color=$this->user->color;
        }else{
            $color="";
        }
        return $color;
    }

}
