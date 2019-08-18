<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 16.08.2019
 * Time: 15:07
 */

namespace App\Http\Controllers\Ajax;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Task;

class UploadController extends Controller
{
    private $document_ext = ['txt'];

    public function store(Request $request)
    {
        $weight=$request->input('weight');
        $uploaded_file = $request->file('file');
        $original_ext = $uploaded_file->getClientOriginalExtension();
        if($original_ext=="txt"){
            $text=file_get_contents($uploaded_file->getRealPath());
            $ar=self::parse($text,$weight);
            return response()->json(['success' => $ar], 200);
        }else{
              return response()->json(['error' => 'File extension must be .txt'], 200);
        }
    }
    static public function parse($text,$weight){
        $arrays = explode("\n", $text);
        $results=[];
        if($arrays){
            foreach ($arrays as $array){
                if(!empty($array)){
                    list($ip,$array1)=explode(':',$array,2);
                    list($port,$array2)=explode('@',$array1,2);
                    list($domain,$array3)=explode("\\",$array2,2);
                    list($login,$password)=explode(";",$array3,2);
                    $results[]=[
                        'weight'=>$weight,
                        'ip'=> $ip,
                        'port'=> $port,
                        'domain'=> $domain,
                        'login'=> $login,
                        'password'=> $password,
                    ];
                }
            }
        }
        return $results;
    }

    public function uploadsave(Request $request){
       $uploadtasks=$request->input('uploadtask');
       $count=0;
       $duplicate_count=0;
       if($uploadtasks){
           foreach ($uploadtasks as $uploadtask){

               $duplicate=Task::where('ip',$uploadtask['ip'])
                   ->where('port',$uploadtask['port'])
                   ->count();
               if($duplicate>0){
                   $duplicate_count++;
                   continue;
               }

               $task = new Task;
               $task->weight = $uploadtask['weight'];
               $task->ip = $uploadtask['ip'];
               $task->port = $uploadtask['port'];
               $task->domain = $uploadtask['domain'];
               $task->login = $uploadtask['login'];
               $task->password = $uploadtask['password'];
               $task->status = 1;
               $task->save();
               $count++;
           }
       }
       return response()->json([
           'success' => 'Add '.$count.' task',
           'duplicate_count'=>'Duplicate count '.$duplicate_count
       ], 200);
    }

}