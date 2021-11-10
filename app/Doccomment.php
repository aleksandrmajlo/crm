<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doccomment extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function doc()
    {
        return $this->belongsTo('App\Doc');
    }
}
