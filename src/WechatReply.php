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
            return collect();
        }

        if (WeixinRule::REPLY_MODE_ALL === $rule->reply_mode) {
            $replies = $rule->replies()->get();

            return $replies ?: collect();
        }

        if (WeixinRule::REPLY_MODE_RANDOM === $rule->reply_mode) {
            $reply = $rule->replies()->orderByRaw('RAND()')->first();

            return $reply ? collect([$reply]) : collect();
        }

        return collect();
    }
}
