<?php

namespace App\Http\Controllers;

use App\Exceptions\Validation\PermissionException;
use App\Exceptions\Validation\ValidationException;
use App\Utils\ResponseRender;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Encapsulate the data returned to the front end
     *
     * @param int $code
     * @param string $msg
     * @param null $data
     * @param null $meta
     * @param null $errors
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function json($code=Response::HTTP_OK, $msg='', $data=null, $meta=null, $errors=null) {
        return ResponseRender::json($code, $msg, $data, $meta, $errors);
    }

    /**
     * Encapsulate the error data returned to the front end
     *
     * @param int $code
     * @param string $msg
     * @param null $errors
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function errorJson($code=Response::HTTP_OK, $msg='', $errors=null) {
        return $this->json($code, $msg, null, null, $errors);
    }

    /**
     * Encapsulate the data of lists returned to the front end
     *
     * @param int $code
     * @param string $msg
     * @param array $listData service层返回的列表查询结果
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function listJson($code=Response::HTTP_OK, $msg='', $listData=[]) {
        return $this->json($code, $msg, Arr::get($listData, 'data', []), Arr::get($listData, 'meta', []));
    }

    /**
     * validate params
     *
     * @param $params
     * @param $rules
     * @param $failMsg
     * @throws ValidationException
     */
    protected function validateParams($params, $rules, $failMsg='') {
        $validator = Validator::make($params, $rules);

        if($validator->fails()) {
            throw new ValidationException($failMsg, Response::HTTP_BAD_REQUEST, $validator->errors());
        }
    }

    /**
     * @param Request $request
     * @param array $ps
     * @throws PermissionException
     */
    protected function hasAnyPermissions(Request $request, array $ps) {
        $user = $request->user();
        if(empty($user)) {
            throw new PermissionException();
        }

        if(!$user->hasAnyPermission($ps)) {
            throw new PermissionException();
        }
    }

    /**
     * @param Request $request
     * @param array $ps
     * @throws PermissionException
     */
    protected function hasAllPermissions(Request $request, array $ps) {
        $user = $request->user();
        if(empty($user)) {
            throw new PermissionException();
        }

        if(!$user->hasAllPermission($ps)) {
            throw new PermissionException();
        }
    }
}
