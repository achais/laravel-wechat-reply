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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    use EventMap;

    /**
     * 启动
     */
    public function boot()
    {
        $this->registerEvents();
        $this->registerRoutes();
        $this->registerResources();

        $this->defineConfigPublishing();
        $this->defineMigrationPublishing();
        $this->defineAssetPublishing();

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(\dirname(__DIR__) . '/database/migrations/');
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
            'domain' => config('wechat_reply.domain', null),
            'prefix' => config('wechat_reply.path'),
            'namespace' => 'Achais\LaravelWechatReply\Http\Controllers',
            'middleware' => config('wechat_reply.middleware', 'web'),
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    protected function registerResources()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'wechat-reply');
    }

    public function defineConfigPublishing()
    {
        $this->publishes([
            __DIR__ . '/../config/wechat_reply.php' => config_path('wechat_reply.php'),
        ], 'config');
    }

    public function defineMigrationPublishing()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'migrations');
    }

    public function defineAssetPublishing()
    {
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/wechat-reply'),
        ], 'assets');
    }

    /**
     * 注册
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/wechat_reply.php',
            'wechat_reply'
        );
    }
}
