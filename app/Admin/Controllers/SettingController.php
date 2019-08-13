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
                $form->action('site-settings')->disablePjax();
                $this->indexSocials($form,$siteSettings);
                $tab->add('Social', $form->render());


//                $form = new Form();
//                $header_img=SiteSetting::getByKey('header_img');
//                $form->action('site-settings')->disablePjax();
//                $form->image('header_img','Header Image');
//                $tab->add('Photo', $form->render());


                $row->column(12, $tab);

            });

    }


    protected function indexSocials($form)
    {
        $siteSettings= SiteSetting::getByKey('social');
        foreach (['Twitter','Facebook', 'Instagram' ] as $key => $network) {
            $form->text('social[' . $key . '][link]', $network . ' link')
                ->default(
                    isset($siteSettings[$key]['link'])
                        ? $siteSettings[$key]['link']
                        : ''
                );
        }
    }
    public function update(Request $request)
    {
        SiteSetting::updateByKey( $request->except(['_token']));
        return redirect()->back();
    }

}
