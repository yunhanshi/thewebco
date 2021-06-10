<?php

namespace App\Utils;

final class UrlUtil
{
    /**
     * 根据应用域名拼装url，以及相关query参数
     *
     * @param $host
     * @param string $path
     * @param array $kvs
     * @param bool $encode
     * @return string
     */
    public static function composeUrl($host, $path='', array $kvs=[], $encode=false) {
        $url = $host . $path;
        $query = self::composeQuery($kvs, $encode);
        if(!empty($query)) {
            if(strpos($url, '?') === false) {
                $url .= '?' . $query;
            } else {
                $url .= '&' . $query;
            }
        }
        return $url;
    }

    public static function composeQuery(array $kvs, $encode=false) {
        $params = [];
        foreach($kvs as $k => $v) {
            if($encode) {
                $params[] = urlencode($k) . '=' . urlencode($v);
            } else {
                $params[] = $k . '=' . $v;
            }
        }
        return implode('&', $params);
    }

    public static function composeOfficialAccountCallback($callback, $uuid) {
        return false === strstr($callback, '?') ? $callback."?code={$uuid}" : $callback."&code={$uuid}";
    }
}
