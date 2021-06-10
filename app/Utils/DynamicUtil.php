<?php

namespace App\Utils;

class DynamicUtil
{
    public static function safeCall($obj, string $methodName, array $args, $defaultReturn=null) {
        if(!method_exists($obj, $methodName)) {
            return $defaultReturn;
        }

        return call_user_func_array(array($obj, $methodName), $args);
    }
}
