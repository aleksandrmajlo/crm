<?php

namespace App\Http\ViewComposers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;
use \App\SiteSetting;

class SettingsComposer
{
    public function compose(View $view)
    {
        $socials= SiteSetting::getByKey('social');
        $view->with('socials',$socials);
    }
}
