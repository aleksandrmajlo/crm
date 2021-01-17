<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 17.08.2019
 * Time: 23:03
 */

namespace App\Http\Controllers\Ajaxuser;


use App\Http\Controllers\Controller;
use App\Admincomment;
use App\Order;
use App\Services\Log;
use App\Task;
use App\Services\OrderService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;


class UserController extends Controller
{
    protected $limit = 200;

    public function get()
    {
        $limit_used = OrderService::getLimitUser();
        return response()->json([
            'success' => true,
            'user' => Auth::user(),
            'limit_used' => $limit_used,
            'status' => config('adm_settings.LogStatus')
        ], 200);
    }

    public function tasks()
    {
        $tasks = Task::where('status', '<', 3)->orderBy('id', 'desc')->get();
        $results = [];
        // для поиска
        if ($tasks) {
            foreach ($tasks as $task) {
                $year = $task->created_at->year;
                $month = $task->created_at->month;
                $date = Carbon::parse($task->created_at)->format('d');
                $timestamp = strtotime($task->created_at->format($year . '-' . $month . '-' . $date));
                if (!isset($results[$timestamp])) {
                    $results[$timestamp] = [];
                }
                $results[$timestamp][] = [
                    'id' => $task->id,
                    'ip' => $task->ip,
                    'port' => $task->port,
                    'domain' => $task->domain,
                    'login' => $task->login,
                    'password' => $task->password,
                    'weight' => $task->weight,
                    'status' => $task->status,
                    'user_id' => $task->user_id,
                    'flag' => $task->flag,
                    'created_at' => $task->created_at,
                    'timestamp' => $timestamp
                ];
                krsort($results, SORT_NUMERIC);
            }
        }
        return response()->json([
            'tasks' => $results,
            'success' => true,
        ], 200);
    }

    // свободные задания
    public function freetasks()
    {
        $tasks = Task::where('status', '=', 1)->orderBy('id', 'desc')->get();
        $results = [];
        if ($tasks) {
            foreach ($tasks as $task) {
                $results[] = [
                    'id' => $task->id,
                    'ip' => $task->ip,
                    'port' => $task->port,
                    'domain' => $task->domain,
                    'login' => $task->login,
                    'password' => $task->password,
                    'weight' => $task->weight,
                    'status' => $task->status,
                    'flag' => $task->flag,
                    'created_at' => $task->created_at,
                    'myorder' => false
                ];
            }
        }
        return response()->json([
            'tasks' => $results,
            'success' => true,
        ], 200);
    }

    // в свободных себе добавляет
    public function addUserTask(Request $request)
    {
        $ids = [];
        $task_id = $request->id;
        $user_id = Auth::user()->id;
        $user_weight = Auth::user()->weight;
        $task = Task::find($task_id);
        // проверяем или не привышен лимит
        $count = OrderService::getWeigthOrderUser($user_id, $task->weight, $user_weight);
        if ($count) {

            // проверка может order для задания реализован
            if (!is_null($task->order)) {
                $order = Order::find($task->order->id);
                $order->user_id = $user_id;
                $order->status = 1;
                $order->type = 1;
                $order->created_at = new \DateTime();
                $order->save();
                $id = $task->order->id;
            } else {
                $order = new Order;
                $order->task_id = $task->id;
                $order->user_id = $user_id;
                $order->status = 1;
                $order->type = 1;
                $order->save();
                $id = $order->id;
            }
            // записуем в лог
            Log::write(1, $task_id, $order->id, $user_id, null, null);
            // вставляем заказ
            $task->status = 2;
            $task->user_id = $user_id;
            $task->order_id = $id;
            $task->save();
            $ids[] = $task_id;
            //получить похожие по ип
            $task_others = Task::where('ip', $task->ip)->where('id', '!=', $task->id)
                ->get();
            if (count($task_others) > 0) {
                foreach ($task_others as $task_otner) {

                    if (!is_null($task_otner->order)) {
                        $order = Order::find($task_otner->order->id);
                        $order->user_id = $user_id;
                        $order->status = 1;
                        $order->type = 2;
                        $order->parent_id = $id;
                        $order->created_at = new \DateTime();
                        $order->save();
                        $id_other = $order->id;

                    } else {

                        $order = new Order;
                        $order->task_id = $task_otner->id;
                        $order->user_id = $user_id;
                        $order->status = 1;
                        $order->type = 2;
                        $order->parent_id = $id;
                        $order->save();
                        $id_other = $order->id;

                    }

                    $task_otner->status = 2;
                    $task_otner->user_id = $user_id;
                    $task_otner->order_id = $id_other;
                    $task_otner->save();
                    $ids[] = $task_otner->id;

                    // записуем в лог
                    Log::write(1, $task_otner->id, $order->id, $user_id, null, ['text' => 'Add in addition to task:' . $task_id]);

                }
            }
            return response()->json(
                [
                    'success' => trans('order.success'),
                ], 200);

        } else {
            // cообщить что лимит перевыщын
            return response()->json(['notorder' => trans('order.notorder'),], 200);
        }

    }

