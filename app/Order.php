<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \App\User;
use \App\Task;
class Order extends Model
{

    protected $fillable = [
        'user_id',
        'task_id',
        'status',
        'user_data',
        'task_data',
        'type',
        'parent_id',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function serials()
    {
        return $this->hasMany('App\Serial');
    }

    public function task()
    {
        return $this->hasOne(Task::class);
    }

}
