<?php

namespace App\Admin\Controllers;

use App\Task;
use App\User;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Content;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TaskController extends Controller
{
    use HasResourceActions;
    protected $title = 'Task';



    public function index(Content $content)
    {
        return $content
            ->header('Task')
            ->description('description')
            ->body($this->grid());
    }

    protected function grid()
    {
        $grid = new Grid(new Task);

        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $filter->like('id');
            $filter->like('ip');
            $filter->like('port');
            $filter->like('domain');
            $status=config('adm_settings.statusTask');
            $filter->equal('status')->select($status);
        });

        $grid->column('id', __('Id'))->sortable();
        $grid->column('ip', __('IP'))->sortable();
        $grid->column('port', __('Port'));
        $grid->column('domain', __('Domain'))->sortable();
        $grid->column('login', __('Login'));
        $grid->column('password', __('Password'));
        $grid->column('weight', __('Weight'))->sortable();;

        $grid->column('status', 'Status')->display(function ($statusNum)  {
            $status=config('adm_settings.statusTask');
            return $status[$statusNum];
        })->sortable();

        $grid->column('user_id', 'User')->display(function ($userId)  {
            if (isset($userId)) {
                $user=User::find($userId);
                if($user){
                    return '<a  target="_blank" class="btn btn-sm btn-default" href="/admin/users/'.$userId.'">'.$user->name.'</a>';
                }else{
                    return '-';
                }
            }
            return '-';
        })->sortable();

        $grid->column('flag','Flag')->display(function ($flag){
             if(!empty($flag)){
                 return '<img src="'.$flag.'">';
             }
        });
        $grid->column('created_at', __('Created at'));
        $grid->quickSearch('ip', 'port');
        return $grid;
    }

    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Task::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('ip', __('IP'));
        $show->field('port', __('Port'));
        $show->field('domain', __('Domain'));
        $show->field('login', __('Login'));
        $show->field('password', __('Password'));
        $show->divider();
        $show->field('weight', __('Weight'));

        $show->status()->as(function($statusNum){
            $status=config('adm_settings.statusTask');
            return $status[$statusNum];
        });
        $show->user_id()->as(function ($user_id) {
            if(isset($user_id)){
                $user=User::findOrFail($user_id);
                return $user->name;
            }else{
                 return '---';
            }

        });

        $show->field('comments', __('Comments'));
        $show->field('created_at', __('Created at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */

    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form($id)->edit($id));
    }
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());

    }

    protected function form($id=false)
    {
        $form = new Form(new Task);
        $form->ip('ip', __('IP'));
        $form->text('port', __('Port'));
        $form->text('domain', __('Domain'));
        $form->text('login', __('Login'));
        $form->text('password', __('Password'));
        $form->divider("Other");
        $form->decimal('weight', __('Weight'));
         $status=config('adm_settings.statusTask');
        $form->radio('status', __('Status'))
              ->options($status)
              ->default('1');
        $form->select('user_id', __('User'))->options(
            \App\User::all()->pluck('name','id'));
        $form->html(function ()use ($id){
            if($id){
                $task=Task::find($id);
                $comment=unserialize($task->comments) ;
                $status=$task->status;
                $html="";
                if($status==3){
                    $html.='<h2>Comment</h2>';
                    if(isset($comment["serial_number"])){
                        $html.='<p>Serial number: <strong>'.$comment["serial_number"].'</strong></p>';
                    }
                    if(isset($comment["comment"])){
                        $html.='<p>Comment: <strong>'.$comment["comment"].'</strong></p>';
                    }
                }
                if ($status==4){
                    $failed_status = config('status_coments.failed');
                    $html.='<h2>Comment</h2>';
                    if(isset($comment["select"])){
                        $html.='<p>Serial number: <strong>'.$failed_status[$comment["select"]].'</strong></p>';
                    }
                    if(isset($comment["comment"])){
                        $html.='<p>Comment: <strong>'.$comment["comment"].'</strong></p>';
                    }
                }
                return $html;
            }
            return '';
        });

        return $form;
    }
}
