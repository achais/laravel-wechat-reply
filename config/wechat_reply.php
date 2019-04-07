<?php

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
    ]

];
