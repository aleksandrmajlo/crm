<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
class Orderlog extends Model
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

}
