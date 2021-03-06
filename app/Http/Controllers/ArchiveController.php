<?php

namespace App\Http\Controllers;

use App\Serial;
use App\Task;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArchiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::where('role', 3)->get();
        $paginate = config('custom.paginate');
        if($request->has('id')){
            $tasks = Task::onlyTrashed()->where('id',$request->id)->orderBy('id', 'DESC')->paginate($paginate);
        }elseif ($request->has('serial')){
            $serials = Serial::onlyTrashed()->where('serial', 'LIKE', "%$request->serial%")->get();
            $tasks=[];
            if($serials){
                $ids=[];
                foreach ($serials as $serial){
                    $ids[]=$serial->task_id;
                }
            }
            $tasks = Task::onlyTrashed()->whereIn('id',$ids)->orderBy('id', 'DESC')->paginate($paginate);
        }
        else{
            $tasks = Task::onlyTrashed()->orderBy('id', 'DESC')->paginate($paginate);
        }
        $dates = DB::table('tasks')
            ->orderBy('created_at', 'DESC')
            ->whereNotNull('deleted_at')
            ->get('created_at')
            ->groupBy(function ($val) {
                return Carbon::parse($val->created_at)->format('Y-m-d');
            });
        return view('archive.index',[
            'title' => 'Archive',
            'meta_title' =>'Archive',
            'with_sidebar' => false,
            'with_content' => '12',
            'tasks' => $tasks,
            'users' => $users,
            'dates' => $dates,
            'status' => config('adm_settings.LogStatus')
        ]);
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
