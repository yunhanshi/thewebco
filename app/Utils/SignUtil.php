<?php

namespace App\Utils;

final class SignUtil
{
    /**
     * 签名规则，将所有参数按key升序排序，拼接规则：a=1&b=2&...|secret，对拼接后字符串做md5，结果转成hex字符串标识。
     *
     * @param $params
     * @param $secret
     * @return string
     */
    public static function sign($params, $secret) {
        ksort($params, SORT_STRING);
        $ts = [];
        foreach($params as $k => $v) {
            $ts[] = $k . '=' . $v;
        }

        $str = implode('&', $ts) . '|' . $secret;
        return md5($str);
    }

    public static function verify($params, $secret, $sign) {
        $act = self::sign($params, $secret);
        return $act === $sign;
    }

    public static function expire($ts) {
        $expire = config('cgi.sign.expire', 15 * 60 * 1000);
        return $ts + $expire <= TimeUtil::milliseconds();
    }
}
