<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Task;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     *
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_name',
        'first_name',
        'middle_name',
        'phone',
        'status',
        'role',
        'weight',
        'blind',
        'color',
        'tape',
        'exportallowed'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tasks()
    {
        return $this->hasMany('Task');
    }
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function orderlogs()
    {
        return $this->hasMany('App\Orderlog');
    }



    public function docs()
    {
        return $this->hasMany('App\Doc');
    }


    public function doccomments()
    {
        return $this->hasMany('App\Doccomment');
    }
}
