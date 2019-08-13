<?php

namespace App\Admin\Controllers;

use App\Post;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PostController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Post';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Post);

        $grid->filter(function (Grid\Filter $filter) {
            $filter->disableIdFilter();
            $filter->like('id');
            $filter->like('title');
            $filter->like('text');
        });
        $grid->column('id', __('Id'));
        $grid->column('slug', __('Slug'));
        $grid->column('title', __('Title'));
        $grid->column('meta_title', __('Meta title'));
        $grid->column('meta_description', __('Meta description'));
        $grid->column('created_at', __('Created at'));

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
        $show = new Show(Post::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('slug', __('Slug'));
        $show->field('title', __('Title'));
        $show->field('meta_title', __('Meta title'));
        $show->field('meta_description', __('Meta description'));
        $show->field('created_at', __('Created at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Post);
        $form->text('title', __('Title'));
        $form->ckeditor('text', __('Text'));
        $form->image('image', __('Image'));
        $form->text('meta_title', __('Meta title'));
        $form->textarea('meta_description', __('Meta description'));

        return $form;
    }
}
