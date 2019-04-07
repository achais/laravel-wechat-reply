<?php

/*
 * This file is part of the achais/laravel-wechat-reply.
 *
 * (c) achais <i@achais.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Achais\LaravelWechatReply\Exceptions;

use InvalidArgumentException;

class RuleDoesNotExist extends InvalidArgumentException
{
    public static function named($ruleName)
    {
        return new static("There is no rule named `{$ruleName}`.");
    }

    public static function withId($ruleId)
    {
        return new static("There is no rule with id `{$ruleId}`.");
    }
}
