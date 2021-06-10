<?php

namespace App\Utils;

use Illuminate\Support\Arr;

final class SettingUtil
{
    public static function get($key, $defaultValue=null) {
        return Arr::get(config('settings', []), $key, $defaultValue);
    }
}
