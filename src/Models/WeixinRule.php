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
        $rule = static::query()->where('rule_name', $ruleName)->first();

        if (!$rule) {
            throw RuleDoesNotExist::named($ruleName);
        }

        return $rule;
    }

    public static function findById($id)
    {
        $rule = static::query()->where('id', $id)->first();

        if (!$rule) {
            throw RuleDoesNotExist::withId($id);
        }

        return $rule;
    }

    public static function findOrCreate($ruleName, $replyMode = self::REPLY_MODE_RANDOM)
    {
        $rule = static::query()->where('rule_name', $ruleName)->first();

        if (!$rule) {
            return static::query()->create(['rule_name' => $ruleName, 'reply_mode' => $replyMode]);
        }

        return $rule;
    }

    public static function deleteByName($ruleName)
    {
        $rule = static::query()->where('rule_name', $ruleName)->first();

        if (!$rule) {
            throw RuleDoesNotExist::named($ruleName);
        }

        return $rule->delete();
    }

    public static function deleteById($id)
    {
        $rule = static::query()->where('id', $id)->first();

        if (!$rule) {
            throw RuleDoesNotExist::withId($id);
        }

        return $rule->delete();
    }

    public function giveKeyword(array $attributes)
    {
        $attributes['weixin_rule_id'] = $this->id;

        return WeixinKeyword::query()->create($attributes);
    }

    public function giveReply(array $attributes)
    {
        $attributes['weixin_rule_id'] = $this->id;

        return WeixinReply::query()->create($attributes);
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
