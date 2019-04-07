<?php

namespace Achais\LaravelWechatReply\Models;

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

    protected $fillable = ['content'];

    public static function create(array $attributes, WeixinRule $rule)
    {
        $attributes['weixin_rule_id'] = $rule->id;

        return static::query()->create($attributes);
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
