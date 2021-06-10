<?php

namespace App\Utils;

use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseRender
{
    /**
     * Encapsulate the data returned to the front end
     *
     * @param int $code
     * @param string $msg
     * @param null $data
     * @param null $meta
     * @param null $errors
     * @return JsonResponse
     */
    public static function json($code=Response::HTTP_OK, $msg='', $data=null, $meta=null, $errors=null) {
        if(empty($msg)) {
            $msg = Response::$statusTexts[$code];
        }
        $resp = [
            'code' => $code,
            'msg' => __($msg),
        ];

        ArrayUtil::setIfNotEmpty($resp, 'data', $data);
        ArrayUtil::setIfNotEmpty($resp, 'meta', $meta);
        ArrayUtil::setIfNotEmpty($resp, 'errors', $errors);

        return new JsonResponse($resp);
    }

    /**
     * Encapsulate the error data returned to the front end
     *
     * @param int $code
     * @param string $msg
     * @param null $errors
     * @return JsonResponse
     */
    public static function errorJson($code=Response::HTTP_OK, $msg='', $errors=null) {
        return self::json($code, $msg, null, null, $errors);
    }

    /**
     * Encapsulate the list data returned to the front end
     *
     * @param int $code
     * @param string $msg
     * @param array $listData service back data
     * @return JsonResponse
     */
    public static function listJson($code=Response::HTTP_OK, $msg='', $listData=[]) {
        return self::json($code, $msg, Arr::get($listData, 'data', []), Arr::get($listData, 'meta', []));
    }
}
