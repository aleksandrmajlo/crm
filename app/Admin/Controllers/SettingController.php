<?php

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

class SettingController extends Controller
{
    use HasResourceActions;
    protected $title = 'Site Setting';

    public function index(Content $content)
    {

        return $content
            ->header('Site Setting')
            ->description(' ')
            ->row(function (Row $row)  {
                $siteSettings=\App\SiteSetting::all();
                $tab = new Tab();
                $form = new Form();
                $form->action('other')->disablePjax();
                $this->indexSocials($form,$siteSettings);
                $tab->add('Side serials', $form->render());
                $row->column(12, $tab);
            });

    }


    protected function indexSocials($form)
    {

        $siteSettings= SiteSetting::getByKey('side_serials');
        $side_serials_def='s';
        $count_def=5;
        if(isset($siteSettings)){
            $side_serials_def=$siteSettings['side_serials'];
            $count_def=$siteSettings['count'];
        }

        $form->radio( 'side_serials','Side')->options(['s' => 'Start', 'e'=> 'End'])->default($side_serials_def);
        $form->number('count','Count')->default($count_def);
    }
    public function update(Request $request)
    {
        SiteSetting::updateByKey( $request->except(['_token']));
        return redirect()->back();
    }

}
