<?php

namespace App\Utils;

final class IdUtil
{
    public static function uuid($prefix='', $moreEntropy=false) {
        $uuid = uniqid($prefix, $moreEntropy);
        $uuid = str_replace('.', '', $uuid);
        return $uuid;
    }
}
