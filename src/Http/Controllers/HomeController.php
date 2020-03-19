<?php

namespace Achais\LaravelWechatReply\Http\Controllers;

use Achais\LaravelWechatReply\WechatReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        return view('wechat-reply::index');
    }

    public function auth(Request $request)
    {
        // POST
        if ($request->isMethod('POST')) {
            if (WechatReply::check($request)) {
                WechatReply::respondSession($request);
                return redirect()->route('wechat-reply.index');
            }

            session()->flash('error', '用户名或者密码有误');
            return view('wechat-reply::auth');
        }

        if (WechatReply::check($request)) {
            return redirect()->route('wechat-reply.index');
        }

        return view('wechat-reply::auth');
    }
}