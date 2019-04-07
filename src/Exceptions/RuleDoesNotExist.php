<?php

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
