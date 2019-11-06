<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 27.10.2019
 * Time: 22:49
 */

namespace App\Services;
use App\Order;

class OrderAdmin
{

    public static function getInfo($id){
        $order=Order::find($id);
        return $order;
    }

}