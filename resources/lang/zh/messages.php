<?php

return [
    'response' => [
        'ok' => '操作成功',
        'accepted' => '已接收到请求，但可能还需要进一步处理',
        'redirect' => '网页跳转',
        'bad_request' => '请求参数有误',
        'unauthorized' => '抱歉，你无权限执行此操作',
        'not_found' => '网页不存在',
        'unknown' => '未知错误',
    ],

    'error' => [
        'invalid_app_id' => '非法的app_id',
        'empty_app_id' => 'app_id不能为空',
        'invalid_app_type' => '非法的app类型',
        'invalid_login_info' => '获取登录信息失败',
        'login' => '登录失败',
        'invalid_uuid' => '非法的uuid',
        'empty_uuid' => 'uuid不能为空',
        'empty_callback' => 'callback不能为空',
        'biz_list' => '获取业务列表失败',
        'biz_app_list' => '获取应用列表失败',
        'biz_app' => '获取应用信息失败',
        'update_biz_app' => '更新第三方应用信息失败',
        'add_biz_app' => '新增第三方应用信息失败',
    ],

    'page' => [
        'qrcode_info' => '微信扫码登录',
    ],

    'const' => [
        'unknown' => '未知',
    ],

    'status' => [
        'enable' => '启用',
        'disable' => '停用',
    ],

    'gender' => [
        'unknown' => '未知',
        'male' => '男',
        'female' => '女',
    ],

    'wechat' => [
        'official' => '微信公众号',
        'mp' => '微信小程序',
    ],

    'signup' => [
        'email_required' => '邮件地址不能为空',
        'email_unique' => '邮件地址已存在',
        'name_required' => '姓名不能为空',
        'confirmation_code_required' => '验证码不能为空',
        'confirmation_code_size' => '验证码必须为6位数字',
        'confirmation_code_numeric' => '验证码必须为6位数字',
        'password_required' => '密码不能为空',
        'password_confirmed' => '密码二次验证不匹配',
        'password_min' => '8-18位不含特殊字符的数字、字母组合',
        'password_max' => '8-18位不含特殊字符的数字、字母组合',
        'confirmation_password_required' => '确认密码不能为空',
    ],
];
