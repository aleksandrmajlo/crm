<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 16.08.2019
 * Time: 15:07
 */

namespace App\Http\Controllers\Ajax;

use App\Services\TaskService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Task;

class UploadController extends Controller
{
    private $document_ext = ['txt'];

    public function store(Request $request)
    {
        $weight = $request->input('weight');
        $uploaded_file = $request->file('file');
        $original_ext = $uploaded_file->getClientOriginalExtension();


        if ($original_ext == "txt") {
            $text = file_get_contents($uploaded_file->getRealPath());
            $ar = self::parse($text, $weight);
            return response()->json([
                'success' => true,
                'tasks' => $ar,
            ], 200);
        } else {
            return response()->json(['error' => 'File extension must be .txt'], 200);
        }
    }
    // парсим строки типа таких
    //36.24.158.14:1104@XN02\Administrator;123456
    static public function parse($text, $weight)
    {
        $arrays = explode("\n", $text);
        $results = [];
        if ($arrays) {
            foreach ($arrays as $k => $array) {
                $array = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $array);
                $array = preg_replace("/([-]{3,})/", "", $array);
                if (!empty($array) && strlen($array) > 10) {
                    list($ip, $array1) = explode(':', $array, 2);
                    list($port, $array2) = explode('@', $array1, 2);
                    // поиск в строке разделителя \
                    $mystring = 'abc';
                    $findme = '\\';
                    $pos = strpos($array2, $findme);
                    if ($pos === false) {
                        /*
                         * если нет разделителя
                         * то  разбиваем по ;
                         */
                        $domain = "";
                        list($login, $password) = explode(";", $array2, 2);
                    } else {
                        // как обычно разбиваем
                        list($domain, $array3) = explode("\\", $array2, 2);
                        list($login, $password) = explode(";", $array3, 2);
                    }
                    $ip = mb_convert_encoding($ip, 'UTF-8', 'UTF-8');
                    $results[] = [
                        'weight' => $weight,
                        'ip' => $ip,
                        'port' => mb_convert_encoding($port, 'UTF-8', 'UTF-8'),
                        'domain' => mb_convert_encoding($domain, 'UTF-8', 'UTF-8'),
                        'login' => mb_convert_encoding($login, 'UTF-8', 'UTF-8'),
                        'password' => mb_convert_encoding($password, 'UTF-8', 'UTF-8'),
                    ];
                }
            }
        }
        return $results;
    }

    static public function prepareCharset($str)
    {
        mb_internal_encoding('UTF-8');
        if (empty($str)) {
            return $str;
        }
        // get charset
        $charset = mb_detect_encoding($str, array('ISO-8859-1', 'UTF-8', 'ASCII'));
        if (stristr($charset, 'utf') || stristr($charset, 'iso')) {
            $str = iconv('ISO-8859-1', 'UTF-8//TRANSLIT', utf8_decode($str));
        } else {
            $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');
        }
        $str = urldecode(str_replace("%C2%81", '', urlencode($str)));
        return $str;
    }

    // публикация записей
    public function publish(Request $request)
    {
        $uploadtasks = $request->input('uploadtask');
        $count = 0;

        $duplicate_count = "";
        $error_text = "";

        if ($uploadtasks) {
            foreach ($uploadtasks as $uploadtask) {
                // пароль может быть нулем
                if (is_null($uploadtask['password'])) {
                    $password = "";
                } else {
                    $password = $uploadtask['password'];
                }
                // домен может быть пустым
                if (is_null($uploadtask['domain'])) {
                    $domain = "";
                } else {
                    $domain = $uploadtask['domain'];
                }
                $port=$uploadtask['port'];
                // для тэста **************************************
                //$port=rand(1000,9999);

                $duplicate = Task::where('ip', $uploadtask['ip'])
                    ->where('port', $port)
                    ->where('domain', $domain)
                    ->where('login', $uploadtask['login'])
                    ->where('password', $password)
                    ->count();
                if ($duplicate > 0) {
                    $duplicate_count .= '<p class="text-warning mb-0 mt-0">Duplicate 
                                           <span>IP: ' . $uploadtask['ip'] . ' </span>
                                           <span>Port: ' . $port . '</span>
                                           <span>Domain: ' . $domain . '</span>
                                           <span>Login: ' . $uploadtask['login'] . '</span>
                                           <span>Password: ' . $password . '</span>
                                     </p>';
                    continue;
                }
                if (is_null($uploadtask['weight'])) {
                    $uploadtask['weight'] = 0;
                }
                $task = new Task;
                $task->weight = $uploadtask['weight'];
                $task->ip = $uploadtask['ip'];
                $task->port = $port;
                $task->domain = $domain;
                $task->login = $uploadtask['login'];
                $task->password = $password;
                $task->status = 1;
                $task->save();
                $count++;

                // Проверка на похожие ранее загруженные
                TaskService::DuplicateCheck($task->id);
            }
        }

        $success = '<p  class="text-primary">Add ' . $count . ' task</p>';
        return response()->json([
            'saved_duplicate_error' => $success . $duplicate_count . $error_text
        ], 200);
    }

}