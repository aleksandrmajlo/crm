<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Note extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'comment',
        'order_id',
        'task_id',
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}
