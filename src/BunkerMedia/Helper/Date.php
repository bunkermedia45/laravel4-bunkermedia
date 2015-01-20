<?php
namespace BunkerMedia\Helper;

use Illuminate\Support\Arr;

class Date {
    // which day does the week start on (0 - 6)

    const WEEK_START = 1;
    static $months
        = [
            1  => 'января',
            2  => 'февраля',
            3  => 'марта',
            4  => 'апреля',
            5  => 'мая',
            6  => 'июня',
            7  => 'июля',
            8  => 'августа',
            9  => 'сентября',
            10 => 'октября',
            11 => 'ноября',
            12 => 'декабря',
        ];

    public static function startOfMonth($date = null) {
        $time = self::todayIfNull($date);
        return gmmktime(0, 0, 0, date('m', $time), 1, date('Y', $time));
    }

    public static function todayIfNull($date = null) {
        return is_string($date) ? strtotime($date) : (is_int($date) ? $date : time());
    }

    public static function endOfMonth($date = null) {
        $time = self::todayIfNull($date);
        return gmmktime(0, 0, 0, date('m', $time), date('t', $time), date('Y', $time));
    }

    public static function weekDays($date = null) {
        $time   = self::todayIfNull($date);
        $output = [];

        $startofweek = self::startOfWeek($date);
        $endofweek   = self::endOfWeek($date);

        $day = $startofweek;

        while ($day < $endofweek) {
            array_push($output, date("D", $day));
            $day = $day + self::DAY;
        }

        return $output;
    }

    public static function startOfWeek($date = null) {
        $time  = self::todayIfNull($date);
        $start = gmmktime(0, 0, 0, date('m', $time), (date('d', $time) + self::WEEK_START) - date('w', $time), date('Y', $time));
        if ($start > $time) {
            $start -= self::WEEK;
        }
        return $start;
    }

    public static function endOfWeek($date = null) {
        $time = self::todayIfNull($date);
        return self::startOfWeek($time) + self::WEEK - 1;
    }

    static function monthDeclension($m) {
        return Arr::get(self::$months, (int)$m, '');
    }

    static function weekday($d = null, $short = false) {
        $ret_full  = [
            0 => 'воскресенье',
            1 => 'понедельник',
            2 => 'вторник',
            3 => 'среда',
            4 => 'четверг',
            5 => 'пятница',
            6 => 'суббота',
        ];
        $ret_short = [
            0 => 'ВС',
            1 => 'ПН',
            2 => 'ВТ',
            3 => 'СР',
            4 => 'ЧТ',
            5 => 'ПТ',
            6 => 'СБ',
        ];
        if (is_null($d)) {
            return $short ? $ret_short : $ret_full;
        }
        $d   = intval($d);
        $ret = $short ? $ret_short : $ret_full;
        return Arr::get($ret, $d);
    }

    static function mkdate($y, $m, $d, $h = 0, $i = 0, $s = 0) {
        return strtotime($y . '-' . $m . '-' . $d . ' ' . $h . ':' . $i . ':' . $s);
    }

    static function year_month($date) {
        $date = self::todayIfNull($date);
        $y    = date('Y', $date);
        $m    = self::month(date('m', $date));
        return $y . ', ' . $m;
    }

    static function month($m) {
        return Arr::get(self::months(), (int)$m, '');
    }

    static function months($m = null, $short = false) {
        $ret = [
            1  => 'январь',
            2  => 'февраль',
            3  => 'март',
            4  => 'апрель',
            5  => 'май',
            6  => 'июнь',
            7  => 'июль',
            8  => 'август',
            9  => 'сентябрь',
            10 => 'октябрь',
            11 => 'ноябрь',
            12 => 'декабрь',
        ];
        if (!$m) {
            return $ret;
        }
        $month = Arr::get($ret, (int)$m);
        if ($short) {
            $month = mb_substr($month, 0, 3);
        }
        return $month;
    }

    /**
     * Получение прошлого месяца в виде массива array('y'=>2013,'m'=>2)
     */
    static function prevMonth($key = null) {
        $prev = self::todayIfNull(date('Y') . '-' . date('M') . '-01') - 24 * 3600;
        $ret  = [
            'y' => (int)date('Y', $prev),
            'm' => (int)date('m', $prev),
        ];
        return $key ? Arr::get($ret, $key) : $ret;
    }

    static function fromDatetime($val) {
        if (!$val) {
            return '';
        }
        return self::formDate($val) . ', ' . date('H:i:s', strtotime($val));
    }

    static function formDate($val) {
        return self::dateFromUnix(strtotime($val));
    }

    static function dateFromUnix($val) {
        $ret   = [];
        $ret[] = (int)date('d', $val);
        $ret[] = Arr::get(self::$months, intval(date('m', $val)));
        $year  = date('Y', $val);
        if (date('Y') != $year) {
            $ret[] = $year;
        }
        return implode(' ', $ret);
    }

    static function datetimeFromUnix($val) {
        return self::dateFromUnix($val) . ', ' . date('H:i:s', $val);
    }

    static function toDate($value) {
        return date('Y-m-d', self::todayIfNull($value));
    }

    static function toDatetime($value) {
        return date('Y-m-d H:i:s', self::todayIfNull($value));
    }

}