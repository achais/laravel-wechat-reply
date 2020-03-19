<?php

namespace Achais\LaravelWechatReply\Http\Controllers;

use Achais\LaravelWechatReply\Http\Middleware\Authenticate;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function __construct()
    {
        $this->middleware(Authenticate::class, [
            'except' => ['auth'],
        ]);
    }
}