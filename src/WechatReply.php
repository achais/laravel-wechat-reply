<?php

/*
 * This file is part of the achais/laravel-wechat-reply.
 *
 * (c) achais <i@achais.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Achais\LaravelWechatReply;

use Achais\LaravelWechatReply\Models\WeixinRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WechatReply
{
    public static function query($keyword)
    {
        // 找到关键词的最新一条规则
        $rule = WeixinRule::query()->whereHas('keywords', function ($query) use ($keyword) {
            $query->where([
                ['keyword', 'like', "%{$keyword}%"],
                ['full_match', false],
            ])->orWhere([
                ['keyword', $keyword],
                ['full_match', true],
            ]);
        })->orderBy('id', 'desc')->first();

        if (!$rule) {
            return null;
        }

        $replies = null;

        if (WeixinRule::REPLY_MODE_ALL === $rule->reply_mode) {
            $replies = $rule->replies()->get();
        }

        if (WeixinRule::REPLY_MODE_RANDOM === $rule->reply_mode) {
            $replies = $rule->replies()->orderByRaw('RAND()')->limit(1)->get();
        }

        return $replies;
    }

    public static function check(Request $request)
    {
        $authUser = config('wechat_reply.auth.user');
        $authPassword = config('wechat_reply.auth.password');
        $authToken = $request->session()->get(config('wechat_reply.auth.token'));

        return Hash::check($authUser . $authPassword, $authToken) ||
            ($authUser == $request->user && $authPassword == $request->password);
    }

    public static function respondSession(Request $request)
    {
        $authToken = Hash::make(config('wechat_reply.auth.user') . config('wechat_reply.auth.password'));
        $request->session()->put(config('wechat_reply.auth.token'), $authToken);
    }

    public static function destroySession(Request $request)
    {
        $request->session()->remove(config('wechat_reply.auth.token'));
    }
}
