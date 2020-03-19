<?php

/*
 * This file is part of the achais/laravel-wechat-reply.
 *
 * (c) achais <i@achais.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
    'models' => [
        'rule' => Achais\LaravelWechatReply\Models\WeixinRule::class,

        'keyword' => Achais\LaravelWechatReply\Models\WeixinKeyword::class,

        'reply' => Achais\LaravelWechatReply\Models\WeixinReply::class,
    ],

    'table_names' => [
        'rules' => 'weixin_rules',

        'keywords' => 'weixin_keywords',

        'replies' => 'weixin_replies',
    ],

    'domain' => null,

    'path' => 'wechat-reply',

    'middleware' => ['web'],

    'auth' => [
        'token' => 'WRT',

        'user' => env('WECHAT_REPLY_USER', 'admin'),

        'password' => env('WECHAT_REPLY_PASSWORD', 'admin'),
    ],

];
