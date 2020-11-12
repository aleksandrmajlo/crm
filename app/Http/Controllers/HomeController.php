<?php

namespace App\Http\Controllers;

use App\Admincomment;
use App\Services\DashboardService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $limit=20;
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (Auth::user()->role==1){
            $offset=0;
            if($request->has('offset')){
                $offset=$request->offset;
                return response()->json([
                    'comments'=>Admincomment::offset($offset)->limit($this->limit)->orderBy('created_at','desc')->get(),
                    'users'=>User::where('status',1)->get(),
                    'success'=>true,
                ]);
            }
            $data=[
                'title'=>'Dashboard',
                'meta_title'=>'Dashboard',
                'with_sidebar'=>false,
                'with_content'=>'12',
                'statistics'=>DashboardService::getStatistics(),
            ];
            return view('dashboard',$data);
        }
        else{
            $home=\App\Infopage::where('alias','home')->first();
            $IMAGE_HIDDEN=env("IMAGE_HIDDEN", false);
            $image=!empty($home->image) ? $home->image : null;
            if($IMAGE_HIDDEN){
                $image=false;
            }
            return view('home',[
                'text'=>$home->text,
                'image'=> $image ,
                'title'=>$home->h1,
                'meta_title'=>$home->meta_title,
                'meta_description'=>$home->meta_description,
            ]);
        }
    }
}
