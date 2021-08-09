<?php

/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 23.08.2019
 * Time: 0:09
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class AdvertController
{
    use HasResourceActions;
    protected $title = 'Advert';

    public function index(Content $content)
    {

        return $content
            ->header('Advert')
            ->description(' ')
            ->row(function (Row $row) {

                $adverts = DB::table('adverts')
                    ->where('id', '=', 1)
                    ->first();
                $form = new Form();
                $header_img = SiteSetting::getByKey('header_img');
                $form->action('advert')->disablePjax();
                $form->ckeditor('text', 'Text')->default($adverts->text);
                $row->column(12, $form->render());
            });
    }

    public function update(Request $request)
    {
        $text = $request->input('text');
        DB::table('adverts')
            ->where('id', 1)
            ->update(['text' => $text]);
        return redirect()->back();
    }
}
