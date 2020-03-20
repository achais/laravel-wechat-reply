<?php

namespace Achais\LaravelWechatReply\Http\Controllers;

use Achais\LaravelWechatReply\Exceptions\InternalException;
use Achais\LaravelWechatReply\Http\Middleware\Authenticate;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware(Authenticate::class, [
            'except' => ['auth'],
        ]);
    }

    public function success($data)
    {
        if (!is_array($data) && !is_string($data)) {
            throw new InternalException('返回内容类型不支持');
        }

        if (!is_array($data)) {
            $message = (string)$data;
            $res = ['status' => 0, 'message' => $message];
            $res['data'] = [];
            return $res;
        } else {
            $res = ['status' => 0, 'message' => 'success'];
            $res['data'] = $data;
            return $res;
        }
    }

    /**
     * 正常业务错误
     * @param $message
     * @param array $data
     * @return array
     */
    public function fail($message, $data = [])
    {
        $res = ['status' => 1, 'message' => $message];
        if (!empty($data)) {
            $res['data'] = $data;
        } else {
            $res['data'] = [];
        }
        return $res;
    }
}