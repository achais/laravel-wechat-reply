<?php

/*
 * This file is part of the achais/laravel-wechat-reply.
 *
 * (c) achais <i@achais.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Achais\LaravelWechatReply;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    use EventMap;

    public function boot()
    {
        $this->registerEvents();
        $this->registerRoutes();
        $this->registerResources();
        $this->defineAssetPublishing();

        $this->publishes([
            \dirname(__DIR__).'/config/wechat_reply.php' => config_path('wechat_reply.php'),
        ], 'config');

        $this->publishes([
            \dirname(__DIR__).'/database/migrations/' => database_path('migrations'),
        ], 'migrations');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(\dirname(__DIR__).'/migrations/');
        }
    }

    protected function registerEvents()
    {
        $events = $this->app->make(Dispatcher::class);

        foreach ($this->events as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }
    }

    protected function registerRoutes()
    {
        Route::group([
            'domain' => config('wechat-reply.domain', null),
            'prefix' => config('wechat-reply.path'),
            'namespace' => 'Achais\LaravelWechatReply\Http\Controllers',
            'middleware' => config('wechat-reply.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'wechat-reply');
    }

    public function defineAssetPublishing()
    {
        $this->publishes([
            WECHAT_REPLY_PATH.'/public' => public_path('vendor/wechat-reply'),
        ], 'wechat-reply-assets');
    }

    public function register()
    {
        if (! defined('WECHAT_REPLY_PATH')) {
            define('WECHAT_REPLY_PATH', realpath(__DIR__.'/../'));
        }

        $this->mergeConfigFrom(
            \dirname(__DIR__).'/config/wechat_reply.php',
            'wechat_reply'
        );
    }
}
