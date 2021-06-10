<?php

namespace App\Utils;

use Illuminate\Support\Arr;

class DBUtil
{
    public static function isDuplicatedKeyException(\Exception $e) {
        if(empty($e->errorInfo)) {
            return false;
        }
        $code = Arr::get($e->errorInfo, 1);
        return $code === 1062;
    }
}
