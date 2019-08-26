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
            return response()->json([
                'success' => true,
                'tasks' => $ar,
            ], 200);
        }else{
              return response()->json(['error' => 'File extension must be .txt'], 200);
        }
    }

    static public function parse($text,$weight){
        $arrays = explode("\n", $text);
        $results=[];
        if($arrays){
            foreach ($arrays as $k=>$array){
                $array=preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $array);
                /*
                $str=$array;
                $str=str_replace("\r",'',$str);
                $str=trim($str);
                $array_str = str_split($str);
                foreach ($array_str as $char) {
                    var_dump($char);
                }
                dd(strlen($str));
                */
                if(!empty($array)&&strlen($array)>10){

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

       $duplicate_count="";
       $error_text="";

       if($uploadtasks){
           foreach ($uploadtasks as $uploadtask){

               $duplicate=Task::where('ip',$uploadtask['ip'])
                   ->where('port',$uploadtask['port'])
                   ->where('domain',$uploadtask['domain'])
                   ->where('login',$uploadtask['login'])
                   ->where('password',$uploadtask['password'])
                   ->count();

               if($duplicate>0){
                   $duplicate_count.='<p class="text-warning mb-0 mt-0">Duplicate 
                                           <span>IP: '.$uploadtask['ip'].' </span>
                                           <span>Port: '.$uploadtask['port'].'</span>
                                           <span>Domain: '.$uploadtask['domain'].'</span>
                                           <span>Login: '.$uploadtask['login'].'</span>
                                           <span>Password: '.$uploadtask['password'].'</span>
                                     </p>';
                   continue;
               }
               if(is_null($uploadtask['password'])){
                   $error_text.='<p class="text-danger mb-0 mt-0">Not password <span>IP: '.$uploadtask['ip'].' </span> <span>Port: '.$uploadtask['port'].'</span></p>';
                   continue;
               }
               if(is_null($uploadtask['weight'])){
                   $uploadtask['weight']=0;
               }

               // /*
               $task = new Task;
               $task->weight = $uploadtask['weight'];
               $task->ip = $uploadtask['ip'];
               $task->port = $uploadtask['port'];
               $task->domain = $uploadtask['domain'];
               $task->login = $uploadtask['login'];
               $task->password = $uploadtask['password'];
               $task->status = 1;
               $task->save();
               //*/

               $count++;
           }
       }
       $success='<p  class="text-primary">Add '.$count.' task</p>';
       return response()->json([
           'saved_duplicate_error'=>$success.$duplicate_count.$error_text
       ], 200);
    }

}