<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Note;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function addNote(Request $request)
    {
        Note::create(
            [
                'comment' => $request->comment,
                'order_id' => $request->order_id,
                'task_id' => $request->task_id,
                'user_id' => Auth::user()->id,
            ]
        );
        return response()->json(
            [
                'success' => true,
            ], 200);
    }

    public function getNoteOrder(Request $request)
    {

        $order_id = $request->order_id;
        $user_id = Auth::user()->id;
        $notes=Note::where('order_id',$order_id)->where('user_id',$user_id)->get();
        return response()->json(
            [
                'notes' => $notes,
            ], 200);
    }

    public function getNoteOrderAdmin(Request $request)
    {

        $order_id = $request->order_id;
        $notes=Note::where('order_id',$order_id)->get();
        return response()->json(
            [
                'notes' => $notes,
            ], 200);
    }

}
