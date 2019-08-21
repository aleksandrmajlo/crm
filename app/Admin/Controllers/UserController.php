<?php

namespace App\Admin\Controllers;

use App\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;


use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Content;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    use HasResourceActions,RegistersUsers;
    protected $title = 'Users';
    public function index(Content $content)
    {
        return $content
            ->header('Worker')
            ->description('description')
            ->body($this->grid());
    }

    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form(true,$id)->edit($id));
    }
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());

    }
    public function store(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->addUser($request->all())));
        return redirect('/admin/users/' . $user->id . '');
    }
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User);
        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $filter->like('name');
            $filter->like('email');
            $roles=config('user_roles.roles');
            $filter->equal('role')->select($roles);
        });

        $grid->column('id', __('Id'))->sortable();

        $grid->column('name', __('Name'))->sortable();
        $grid->column('email', __('Email'))->sortable();

        $grid->column('fullname')->display(function () {
            return $this->fullname;
        });

//        $grid->column('id', __('History order'))->display(function ($id){
//            return '<a  target="_blank" href="/admin/history/?user='.$id.'"  >View</a>';
//        });

        $grid->column('created_at', __('Created at'));

        $grid->quickSearch('name','email');

        $grid->disableExport();
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
        $show = new Show(User::findOrFail($id));
        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('created_at', __('Created at'));
        return $show;
    }
    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($edit = false,$id=false)
    {
        $form = new Form(new User);

        $form->hidden('id');

        $form->tab('Info', function (Form $form) use ($edit,$id) {
            if ($edit) {
                $form->text('name', __('Name'))->readonly();
                $form->email('email', __('Email'))->readonly();
                $form->text('password', __('Password'));
            } else {
                $form->text('name', __('Name'))->required();
                $form->email('email', __('Email'))->required();
                $form->text('password', __('Password'))->required();
            }
            $roles=config('user_roles.roles');
            $form->select('role','Roles')->options($roles);
            $form->radio('status', 'Status')->options(['0' => 'Off', '1'=> 'On'])->default('0');

            $form->text('weight', __('Weight'));
            $form->radio('blind', 'Blind')->options(['0' => 'Off', '1'=> 'On'])->default('0');
            $form->color('color', "Color");

        });
        $form->tab('Info 2', function (Form $form) use ($edit) {
            $form->text('last_name', __('Last name'));
            $form->text('first_name', __('First name'));
            $form->text('middle_name', __('Middle name'));
            $form->mobile('phone', __('Phone'))
                ->options(['mask' => '+9 999 9999 9999']);
        });
        if ($edit) {
            $form->setAction('/admin/usersEdit');
        }
        $form->tools(function (Form\Tools $tools) {
            $tools->disableList();
            $tools->disableDelete();
            if(session('status')){
                $tools->add('<div class="alert-success alert pull-right">Profile updated</div>');
            }
        });
        $form->footer(function ($footer) {
            $footer->disableReset();
            $footer->disableViewCheck();
            $footer->disableEditingCheck();
            $footer->disableCreatingCheck();
        });
        return $form;
    }

    protected function validator(array $data)
    {
        return Validator::make(
            $data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

    }
    protected function addUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'phone' => $data['phone'],
            'role' => $data['role'],
            'weight' => $data['weight'],
            'blind' => $data['blind'],
            'color' => $data['color'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function editTask(Request $request)
    {

        $user=User::findOrFail($request->id);
        if ($request->password) {
            $validatedData = $request->validate([
                'password' => ['required', 'string', 'min:8'],
            ]);
            $user->password=Hash::make($request->password);
        }
        $user->last_name=$request->last_name;
        $user->first_name=$request->first_name;
        $user->middle_name=$request->middle_name;
        $user->phone=$request->phone;
        $user->status=$request->status;
        $user->role=$request->role;
        $user->weight=$request->weight;
        $user->blind=$request->blind;
        $user->color=$request->color;
        $user->save();
        return redirect('/admin/users/' . $user->id . '/edit')->with('status', 'Profile updated!');
    }


    public function userhistory(){

        return response()->json(['notorder' =>"1111111"], 200);
    }


}
