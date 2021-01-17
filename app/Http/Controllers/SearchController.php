<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;
use App\Order;
use App\User;
use App\Serial;
use App\Services\OrderService;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class SearchController extends Controller
{
    public function index(Request $request)
    {

        $data = [
            'title' => trans('search.title'),
            'meta_title' => trans('search.meta_title'),
            'with_sidebar' => false,
            'with_content' => '12',
            'value' => '',
            'value_id' => '',
            'status' => config('adm_settings.statusTask'),
            'serial' => false,
            'task' => false,
        ];
        if ($request->has('q')) {
            $q = $name = $request->input('q');
            $data['value'] = $q;
            $serial = Serial::where('serial', 'LIKE', "%$q%")->first();
            $data['serial'] = $serial;
            if ($serial) {
                $data['serial_other'] = Serial::where('order_id', $serial->order_id)
                    ->where('id', '!=', $serial->id)
                    ->get();
            }
        }
        if ($request->has('id')) {
            $id = $request->input('id');
            $data['value_id'] = $id;
            $data['task'] = Task::findOrFail($id);
        }
        return view('search.index', $data);
    }

    public function searchdate(Request $request)
    {

        $data = [
            'title' => trans('search.title'),
            'meta_title' => trans('search.meta_title'),
            'with_sidebar' => false,
            'with_content' => '12',
            'value' => '',
            'status' => config('adm_settings.statusTask'),
            'failed' => config('status_coments.failed'),
            'works' => [],
            'dones' => [],
            'faileds' => [],
            'start' => '',
            'end' => '',
        ];

        if ($request->has('start') && $request->has('end')) {

            $start = $request->input('start');
            $end = $request->input('end');
            $data['start'] = $start;
            $data['end'] = $end;

            if ($request->has('user') && $request->input('user') !== '-1') {
                $user_id = $request->user;
                $data['works'] = Order::whereBetween('created_at', [
                    $start . " 00:00:00",
                    $end . " 23:59:59"])
                    ->where('status', 1)
                    ->where('user_id', $user_id)
                    ->orderBy('task_id', 'desc')
                    ->get();
                $data['dones'] = Order::whereBetween('updated_at', [
                    $start . " 00:00:00",
                    $end . " 23:59:59"])
                    ->where('status', 3)
                    ->where('user_id', $user_id)
                    ->orderBy('task_id', 'desc')
                    ->get();
                $data['faileds'] = Order::whereBetween('updated_at', [
                    $start . " 00:00:00",
                    $end . " 23:59:59"])
                    ->where('status', 4)
                    ->where('user_id', $user_id)
                    ->orderBy('task_id', 'desc')
                    ->get();

            } else {
                $data['works'] = Order::whereBetween('created_at', [
                    $start . " 00:00:00",
                    $end . " 23:59:59"])
                    ->where('status', 1)
                    ->orderBy('task_id', 'desc')
                    ->get();
                $data['dones'] = Order::whereBetween('updated_at', [
                    $start . " 00:00:00",
                    $end . " 23:59:59"])
                    ->where('status', 3)
                    ->orderBy('task_id', 'desc')
                    ->get();
                $data['faileds'] = Order::whereBetween('updated_at', [
                    $start . " 00:00:00",
                    $end . " 23:59:59"])
                    ->where('status', 4)
                    ->orderBy('task_id', 'desc')
                    ->get();
            }

        } else {
            $data['works'] = Order::where('status', 1)
                ->get();
            $data['dones'] = Order::where('status', 3)
                ->get();
            $data['faileds'] = Order::where('status', 4)
                ->get();
        }
        return view('search.searchDate', $data);
    }


    public function searchIP(Request $request)
    {
        $data = [
            'title' => trans('search.titleIP'),
            'meta_title' => trans('search.meta_titleIP'),
            'with_sidebar' => false,
            'with_content' => '12',
            'value' => '',
            'value_id' => '',
            'status' => config('adm_settings.statusTask'),
            'tasks' => false,
        ];
        if ($request->has('q')) {
            $ip = $request->input('q');
            $data['value'] = $ip;
            $data['tasks'] = Task::where('ip', '=', $ip)->get();
        }
        return view('search.searchIP', $data);
    }


}
