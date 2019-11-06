<?php

namespace App\Admin\Controllers;

use App\Order;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Content;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Tab;

use View;

use \App\User;
use \App\Task;
use \App\Services\OrderAdmin;


class OrderController extends Controller
{
    use HasResourceActions;
    protected $title = 'Order';


    public function index(Content $content)
    {
        return $content
            ->header('Order')
            ->description('description')
            ->body($this->grid());
    }

    protected function grid()
    {
        $grid = new Grid(new Order);

        $grid->column('id', __('Id'))->sortable();

        $grid->column('user_id', 'User')->display(function ($userId) {
            if (isset($userId)) {
                $user = User::find($userId);
                if ($user) {
                    return '<a  target="_blank" class="btn btn-sm btn-default" href="/admin/users/' . $userId . '">' . $user->name . '</a>';
                } else {
                    return '-';
                }
            }
            return '-';
        })->sortable();

        $grid->column('task_id', 'Task')->display(function ($taskId) {
            if (isset($taskId)) {
                $task = Task::find($taskId);
                if ($task) {
                    return '<a  target="_blank" class="btn btn-sm btn-default" href="/admin/tasks/' . $taskId . '/edit">' . $taskId . '</a>';
                } else {
                    return '-';
                }
            }
            return '-';
        })->sortable();

        $grid->column('status', 'Status')->display(function ($statusNum) {
            $status = config('order_status.orderstatus');
            return $status[$statusNum];
        })->sortable();
        $grid->column('type', __('Type'))->display(function ($statusNum) {
            $type = config('order_status.typeorder');
            return $type[$statusNum];
        })->sortable();
        $grid->column('created_at', __('Created'))->sortable();;
        $grid->column('updated_at', __('End date'))->sortable();;
        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $filter->like('id');
            $filter->like('task_id');
            $status = config('order_status.orderstatus');

            $filter->equal('status')->select($status);

            $typeorder = config('order_status.typeorder');
            $filter->equal('type')->select($typeorder);

            $filter->equal('user_id', 'User')
                ->select(User::all()->pluck('name', 'id'));

            $filter->date('created_at', 'Created');
            $filter->date('updated_at', 'End date');

        });

        $grid->quickSearch('task_id');

        $grid->model()->orderBy('id', 'desc');
        $grid->paginate(100);

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
        $show = new Show(Order::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('task_id', __('Task id'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }


    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form($id)->edit($id));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($id = false)
    {
//        dd($id);
        $tab = new Tab();

        $form = new Form(new Order);
        $form->select('user_id', __('User'))->options(
            User::all()->pluck('name', 'id'))->readOnly();
        $form->text('task_id', __('Task id'))->readOnly();
        $status = config('order_status.orderstatus');
        $form->radio('status', __('Status'))
            ->options($status)
            ->default('1');
        $type = config('order_status.typeorder');
        $form->radio('type', __('Type'))
            ->options($type)
            ->default('1')->readOnly();
        $form->text('parent_id', __('Parent id'))->readOnly();
        $form->display('created_at', 'Created time');
        $form->display('updated_at', 'Updated time');
        $form->tools(function (Form\Tools $tools) {
            $tools->disableList();
            $tools->disableView();
        });
        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableSubmit();
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });

        $errors=config('status_coments.failed');
        $tab->add('Info', $form->render());
        $order = OrderAdmin::getInfo($id);
//        dd($order->serials);
        ob_start();
        echo View::make('admin.orderInfo', array(
                'order' => $order,
                'status' => $status,
                'type' => $type,
                'errors' => $errors,
            )
        );
        $html = ob_get_clean();
        $tab->add('Other', $html);
        return $tab;

    }
}
