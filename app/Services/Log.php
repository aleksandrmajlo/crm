<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 05.11.2019
 * Time: 14:31
 */

namespace App\Services;

use \App\Orderlog;

class Log
{

    /*
     * $order model
     */
    static public function write($status, $task_id, $order_id = null, $user_id = null, $admin_id = null, $serilize = null)
    {

        switch ($status) {

            case 1:
                  static::User_to_work($status, $task_id, $order_id, $user_id,$admin_id,$serilize);
                break;

            case 2:
                  static::User_set_status_done($status, $task_id, $order_id, $user_id,$admin_id,$serilize);
                break;

            case 3:
                  static::User_set_status_failed($status, $task_id, $order_id, $user_id,$admin_id,$serilize);
                break;

            case 4:
                  static::User_update_status_done_to_done($status, $task_id, $order_id, $user_id,$admin_id,$serilize);
                break;

            case 5:
                  static::User_change_status_done_to_failed($status, $task_id, $order_id, $user_id,$admin_id,$serilize);
                break;

            case 6:
                  static::User_update_status_failed_to_failed($status, $task_id, $order_id, $user_id,$admin_id,$serilize);
                break;

            case 7:
                  static::User_change_status_failed_to_done($status, $task_id, $order_id, $user_id,$admin_id,$serilize);
                break;

            case 8:
                  static::User_comment_admin_to_read($status, $task_id, $order_id, $user_id,$admin_id,$serilize);
                break;

            case 9:
                  static::Admin_change_status_to_free($status, $task_id, $order_id, $user_id,$admin_id,$serilize);
                break;

            case 10:
                  static::Admin_change_status_to_work($status, $task_id, $order_id, $user_id,$admin_id,$serilize);
                break;

            case 11:
                  static::Admin_add_comment($status, $task_id, $order_id, $user_id,$admin_id,$serilize);
                break;

            case 12:
                  static::Admin_upload_and_change_status_failed_to_free($status, $task_id, $order_id, $user_id,$admin_id,$serilize);
                break;

            case 13:
                  static::Admin_upload_and_change_status_done_to_work($status, $task_id, $order_id, $user_id,$admin_id,$serilize);
                break;

            case 14:
                  static::Admin_upload_and_attached_to_done($status, $task_id, $order_id, $user_id,$admin_id,$serilize);
                break;

            case 15:
                  // не создаем новую
                  static::Admin_upload_and_attached_to_done($status, $task_id, $order_id, $user_id,$admin_id,$serilize);
                break;


        }


    }

    // 1 User:  to work
    /*
     * serilize => text возможно
     */
    public static function User_to_work($status, $task_id, $order_id, $user_id,$admin_id,$serilize)
    {
        $orderlog = new Orderlog;
        $orderlog->status = $status;
        $orderlog->task_id = $task_id;
        $orderlog->order_id = $order_id;
        $orderlog->user_id = $user_id;
        if(!is_null($serilize)){
            $orderlog->text = serialize($serilize);
        }
        $orderlog->save();

    }


    // 2 User: set status failed
    /*
     * serilize => failedstatus comment
     */
    public static function User_set_status_done($status, $task_id, $order_id, $user_id,$admin_id,$serilize)
    {
        $orderlog = new Orderlog;
        $orderlog->status = $status;
        $orderlog->task_id = $task_id;
        $orderlog->order_id = $order_id;
        $orderlog->user_id = $user_id;
        if(!is_null($serilize)){
            $orderlog->text = serialize($serilize);
        }
        $orderlog->save();
    }

    // 3 User: set status failed
    /*
     * serilize => failedstatus comment
     */
    public static function User_set_status_failed($status, $task_id, $order_id, $user_id,$admin_id,$serilize)
    {
        $orderlog = new Orderlog;
        $orderlog->status = $status;
        $orderlog->task_id = $task_id;
        $orderlog->order_id = $order_id;
        $orderlog->user_id = $user_id;
        if(!is_null($serilize)){
            $orderlog->text = serialize($serilize);
        }
        $orderlog->save();

    }

    // 4 User: set status failed
    /*
     *
     */
    public static function User_update_status_done_to_done($status, $task_id, $order_id, $user_id,$admin_id,$serilize)
    {
        $orderlog = new Orderlog;
        $orderlog->status = $status;
        $orderlog->task_id = $task_id;
        $orderlog->order_id = $order_id;
        $orderlog->user_id = $user_id;
        if(!is_null($serilize)){
            $orderlog->text = serialize($serilize);
        }
        $orderlog->save();

    }

    // 5 User: set status failed
    /*
     *
     */
    public static function User_change_status_done_to_failed($status, $task_id, $order_id, $user_id,$admin_id,$serilize)
    {
        $orderlog = new Orderlog;
        $orderlog->status = $status;
        $orderlog->task_id = $task_id;
        $orderlog->order_id = $order_id;
        $orderlog->user_id = $user_id;
        if(!is_null($serilize)){
            $orderlog->text = serialize($serilize);
        }
        $orderlog->save();

    }

