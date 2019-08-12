<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 11.08.2019
 * Time: 17:22
 */

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class OrderService
{
    public function getOrder(){
        $user_id = Auth::user()->id;
        var_dump($user_id);
    }
}