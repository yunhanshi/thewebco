<?php

namespace App\Utils;

final class CurlUtil
{
    /**
     * 发起get请求
     * @param $url
     * @param array $params
     * @param array $headers header字符串列表（每个header拼装后的字符串）
     * @return bool|string
     */
    public static function get($url, $params=array(), $headers=array()) {
        // 额外请求参数
        if(!empty($params)) {
            // 判断原url里是否已有参数，选择不同的连接符
            $joint = strpos($url, "?") !== false ? "&" : "?";

            // 将额外的参数组装到url中
            $url .= $joint.http_build_query($params);
        }

        return self::req($url, "GET", null, $headers);
    }

    public static function post($url, $params=array(), $headers=array()) {
        return self::req($url, "POST", $params, $headers);
    }

    public static function postJson($url, $params=array(), $headers=array()) {
        $json = !empty($params) ? json_encode($params) : '';
        $headers = $headers ?? [];
        $headers[] = 'Content-Type: application/json';
        return self::req($url, "POST", $json, $headers);
    }

    public static function delete($url, $params=array(), $headers=array()) {
        return self::req($url, "DELETE", $params, $headers);
    }

    private static function req($url, $request, $postParams, $headers) {
        // 发送请求
        $curl = curl_init();

        // Set SSL if required
        if (substr($url, 0, 5) == 'https') {
            curl_setopt($curl, CURLOPT_PORT, 443);
        }

        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $request);

        curl_setopt($curl, CURLOPT_URL, $url);

        // 如果有post参数
        if(!empty($postParams)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postParams);
        }

        // 自定义header
        if(!empty($headers)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }

        try {
            return curl_exec($curl);
        } finally {
            curl_close($curl);
        }
    }
}
