<?php

/*
 * This file is part of the achais/laravel-wechat-reply.
 *
 * (c) achais <i@achais.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Achais\LaravelWechatReply\Http\Middleware;

use Achais\LaravelWechatReply\WechatReply;

class Authenticate
{
    public function handle($request, $next)
    {
        return WechatReply::check($request) ? $next($request) : redirect()->route('wechat-reply.auth');
    }
}
