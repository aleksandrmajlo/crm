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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;


class BecupController extends Controller
{
    use HasResourceActions;
    protected $title = 'Becup';

    public function index(Content $content)
    {

        $content->header('Becup');
        $dir = 'becup/mysql';
        $becups = Storage::allFiles($dir);

        $content->view('admin.becup', [
            'becups' => $becups
        ]);
        return $content;
    }

    public function update(Request $request)
    {
        $res = Artisan::call('backup:run');

    }
}
