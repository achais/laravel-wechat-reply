<?php

namespace Achais\LaravelWechatReply;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
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

    public function register()
    {
        $this->mergeConfigFrom(
            \dirname(__DIR__).'/config/wechat_reply.php',
            'wechat_reply'
        );
    }
}
