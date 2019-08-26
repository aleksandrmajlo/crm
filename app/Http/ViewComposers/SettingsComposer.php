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
//        $socials= SiteSetting::getByKey('social');
//        $view->with('socials',$socials);

        $adverts = DB::table('adverts')
            ->where('id', '=', 1)
            ->first();
        $view->with('adverts',$adverts->text);


    }
}
