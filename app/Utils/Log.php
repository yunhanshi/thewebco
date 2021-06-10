<?php

namespace App\Utils;

/**
 * \Illuminate\Support\Facades\Log 简单封装，调整额外参数打印格式，支持附加exception堆栈。
 *
 * @package App\Utils
 */
final class Log {

    public static function alert(string $message, array $context = [], $e=null) {
        \Illuminate\Support\Facades\Log::alert(self::composeLog($message, $context, $e));
    }

    public static function critical(string $message, array $context = []) {
        \Illuminate\Support\Facades\Log::critical(self::composeLog($message, $context, $e));
    }

    public static function debug(string $message, array $context = [], $e=null) {
        \Illuminate\Support\Facades\Log::debug(self::composeLog($message, $context, $e));
    }

    public static function emergency(string $message, array $context = [], $e=null) {
        \Illuminate\Support\Facades\Log::emergency(self::composeLog($message, $context, $e));
    }

    public static function error(string $message, array $context = [], $e=null) {
        \Illuminate\Support\Facades\Log::error(self::composeLog($message, $context, $e));
    }

    public static function info(string $message, array $context = [], $e=null) {
        \Illuminate\Support\Facades\Log::info(self::composeLog($message, $context, $e));
    }

    public static function notice(string $message, array $context = [], $e=null) {
        \Illuminate\Support\Facades\Log::notice(self::composeLog($message, $context, $e));
    }

    public static function warning(string $message, array $context = [], $e=null) {
        \Illuminate\Support\Facades\Log::warning(self::composeLog($message, $context, $e));
    }

    private static function composeLog($message, $context, $e) {
        $message = __($message);
        $str = $message . ', ' . self::composeMessage($context);
        $eLog = self::composeException($e);
        if(!empty($eLog)) {
            $str .= "\n" . $eLog;
        }
        return $str;
    }

    private static function composeMessage($context) {
        if(!is_array($context)) {
            return json_encode($context, JSON_UNESCAPED_UNICODE);
        }

        $kvs = [];
        foreach($context as $k => $v) {
            $kvs[] = $k . ": " . $v;
        }

        return implode(", ", $kvs);
    }

    private static function composeException($e) {
        $str = '';
        if($e instanceof \Exception) {
            // 打印异常堆栈
            $firstFalg = true;
            do {
                if(!$firstFalg) {
                    $str .= "Previous Exception:\n";
                }
                $str .= "Code: ".$e->getCode()."\n";
                $str .= "Message: ".$e->getMessage()."\n";
                $str .= $e->getTraceAsString()."\n";
                $e = $e->getPrevious();
                $firstFalg = false;
            } while(!is_null($e));
        }
        return $str;
    }
}
