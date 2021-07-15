<?php


namespace App\Http\Controllers\Comment;

use App\Admincomment;
use App\Http\Controllers\Controller;
use App\Services\Log;
use Illuminate\Http\Request;
use App\Order;
use App\Task;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // вывод для подбзователя
    public function GommentFeed()
    {
        return view('comment.index', [
            'title' => trans('site.commentsFeed'),
            'meta_title' => trans('site.commentsFeed'),
            'with_sidebar' => false,
            'with_content' => '12'
        ]);
    }

    // добавляем комментарий
    public function add(Request $request)
    {

        $user_id = Auth::user()->id;
        $task_id = $request->input('task_id');
        $task = Task::find($task_id);
        $order_id = 0;
        if (isset($task->order)) {
            $order_id = $task->order->id;
        }

        $admincomment = new Admincomment;

        $admincomment->commentadmin = $request->text;
        $admincomment->showcommentadmin = $request->showcommentadmin;
        $admincomment->user_id = $user_id;
        $admincomment->order_id = $order_id;
        $admincomment->task_id = $task_id;
        $admincomment->save();

        Log::write(11, $task_id, $order_id, $task->user_id, $user_id,
            [
                'comment' => $request->text,
                'showcommentadmin' => $request->showcommentadmin
            ]
        );

        return response()->json([
            'success' => true,
        ], 200);
    }

    // получаем
    public function get(Request $request)
    {

        $results = [];
        $task_id = $request->input('task_id');
        if ($request->has('form') && $request->form == '-1') {
            $task = Task::onlyTrashed()->find($task_id);
        } else {
            $task = Task::find($task_id);
        }

        if ($task->admincomments) {
            foreach ($task->admincomments as $admincomment) {
                $admin = "";
                if ($admincomment->user) {
                    $admin = $admincomment->user->email . ' ' . $admincomment->user->fullname;
                }
                $results[] = [
                    'text' => $admincomment->commentadmin,
                    'date' => $admincomment->created_at->format('Y-m-d H:i:s'),
                    'admin' => $admin
                ];
            }
        }
        //dump($results);
        return response()->json([
            'results' => $results,
            'success' => true,
        ], 200);
    }
}
