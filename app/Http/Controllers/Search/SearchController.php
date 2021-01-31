<?php
namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use App\Task;
use App\Order;
use App\User;
use App\Services\OrderService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class SearchController extends Controller
{
    public function ip(Request $request){
        $results=[];
        $ip=$request->input('ip');
        if(!empty($ip)){
            $tasks=Task::where('status','<',3)->where('ip','LIKE', "%$ip%")->orderBy('id', 'desc')->get();
            if($tasks){
                foreach ($tasks as $task){
                    $results[]=[
                        'id'=>$task->id,
                        'ip'=>$task->ip,
                        'port'=>$task->port,
                        'domain'=>$task->domain,
                        'login'=>$task->login,
                        'password'=>$task->password,
                        'weight'=>$task->weight,
                        'status'=>$task->status,
                        'user_id'=>$task->user_id,
                        'flag'=>$task->flag,
                        'created_at'=>$task->created_at,
                    ];
                }
            }
        }
        return response()->json([
            'success'=>true,
            'tasks'=>$results
        ], 200);

    }
}
