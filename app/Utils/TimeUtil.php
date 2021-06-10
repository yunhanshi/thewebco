<?php

namespace App\Utils;

class TimeUtil
{
    /**
     * 获取当前毫秒数
     */
    public static function milliseconds()
    {
        return round(microtime(true) * 1000);
    }

    public static function now() {
        return date("Y-m-d H:i:s");
    }
}
