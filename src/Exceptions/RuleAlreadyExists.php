<?php

namespace Achais\LaravelWechatReply\Exceptions;

use InvalidArgumentException;

class RuleAlreadyExists extends InvalidArgumentException
{
    public static function create($ruleName)
    {
        return new static("A rule `{$ruleName}` already exists.");
    }
}
