<?php

namespace Modules\Shared\Helpers;

use Carbon\Carbon;
use Morilog\Jalali\Jalalian;

class DateTimeHelpers
{
    public static function jalaliDateToGregorian(string $date, string $format = 'Y/m/d')
    {
        return $date ? Jalalian::fromFormat($format, $date)->toCarbon()->format('Y-m-d') : null;
    }

    public static function jalaliDateTimeToGregorian(string $dateTime, string $format = 'Y/m/d H:i:s')
    {
        return $dateTime ? Jalalian::fromFormat($format, $dateTime)->toCarbon()->format('Y-m-d H:i:s') : null;
    }

    public static function gregorianDateToJalali(string $date, string $format = 'Y/m/d')
    {
        return Jalalian::fromCarbon(Carbon::parse($date))->format($format);
    }

    public static function gregorianDateTimeToJalali(string $dateTime, string $format = 'Y/m/d H:i:s')
    {
        return Jalalian::fromCarbon(Carbon::parse($dateTime))->format($format);
    }

    public static function splitJalaliDate(string $date, string $format = 'Y/m/d')
    {
        $date = Jalalian::fromFormat($format, $date)->format('Y-F-m-d-l-z');
        $list = explode('-', $date);

        return array(
            'day_of_year' => $list[5],
            'day_str' => $list[4],
            'day' => $list[3],
            'month' => $list[2],
            'month_str' => $list[1],
            'year' => $list[0],
        );
    }

}