    // установить комментарий просмотреным
    public function CommentViewed(Request $request)
    {
        $comment_id = $request->comment_id;
        $admincomment = Admincomment::find($comment_id);
        if ($admincomment->viewed === 0) {
            $admincomment->viewed = 1;
            $admincomment->save();
        }
        $task = Task::find($admincomment->task_id);

        Log::write(8,
            $task->id,
            $admincomment->order_id,
            Auth::user()->id,
            $admincomment->user_id,
            [
                'comment' => $admincomment->commentadmin
            ]
        );
        return response()->json([
            'success' => true,
        ], 200);
    }

    // получить сообщение о добавленных заданиях
    public function messange()
    {

        $user_id = Auth::user()->id;
        $html = "";
        $messanges = DB::table('tasklogs')->where('user_id', $user_id)
            ->where('messange', 0)->get();
        if ($messanges) {
            foreach ($messanges as $messange) {
                $task_id = $messange->task_id;
                $task = Task::find($task_id);
                $order = Order::find($messange->order_id);
                if ($order) {
                    $parent_id = $order->parent_id;
                    $parentOrder = Order::find($parent_id);
                    if ($task->status == 2) {
                        $html .= '<p class="mb-2">
                               Add ID:<span class="text-bold">' . $task_id . ' </span> to ID:<span class="text-bold">' . $parentOrder->task_id . '.</span><br>
                               Status:<span class="text-bold">Work</span>
                               </hr>
                           </p>';
                    }
                }

            }
        }
        return response()->json([
            'html' => $html,
            'success' => true,
        ], 200);

    }

    public function messangeReadStaus()
    {
        $user_id = Auth::user()->id;
        DB::table('tasklogs')->where('user_id', $user_id)
            ->where('messange', 0)
            ->update(['messange' => 1]);
        return response()->json([
            'success' => true,
        ], 200);

    }

    // лента комментов
    public function commentsFeed(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if ($user->tape == 1) {
            $offset = 0;
            if ($request->has('offset')) {
                $offset = $request->offset;
            }
            $comments = Admincomment::offset($offset)->limit($this->limit)->orderBy('id', 'desc')->get();
            if ($offset == 0) {
                return response()->json([
                    'comments' => $comments,
                    'offset' => (int)$request->offset,
                    'limit' => $this->limit,
                    'success' => true,
                ], 200);
            } else {
                return response()->json([
                    'comments' => $comments,
                    'success' => true,
                ], 200);
            }
        } else {
            return response()->json([
                'success' => false,
            ], 200);
        }
    }

    // export
    public function ordersExportUsers()
    {
        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        if ($user->exportallowed === 0) return abort(404, 'Not allowed');;
        $orders = OrderService::getOrderActive($user_id);
        $content = "";
        foreach ($orders as $order) {
            $domain = $order['domain'] . "\\";
            if ($domain == "") $domain = 'Not Domain\\';
            $content .= $order['ip'] . ":" . $order['port'] . "@" . $domain . $order['login'] . ";" . $order['password'] . "\n";
            if ($order['sub_orders']) {
                foreach ($order['sub_orders'] as $sub_order) {
                    $domain = $sub_order['domain'] . "\\";
                    if ($domain == "") $domain = 'Not Domain\\';
                    $content .= $sub_order['ip'] . ":" . $sub_order['port'] . "@" . $domain . $sub_order['login'] . ";" . $sub_order['password'] . "\n";
                }
            }
        }
        $fileName = date("d_m_Y_H_i_s") . "_export.txt";
        $headers = [
            'Content-type' => 'text/plain',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $fileName),
            'Content-Length' => strlen($content)
        ];
        return Response::make($content, 200, $headers);

    }

}
