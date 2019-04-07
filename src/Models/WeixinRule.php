<?php

namespace Achais\LaravelWechatReply\Models;

use Illuminate\Database\Eloquent\Model;
use Achais\LaravelWechatReply\Exceptions\RuleAlreadyExists;
use Achais\LaravelWechatReply\Exceptions\RuleDoesNotExist;

class WeixinRule extends Model
{
    const REPLY_MODE_ALL = 'all';

    const REPLY_MODE_RANDOM = 'random';

    protected $fillable = ['rule_name', 'reply_mode'];

    public static function create(array $attributes = [])
    {
        if (static::query()->where('rule_name', $attributes['rule_name'])->first()) {
            throw RuleAlreadyExists::create($attributes['rule_name']);
        }

        return static::query()->create($attributes);
    }

    public static function findByName($ruleName)
    {
        $rule = static::query()->where('full_name', $ruleName)->first();

        if (! $rule) {
            throw RuleDoesNotExist::named($ruleName);
        }

        return $rule;
    }

    public static function findById($id)
    {
        $rule = static::query()->where('id', $id)->first();

        if (! $rule) {
            throw RuleDoesNotExist::withId($id);
        }

        return $rule;
    }

    public static function findOrCreate($ruleName, $replyMode = self::REPLY_MODE_RANDOM)
    {
        $rule = static::query()->where('name', $ruleName)->first();

        if (!$rule) {
            return static::query()->create(['rule_name' => $rule, 'reply_mode' => $replyMode]);
        }

        return $rule;
    }

    public function keywords()
    {
        return $this->hasMany(
            config('wechat_reply.models.keyword'),
            'weixin_rule_id',
            'id'
        );
    }

    public function replies()
    {
        return $this->hasMany(
            config('wechat_reply.models.reply'),
            'weixin_rule_id',
            'id'
        );
    }
}
