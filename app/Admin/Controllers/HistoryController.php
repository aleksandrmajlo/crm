<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 13.08.2019
 * Time: 23:26
 */

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\SiteSetting;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Form;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\MessageBag;

use App\User;


class HistoryController extends Controller
{

    use HasResourceActions;
    protected $title = 'History';

    public function index(Content $content,Request $request){
        $user_id=$request->get('user');
        if(isset($user_id)){
            $user=User::find($user_id);
            $content->header('History order user: '.$user->name);
            $content->view('admin.history', [
                'user'=>$user
            ]);
            return $content;
        }
    }

}