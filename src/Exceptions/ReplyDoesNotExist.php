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

class ReplyDoesNotExist extends InvalidArgumentException
{
    public static function withId($replyId)
    {
        return new static("There is no reply with id `{$replyId}`.");
    }
}
