<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Serial extends Model
{
    use SoftDeletes;
    public function order()
    {
        return $this->belongsTo('App\Order');
    }

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
