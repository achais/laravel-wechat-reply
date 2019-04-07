<?php

namespace Achais\LaravelWechatReply\Models;

use Achais\LaravelWechatReply\Exceptions\KeywordDoesNotExist;
use Illuminate\Database\Eloquent\Model;

class WeixinKeyword extends Model
{
    protected $fillable = ['keyword', 'full_match'];

    protected $casts = [
        'full_match' => 'boolean',
    ];

    public static function create(array $attributes, WeixinRule $rule)
    {
        $attributes['weixin_rule_id'] = $rule->id;

        return static::query()->create($attributes);
    }

    public static function findById($id)
    {
        $keyword = self::query()->where('id', $id)->first();

        if (!$keyword) {
            throw KeywordDoesNotExist::withId($id);
        }

        return $keyword;
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
