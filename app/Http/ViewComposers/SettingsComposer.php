<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use \App\SiteSetting;


class SettingsComposer
{
    public function compose(View $view)
    {

        $siteSettings= SiteSetting::getByKey('side_serials');
        $side_serials=[
            'side_serials_def'=>'s',
            'count_def'=>5
        ];
        if(isset($siteSettings)){
            $side_serials=[
                'side_serials_def'=>$siteSettings['side_serials'],
                'count_def'=>$siteSettings['count']
            ];
        }

        $adverts = DB::table('adverts')
            ->where('id', '=', 1)
            ->first();
        $view->with('adverts',$adverts->text);

        $view->with('side_serials',$side_serials);



    }
}
