<?php

namespace common\components;

use yii\base\Model;

class Helper extends Model
{
    /**
     * @param $time
     * @return float|int
     */
    public static function getSecondsInTime($time)
    {
        $seconds = 0;
        $arr = explode(':', $time);
        $seconds += $arr[0] * 60 * 60;
        $seconds += $arr[1] * 60;
        return $seconds;
    }

    /**
     * @param $time
     * @return int|string
     */
    public static function getTimeAsString($time)
    {
        if($time) {
            $hours = floor($time / 60 / 60);
            $diff = $time - $hours * 60 * 60;
            $minutes = floor($diff / 60);
            return str_pad($hours, 2, 0, STR_PAD_LEFT).':'.str_pad($minutes, 2, 0, STR_PAD_LEFT);
        }
        return 0;
    }

    public static function formatTimeFromHours($hours)
    {
        $time = str_pad($hours, 2, '0', STR_PAD_LEFT);
        $time = $time.':00';
        return self::getSecondsInTime($time);
    }
}
