<?php

namespace Achais\LaravelWechatReply\Exceptions;

use InvalidArgumentException;

class KeywordDoesNotExist extends InvalidArgumentException
{
    public static function withId($keywordId)
    {
        return new static("There is no keyword with id `{$keywordId}`.");
    }
}
