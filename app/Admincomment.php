<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admincomment extends Model
{
    public function order()
    {
        return $this->belongsTo('App\Order');
    }
}
