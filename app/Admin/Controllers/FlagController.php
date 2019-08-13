<?php
namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
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

use App\Task;
use App\Observers\TaskObserver;

class FlagController extends Controller
{

    use HasResourceActions;
    protected $title = 'Update flag image';

    public function index(Content $content)
    {
        return $content
            ->header('Update flag image')
            ->description(' ')
            ->row(function (Row $row)  {

                $tab = new Tab();
                $form = new Form();
                $form->action('flag')->disablePjax();
                $tab->add('Form', $form->render());
                $row->column(12, $tab);

            });
    }

    public function update(Request $request)
    {
         $tasks=Task::all();
         foreach ($tasks as $task){
             $flag=$this->getFlag($task->ip);

             $task_=Task::find($task->id);
             $task_->flag=$flag;
             $task_->save();

         }
        return redirect()->back();
    }

    public function getFlag($ip)
    {
        $IP_DATA_KEY = env('IP_DATA_KEY');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ipdata.co/" . $ip . "?api-key=".$IP_DATA_KEY,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $response_ar = json_decode($response);
        if ($err) {
            return "";
        } else {
            if(isset($response_ar->flag)){
                return $response_ar->flag;
            }else{
                return "";
            }
        }
    }

}