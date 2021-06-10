<?php

namespace App\Utils;

final class StringUtil
{
    /**
     * 判断源字符串是否以目标字符串开头，区分大小写
     *
     * @param $source
     * @param $target
     * @return bool
     */
    public static function startsWith($source, $target) {
        return strpos($source, $target) === 0;
    }

    /**
     * 判断源字符串是否以目标字符串结尾，区分大小写
     *
     * @param $source
     * @param $target
     * @return bool
     */
    public static function endsWith($source, $target) {
        $pos = strpos($source, $target);
        if($pos === false) {
            return false;
        }

        return strlen($target) + $pos === strlen($source);
    }
}
