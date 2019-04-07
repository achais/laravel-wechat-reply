<?php
/**
 * Created by PhpStorm.
 * User: achais
 * Date: 2019-04-07
 * Time: 18:27
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

        if (!$rule) return collect();

        if ($rule->reply_mode === WeixinRule::REPLY_MODE_ALL) {
            $replies = $rule->replies()->get();
            return $replies ?: collect();
        }

        if ($rule->reply_mode === WeixinRule::REPLY_MODE_RANDOM) {
            $reply = $rule->replies()->orderByRaw("RAND()")->first();
            return $reply ? collect([$reply]) : collect();
        }

        return collect();
    }
}
