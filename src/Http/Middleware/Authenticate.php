<?php

namespace Achais\LaravelWechatReply\Http\Middleware;

use Achais\LaravelWechatReply\WechatReply;

class Authenticate
{
    public function handle($request, $next)
    {
        return WechatReply::check($request) ? $next($request) : redirect()->route('wechat-reply.auth');
    }
}