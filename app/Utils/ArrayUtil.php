<?php

namespace App\Utils;

final class ArrayUtil
{
    /**
     * 从数组中删除指定的keys
     *
     * @param $arr
     * @param $keys
     * @return array
     */
    public static function removeByKeys($arr, $keys) {
        if (!$arr) {
            return [];
        }
        return array_filter($arr, function($v, $k) use($keys) {
            return !in_array($k, $keys);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * 从数组中保留指定的keys的value
     *
     * @param $arr
     * @param $keys
     * @return array
     */
    public static function getByKeys($arr, $keys) {
        if (!$arr) {
            return [];
        }
        return array_filter($arr, function($v, $k) use($keys) {
            return in_array($k, $keys);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * 如果value非空，则设置到目标array中.
     *
     * @param array $arr
     * @param $key
     * @param $value
     */
    public static function setIfNotEmpty(array &$arr, $key, $value) {
        if(!empty($value)) {
            $arr[$key] = $value;
        }
    }

    /**
     * 从指定的数组中提取指定的key对应的key/value
     *
     * @param $arr
     * @param array $keys
     * @return array
     */
    public static function extractValues($arr, array $keys) {
        $res = array();
        foreach($keys as $key) {
            if(isset($arr[$key])) {
                $res[$key] = $arr[$key];
            }
        }
        return $res;
    }

    /**
     * 二维数组根据某个字段排序
     * @param array $array 要排序的数组
     * @param string $key   要排序的键字段
     * @param string $sort  排序类型  SORT_ASC     SORT_DESC
     * @return array 排序后的数组
     */
    public static function sortByKey($array, $key, $sort = SORT_DESC) {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$key];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }

    /**
     * 如果指定的key在src中存在，则将对应的key/value设到dst中
     *
     * @param array $dst
     * @param array $src
     * @param $key
     * @return bool 是否有设值
     */
    public static function copyIfExist(array &$dst, array $src, $key) {
        if(isset($src[$key])) {
            $dst[$key] = $src[$key];
            return true;
        }
        return false;
    }
}
