<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Doc extends Model
{

    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function doccomments()
    {
        return $this->hasMany('App\Doccomment');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
