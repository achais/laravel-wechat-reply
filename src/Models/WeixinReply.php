<?php

/*
 * This file is part of the achais/laravel-wechat-reply.
 *
 * (c) achais <i@achais.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Achais\LaravelWechatReply\Models;

use Achais\LaravelWechatReply\Exceptions\ReplyDoesNotExist;
use Illuminate\Database\Eloquent\Model;

class WeixinReply extends Model
{
    const TYPE_TEXT = 'text';

    const TYPE_IMAGE = 'image';

    const TYPE_VOICE = 'voice';

    const TYPE_VIDEO = 'video';

    const TYPE_MUSIC = 'music';

    const TYPE_NEWS = 'news';

    const TYPE_MP_NEWS = 'mpnews';

    const TYPE_MSG_MENU = 'msgmenu';

    const TYPE_WX_CARD = 'wxcard';

    protected $fillable = ['type', 'content', 'weixin_rule_id'];

    public static function create(array $attributes, WeixinRule $rule)
    {
        $attributes['weixin_rule_id'] = $rule->id;

        return static::query()->create($attributes);
    }

    public static function findById($id)
    {
        $reply = self::query()->where('id', $id)->first();

        if (!$reply) {
            throw ReplyDoesNotExist::withId($id);
        }

        return $reply;
    }

    public static function deleteById($id)
    {
        $reply = self::query()->where('id', $id)->first();

        if (!$reply) {
            throw ReplyDoesNotExist::withId($id);
        }

        return $reply->delete();
    }

    public function rule()
    {
        return $this->belongsTo(
            config('wechat_reply.models.rule'),
            'weixin_rule_id',
            'id'
        );
    }
}
