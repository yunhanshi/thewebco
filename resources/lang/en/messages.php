<?php

return [
    'response' => [
        'ok' => 'OK',
        'accepted' => 'Accepted',
        'redirect' => 'Redirect',
        'bad_request' => 'Bad Request',
        'unauthorized' => 'Unauthorized',
        'not_found' => 'Not Found',
        'unknown' => 'Unknown Error',
    ],

    'error' => [
        'invalid_app_id' => 'Invalid app_id',
        'empty_app_id' => 'app_id should not be empty',
        'invalid_app_type' => 'Invalid app_type',
        'login' => 'Fail to login',
        'invalid_login_info' => 'Fail to get login info',
        'invalid_uuid' => 'Invalid uuid',
        'empty_uuid' => 'uuid should not be empty',
        'empty_callback' => 'callback should not be empty',
        'user_list' => 'Fail to get user list',
        'biz_list' => 'Fail to get Biz list',
        'biz_app_list' => 'Fail to get App list',
        'biz_app' => 'Fail to get App info',
        'update_biz_app' => 'Fail to update app info',
        'add_biz_app' => 'Fail to add app info',
    ],

    'const' => [
        'unknown' => 'Unknown',
    ],

    'status' => [
        'enable' => 'Enable',
        'disable' => 'Disable',
    ],

    'gender' => [
        'unknown' => 'Unknown',
        'male' => 'Male',
        'female' => 'Female',
    ],

    'wechat' => [
        'official' => 'Wechat Official Account',
        'mp' => 'Wechat Microprogram',
    ],

    'page' => [
        'qrcode_info' => 'Scan Qrcode By Wechat App',
    ],

    'signup' => [
        'email_required' => 'email is required',
        'email_unique' => 'email has been existed',
        'name_required' => 'name is required',
        'confirmation_code_required' => 'confirmation code is required',
        'confirmation_code_size' => 'confirmation code is a 6-digital number',
        'confirmation_code_numeric' => 'confirmation code is a 6-digital number',
        'password_required' => 'password is required',
        'password_confirmed' => 'confirmation password is invalid',
        'password_min' => 'the min password length is 8',
        'password_max' => 'the max password length is 18',
        'confirmation_password_required' => 'confirmation password is required',
    ],
];
