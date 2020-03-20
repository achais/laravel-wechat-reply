<?php

/*
 * This file is part of the achais/laravel-wechat-reply.
 *
 * (c) achais <i@achais.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Achais\LaravelWechatReply\Http\Controllers;

use Achais\LaravelWechatReply\WechatReply;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Dashboard.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('wechat-reply::index');
    }

    /**
     * Login.
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function auth(Request $request)
    {
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

    /**
     * Logout.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        WechatReply::destroySession($request);

        return redirect()->route('wechat-reply.auth')->with('info', '已退出登录');
    }
}
