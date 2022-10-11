<?php

namespace common\components;

use phpDocumentor\Reflection\PseudoTypes\NonEmptyLowercaseString;
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
        if ($time) {
            $hours = floor($time / 60 / 60);
            $diff = $time - $hours * 60 * 60;
            $minutes = floor($diff / 60);
            return str_pad($hours, 2, 0, STR_PAD_LEFT) . ':' . str_pad($minutes, 2, 0, STR_PAD_LEFT);
        }
        return 0;
    }

    public static function formatTimeFromHours($hours)
    {
        $time = str_pad($hours, 2, '0', STR_PAD_LEFT);
        $time = $time . ':00';
        return self::getSecondsInTime($time);
    }
    public static function formatTimeFromHoursString($hours)
    {
        if(preg_match('/:/', $hours)) {
            return $hours;
        }
        $time = str_pad($hours, 2, '0', STR_PAD_LEFT);
        $time = $time . ':00';
        return $time;
    }

    /**
     * приводит номер телефона в формат без скобочек и т.д.
     */
    public static function phoneFormat($phone, $plus = false)
    {
        if ($phone) {
            $str = str_replace('-', '', $phone);
            $str = str_replace('(', '', $str);
            $str = str_replace(')', '', $str);
            $str = str_replace('_', '', $str);
            $str = str_replace(' ', '', $str);
            return $plus ? "+" . $str : $str;
        }
        return '';
    }
    /**
     * @return array
     */
    public static function getTimesArrayOLD()
    {
        $hourBegin = 9;
        $countHours = 14;

        $result = [];

        for($i = 0; $i < $countHours; $i++) {
            $result[] = $hourBegin + $i;
        }
        return $result;
    }
    public static function getTimesArray()
    {
        return [
            '6', '06:30', '7', '07:30', '8', '08:30', '9', '09:30', '10', '10:30', '11', '11:30', '12', '12:30', '13',
            '13:30', '14', '14:30', '15', '15:30', '16', '16:30', '17', '17:30',
            '18', '18:30', '18', '19:30', '20', '20:30', '21', '21:30', '22', '22:30', '23', '23:30', '24'
        ];
    }

    public static function getTimesShortArray()
    {
        return [
            '6', '7', '8', '9', '10', '11', '12', '13', '14', '15',
            '16', '17', '18', '19', '20', '21', '22', '23', '24'
        ];
    }

    /**
     * @return array
     */
    public static function getTimesSecondsArray()
    {
        $result = [];
        foreach(self::getTimesArray() as $time) {
            $formattedTime = self::formatTimeFromHoursString($time);
            $result[self::getSecondsInTime($formattedTime)] = $formattedTime;
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function getTimesShortSecondsArray()
    {
        $result = [];
        foreach(self::getTimesShortArray() as $time) {
            $formattedTime = self::formatTimeFromHoursString($time);
            $result[self::getSecondsInTime($formattedTime)] = $formattedTime;
        }
        return $result;
    }

    /**
     * @param $array
     * @return string
     */
    public static function getTimesOptions($array)
    {
        $str = '';
        if(!empty($array)) {
            foreach($array as $key => $value) {
                $str .= "<option value='".$key."'>".$value."</option>";
            }
        }
        return $str;
    }

    public static function getStringFromArray($array)
    {
        if(is_array($array) && !empty($array)) {
            return implode(',', $array);
        }
        return '';
    }

    public static function getJsonFromArray($array)
    {
        return $array ? json_encode($array) : '';
    }

    public static function getArrayFromJson($json)
    {
        return $json ? json_decode($json, true) : [];
    }

    public static function getArrayFromString($string)
    {
        return explode(',', $string);
    }

    public static function getDateFormatHeader($dateTimestamp)
    {
        $dateTimestamp = strtotime($dateTimestamp);
        $weekDay = date('N', $dateTimestamp);
        $day = date('j', $dateTimestamp);
        $month = date('n', $dateTimestamp);
        $year = date('Y', $dateTimestamp);

        return self::getDayName($weekDay).', '.$day.' '.self::getMonthName($month).' '.$year.' г.';
    }

    public static function getDayName($weekDay)
    {
        $weekDays = [
            1 => 'понедельник',
            2 => 'вторник',
            3 => 'среда',
            4 => 'четверг',
            5 => 'пятница',
            6 => 'суббота',
            7 => 'воскресенье',
        ];
        return array_key_exists($weekDay, $weekDays) ? $weekDays[$weekDay] : false;
    }
    public static function getMonthName($month)
    {
        $monthNames = [
            1 => 'января',
            2 => 'февраля',
            3 => 'марта',
            4 => 'апреля',
            5 => 'мая',
            6 => 'июня',
            7 => 'июля',
            8 => 'августа',
            9 => 'сентября',
            10 => 'октября',
            11 => 'ноября',
            12 => 'декабря',
        ];
        return array_key_exists($month, $monthNames) ? $monthNames[$month] : false;
    }














    public function translate($word)
    {
        $arr = preg_split('//u',$word,-1,PREG_SPLIT_NO_EMPTY);
        $arr_2 = array();
        $count = 0;
        $str = '';
        foreach ($arr as $item)
        {
            $arr_2[$count] = $this->getLetter($item);
            $count++;
        }
        foreach ($arr_2 as $let) {
            $str .= $let;
        }
        return $str;
    }
    public function getLetter($letter)
    {
        $letters = [
            'а' => 'a',
            'А' => 'a',
            'б' => 'b',
            'Б' => 'b',
            'в' => 'v',
            'В' => 'v',
            'г' => 'g',
            'Г' => 'g',
            'д' => 'd',
            'Д' => 'd',
            'е' => 'e',
            'Е' => 'e',
            'ж' => 'zh',
            'Ж' => 'zh',
            'з' => 'z',
            'З' => 'z',
            'и' => 'i',
            'И' => 'i',
            'й' => 'i',
            'Й' => 'i',
            'к' => 'k',
            'К' => 'k',
            'л' => 'l',
            'Л' => 'l',
            'м' => 'm',
            'М' => 'm',
            'н' => 'n',
            'Н' => 'n',
            'о' => 'o',
            'О' => 'o',
            'п' => 'p',
            'П' => 'p',
            'р' => 'r',
            'Р' => 'r',
            'с' => 's',
            'С' => 's',
            'т' => 't',
            'Т' => 't',
            'у' => 'u',
            'У' => 'u',
            'ф' => 'f',
            'Ф' => 'f',
            'х' => 'h',
            'Х' => 'h',
            'ц' => 'c',
            'Ц' => 'c',
            'ч' => 'ch',
            'Ч' => 'ch',
            'ш' => 'sh',
            'Ш' => 'sh',
            'щ' => 'sh',
            'Щ' => 'sh',
            'ъ' => 'y',
            'Ъ' => 'y',
            'ы' => 'y',
            'Ы' => 'y',
            'ь' => '',
            'Ь' => '',
            'э' => 'e',
            'Э' => 'e',
            'ю' => 'u',
            'Ю' => 'u',
            'я' => 'ya',
            'Я' => 'ya',
            'a' => 'a',
            'A' => 'a',
            'b' => 'b',
            'B' => 'b',
            'c' => 'c',
            'C' => 'c',
            'd' => 'd',
            'D' => 'd',
            'e' => 'e',
            'E' => 'e',
            'f' => 'f',
            'F' => 'f',
            'g' => 'g',
            'G' => 'g',
            'h' => 'h',
            'H' => 'h',
            'i' => 'i',
            'I' => 'i',
            'j' => 'j',
            'J' => 'j',
            'k' => 'k',
            'K' => 'k',
            'l' => 'l',
            'L' => 'l',
            'm' => 'm',
            'M' => 'm',
            'n' => 'n',
            'N' => 'n',
            'o' => 'o',
            'O' => 'o',
            'p' => 'p',
            'P' => 'p',
            'q' => 'q',
            'Q' => 'q',
            'r' => 'r',
            'R' => 'r',
            's' => 's',
            'S' => 's',
            't' => 't',
            'T' => 't',
            'u' => 'u',
            'U' => 'u',
            'v' => 'v',
            'V' => 'v',
            'w' => 'w',
            'W' => 'w',
            'x' => 'x',
            'X' => 'x',
            'y' => 'y',
            'Y' => 'y',
            'z' => 'z',
            'Z' => 'z',
            '  ' => '-',
            '   ' => '-',
            ' ' => '-',
            '+' => '-',
            '-' => '-',
            '(' => '-',
            ')' => '-',
            "/" => "-",
            "*" => "-",
            "?" => "-",
            "<" => "-",
            ">" => "-",
            "#" => "-",
            "№" => "-",
            "$" => "-",
            "%" => "-",
            "&" => "-",
            "[" => "-",
            "]" => "-",
            "@" => "-",
            "!" => "-",
            "'" => "-",
            "0" => "0",
            "1" => "1",
            "2" => "2",
            "3" => "3",
            "4" => "4",
            "5" => "5",
            "6" => "6",
            "7" => "7",
            "8" => "8",
            "9" => "9",
        ];
        return $letters[$letter];
    }
    public static function getWeekCount($timestamp)
    {
        return ((int) ((date("j")+date("w", strtotime(date("m") . "/01/" . date("Y")))-2)/7)) + 1;
        $month = date('m', $timestamp);
        /*$days_in_month = date('j', $timestamp);
        $diff = 3600 * 24;
        for($i = 0; $i < 7; $i++) {

        }*/
    }
}
