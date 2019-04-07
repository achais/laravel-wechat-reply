<?php

namespace Achais\LaravelWechatReply\Exceptions;

use InvalidArgumentException;

class ReplyDoesNotExist extends InvalidArgumentException
{
    public static function withId($replyId)
    {
        return new static("There is no reply with id `{$replyId}`.");
    }
}
