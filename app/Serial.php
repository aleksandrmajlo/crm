<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Serial extends Model
{

    public function order()
    {
        return $this->belongsTo('App\Order');
    }
    public function task()
    {
        return $this->belongsTo('App\Task');
    }

}
