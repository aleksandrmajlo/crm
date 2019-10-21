<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 20.10.2019
 * Time: 11:51
 */

namespace App\Http\Controllers;


use App\Task;
use App\Order;

use Illuminate\Http\Request;

class CleartaskController extends Controller
{
    public function index()
    {

        $orders = Order::all();
        foreach ($orders as $order) {
            if (is_null($order->task)) {
                $order->delete();
            }
        }
        return redirect()->back();
    }
}