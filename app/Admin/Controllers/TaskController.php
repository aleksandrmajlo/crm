<?php

namespace App\Admin\Controllers;

use App\Task;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TaskController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\Task';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Task);

        $grid->column('id', __('Id'));
        $grid->column('ip', __('Ip'));
        $grid->column('port', __('Port'));
        $grid->column('domain', __('Domain'));
        $grid->column('login', __('Login'));
        $grid->column('password', __('Password'));
        $grid->column('weight', __('Weight'));
        $grid->column('status', __('Status'));
        $grid->column('user_id', __('User id'));
        $grid->column('comments', __('Comments'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
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
        $show->field('ip', __('Ip'));
        $show->field('port', __('Port'));
        $show->field('domain', __('Domain'));
        $show->field('login', __('Login'));
        $show->field('password', __('Password'));
        $show->field('weight', __('Weight'));
        $show->field('status', __('Status'));
        $show->field('user_id', __('User id'));
        $show->field('comments', __('Comments'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Task);

        $form->ip('ip', __('Ip'));
        $form->text('port', __('Port'));
        $form->text('domain', __('Domain'));
        $form->text('login', __('Login'));
        $form->password('password', __('Password'));
        $form->decimal('weight', __('Weight'));
        $form->number('status', __('Status'));
        $form->number('user_id', __('User id'));
        $form->textarea('comments', __('Comments'));

        return $form;
    }
}
