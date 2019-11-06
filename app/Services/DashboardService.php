<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 10.09.2019
 * Time: 23:21
 */

namespace App\Services;

use App\Order;
use App\Task;
use App\Serial;
use App\Orderlog;

class DashboardService
{

    public static function getStatistics(){

        $results=[
            'all'=>Task::count(),
            'free'=>Task::where('status',1)->count(),
            'work'=>Task::where('status',2)->count(),
            'done'=>Task::where('status',3)->count(),
            'failed'=>Task::where('status',4)->count(),
        ];
        return $results;
    }


}