    // 6 User: set status failed
    /*
     *
     */
    public static function User_update_status_failed_to_failed($status, $task_id, $order_id, $user_id,$admin_id,$serilize)
    {
        $orderlog = new Orderlog;
        $orderlog->status = $status;
        $orderlog->task_id = $task_id;
        $orderlog->order_id = $order_id;
        $orderlog->user_id = $user_id;
        if(!is_null($serilize)){
            $orderlog->text = serialize($serilize);
        }
        $orderlog->save();

    }

    // 7 User: set status failed
    /*
     *
     */
    public static function User_change_status_failed_to_done($status, $task_id, $order_id, $user_id,$admin_id,$serilize)
    {
        $orderlog = new Orderlog;
        $orderlog->status = $status;
        $orderlog->task_id = $task_id;
        $orderlog->order_id = $order_id;
        $orderlog->user_id = $user_id;
        if(!is_null($serilize)){
            $orderlog->text = serialize($serilize);
        }
        $orderlog->save();

    }

    // 8 User: set status failed
    /*
     *
     */
    public static function User_comment_admin_to_read($status, $task_id, $order_id, $user_id,$admin_id,$serilize)
    {
        $orderlog = new Orderlog;
        $orderlog->status = $status;
        $orderlog->task_id = $task_id;
        $orderlog->order_id = $order_id;
        $orderlog->user_id = $user_id;
        $orderlog->admin_id = $admin_id;
        if(!is_null($serilize)){
            $orderlog->text = serialize($serilize);
        }
        $orderlog->save();

    }

    // 9
    /*
     *
     */
    public static function Admin_change_status_to_free($status, $task_id, $order_id, $user_id,$admin_id,$serilize)
    {
        $orderlog = new Orderlog;
        $orderlog->status = $status;
        $orderlog->task_id = $task_id;
        $orderlog->order_id = $order_id;
        $orderlog->admin_id = $admin_id;
        if(!is_null($serilize)){
            $orderlog->text = serialize($serilize);
        }
        $orderlog->save();

    }

    // 10
    /*
     *
     */
    public static function Admin_change_status_to_work($status, $task_id, $order_id, $user_id,$admin_id,$serilize)
    {
        $orderlog = new Orderlog;
        $orderlog->status = $status;
        $orderlog->task_id = $task_id;
        $orderlog->order_id = $order_id;
        $orderlog->user_id = $user_id;
        $orderlog->admin_id = $admin_id;
        if(!is_null($serilize)){
            $orderlog->text = serialize($serilize);
        }
        $orderlog->save();
    }

    // 11
    /*
     *
     */
    public static function Admin_add_comment($status, $task_id, $order_id, $user_id,$admin_id,$serilize)
    {
        $orderlog = new Orderlog;
        $orderlog->status = $status;
        $orderlog->task_id = $task_id;
        $orderlog->order_id = $order_id;
        $orderlog->user_id = $user_id;
        $orderlog->admin_id = $admin_id;
        if(!is_null($serilize)){
            $orderlog->text = serialize($serilize);
        }
        $orderlog->save();
    }

    // 12
    /*
     *
     */
    public static function Admin_upload_and_change_status_failed_to_free($status, $task_id, $order_id, $user_id,$admin_id,$serilize)
    {
        $orderlog = new Orderlog;
        $orderlog->status = $status;
        $orderlog->task_id = $task_id;
        $orderlog->order_id = $order_id;
        $orderlog->user_id = $user_id;
        $orderlog->admin_id = $admin_id;
        if(!is_null($serilize)){
            $orderlog->text = serialize($serilize);
        }
        $orderlog->save();
    }

    // 13
    /*
     *
     */
    public static function Admin_upload_and_change_status_done_to_work($status, $task_id, $order_id, $user_id,$admin_id,$serilize)
    {
        $orderlog = new Orderlog;
        $orderlog->status = $status;
        $orderlog->task_id = $task_id;
        $orderlog->order_id = $order_id;
        $orderlog->user_id = $user_id;
        $orderlog->admin_id = $admin_id;
        if(!is_null($serilize)){
            $orderlog->text = serialize($serilize);
        }
        $orderlog->save();
    }

    // 14
    /*
     *
     */
    public static function Admin_upload_and_attached_to_done($status, $task_id, $order_id, $user_id,$admin_id,$serilize)
    {
        $orderlog = new Orderlog;
        $orderlog->status = $status;
        $orderlog->task_id = $task_id;
        $orderlog->order_id = $order_id;
        $orderlog->user_id = $user_id;
        $orderlog->admin_id = $admin_id;
        if(!is_null($serilize)){
            $orderlog->text = serialize($serilize);
        }
        $orderlog->save();
    }

}