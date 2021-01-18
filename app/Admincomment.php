<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Admincomment extends Model
{
    use SoftDeletes;
    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function task()
    {
        return $this->belongsTo('App\Task','task_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
