<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $r=rand(1, 16);
//        $date = Carbon::now()->subDays($r);
        $date = Carbon::now();
        DB::table('tasks')->insert([
//            'ip' =>  $randIP = "".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255),
            'ip' =>'71.103.238.230',
            'port' => mt_rand(1000, 9999),
            'domain' => Str::random(4),
            'login' => Str::random(5),
            'password' => Str::random(6),
            'weight' => $this->rand_float(0,100),
            'status'=>1,
            'created_at' => $date,

        ]);
    }

    public function rand_float($st_num=0,$end_num=1,$mul=1000000)
    {
        if ($st_num>$end_num) return false;
        return mt_rand($st_num*$mul,$end_num*$mul)/$mul;
    }
}
