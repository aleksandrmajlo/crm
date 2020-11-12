<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 11.09.2019
 * Time: 11:11
 */

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Task;
use App\Order;
use App\User;
use App\Services\OrderService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class DashbordController extends Controller
{
    protected $limit=20;

    public function indexAdmin(Request $request){

    }

    public function get(Request $request)
    {
        $date = $request->input('date');
        $failed = config('status_coments.failed');
        $results = [
            'work' => [],
            'done' => [],
            'failed' => []
        ];
        $orders = Order::whereDate('created_at', $date)
            ->orWhereDate('updated_at', $date)
            ->get();
        foreach ($orders as $order) {
            if ($order->status == 1) {
                if (is_null($order->parent_id)) {
                    $ip = $order->task->ip;
                    $port = $order->task->port;
                    $results['work'][] = [
                        'id' => $order->task_id,
                        'user' => $order->user->name . ' ' . $order->user->email,
                        'color' => $order->user->color,
                        'task' => $ip . ':' . $port,
                        'weight' => $order->task->weight,
                    ];

                } else {
                    $results['work'][] = [
                        'id' => $order->task_id,
                        'user' => $order->user->name . ' ' . $order->user->email,
                        'color' => $order->user->color,
                        'task' => 'parent',
                        'weight' => 'parent',
                    ];

                }
            }
            if ($order->status == 3) {
                $serials = [];
                if (is_null($order->parent_id)) {
                    if ($order->serials) {
                        foreach ($order->serials as $serial) {
                            $serials[] = '/search?q=' . $serial->serial;
                        }
                    }
                    $results['done'][] = [
                        'id' => $order->task_id,
                        'user' => $order->user->name . ' ' . $order->user->email,
                        'color' => $order->user->color,
                        'task' => $order->task->ip . ':' . $order->task->port,
                        'weight' => $order->task->weight,
                        'serials' => $serials
                    ];
                } else {
                    $results['done'][] = [
                        'id' => $order->task_id,
                        'user' => $order->user->name . ' ' . $order->user->email,
                        'color' => $order->user->color,
                        'task' => 'parent',
                        'weight' => 'parent',
                        'serials' => $serials
                    ];
                }
            }
            if ($order->status == 4) {
                $failed_text = "";
                $comment_text = "";
                if ($order->comment) {
                    $comment = unserialize($order->comment);
                    $failed_text = $failed[$comment['select']];
                    $comment_text = $comment['comment'];
                }
                $results['failed'][] = [
                    'id' => $order->task_id,
                    'user' => $order->user->name . ' ' . $order->user->email,
                    'color' => $order->user->color,
                    'task' => $order->task->ip . ':' . $order->task->port,
                    'weight' => $order->task->weight,
                    'failed' => $failed_text,
                    'comment' => $comment_text,
                ];
            }
        }
        return response()->json([
            'success' => true,
            'results' => $results
        ], 200);
    }

    // все пользователи
    public function getUsers()
    {
        $users = User::where('role', 3)->
        where('status', 1)->get();
        $results = [];

        foreach ($users as $user) {
            $results[] = [
                'id' => $user->id,
                'name' => $user->name . " " . $user->email,
            ];
        }

        return response()->json([
            'success' => true,
            'users' => $results
        ], 200);
    }

    // заказы конкретно пользователя
    public function getUser(Request $request)
    {
        $offset=0;
        if($request->has('offset')){
            $offset=$request->offset;
        }
        $id = $request->input('id');
        $user = User::find($id);

        $failed = config('status_coments.failed');
        $orderstatus = config('order_status.orderstatus');
        $orders = [];
        $dara_orders =Order::where('user_id',$id)->offset($offset)->limit($this->limit)->orderBy('task_id','desc')->get();
        foreach ($dara_orders as $order) {
            $serials = [];
            if ($order->status == 3) {
                if ($order->serials) {
                    foreach ($order->serials as $serial) {
                        $serials[] = '/search?q=' . urlencode($serial->serial);
                    }
                }
            }
            $failedText = "";
            $commentText = "";
            if ($order->status == 4) {
                if ($order->comment) {
                    $comment = @unserialize($order->comment);
                    if ($comment !== false) {
                        if ($comment['select']) {
                            $failedText = $failed[$comment['select']];
                        }
                        if ($comment['comment']) {
                            $commentText = $comment['comment'];
                        }
                    }
                }
            }
            $orders[] = [
                'id' => $order->task_id,
                'order_id'=>$order->id,
                'status_id' => $order->status,
                'status' => $orderstatus[$order->status],
                'serials' => $serials,
                'failed' => $failedText,
                'comment' => $commentText,
                'created_at' => Carbon::parse($order->created_at)->format('d-M-Y H:i:s'),
                'updated_at' => Carbon::parse($order->updated_at)->format('d-M-Y H:i:s'),
                'notes'=>$order->notes
            ];
        }

        if ($offset==0){
            return response()->json([
                'success' => true,
                'weight' => $user->weight,
                'limit' => OrderService::getLimitUser($id),
                'orders' => $orders
            ], 200);
        }else{
            return response()->json([
                'success' => true,
                'orders' => $orders
            ], 200);
        }
    }

}
