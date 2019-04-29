<?php
/**
 * Created by PhpStorm.
 * User: purelightme
 * Date: 2019/3/16
 * Time: 16:02
 */

return [

    'expires' => 1800,  //验证码有效时间，单位：秒

    'turn' => 'off', //是否真正发送短信，on发送，off不发送

    'cache' => [
        'driver' => 'redis', //验证码缓存driver，支持redis，db，可自行定制其他driver
    ],

    'code' => [
        'size' => 4, //短信验证码位数
    ],

    'sms' => [
        // HTTP 请求的超时时间（秒）
        'timeout' => 5.0,
        // 默认发送配置
        'default' => [
            // 网关调用策略，默认：顺序调用
            'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,
            // 默认可用的发送网关
            'gateways' => [
                'aliyun',
            ],
        ],
        // 可用的网关配置
        'gateways' => [
            'errorlog' => [
                'file' => storage_path('logs/easy-sms.log'),
            ],
            'aliyun' => [
                'access_key_id' => env('ACCESS_KEY_ID'),
                'access_key_secret' => env('ACCESS_KEY_SECRET'),
                'sign_name' => env('SIGN_NAME'),
            ],
        ],
    ],
];