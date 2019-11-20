<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    public $timestamps = false;

    protected $fillable = [ 'key', 'value', 'serialized'];

    public static function updateByKey( $data)
    {

        self::query()->delete();
        $settings = [];
        if(isset($data["side_serials"])){
            $settings[] = [
                'key' => 'side_serials',
                'value' => serialize($data),
                'serialized' => 1
            ];
        }else{
            foreach ($data as $key => $value) {
                $settings[] = [
                    'key' => $key,
                    'value' => is_array($value) ? serialize($value) : $value,
                    'serialized' => is_array($value) ? 1 : 0
                ];
            }
        }
        self::insert($settings);
    }

    public static function getByKey($key)
    {
        $setting = self::where('key', '=', $key)->first();
        if (isset($setting)) {
            return ($setting->serialized === 1) ? unserialize($setting->value) : $setting->value;
        }
        return;
    }

}
