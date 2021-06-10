<?php

namespace App\Utils;

class DateUtil
{
    public static function convertFormat($dateStr, $dstFormat, $srcFormat='Y-m-d H:i:s') {
        if(empty($dateStr)) {
            return '';
        }
        $ts = strtotime($dateStr);
        return date($dstFormat, $ts);
    }

    public static function yesterday() {
        return date('Y-m-d', strtotime("-1 day"));
    }

    public static function today() {
        return date("Y-m-d");
    }

    public static function getDateBefore(int $count) {
        return date('Y-m-d', strtotime("-$count days"));
    }
}
