<?php

/*
 * This file is part of the achais/laravel-wechat-reply.
 *
 * (c) achais <i@achais.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Achais\LaravelWechatReply\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'wechat-reply:install';

    protected $description = 'Install all of the wechat reply resources.';

    public function handle()
    {
        $this->comment('Publishing Wechat Reply Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'provider']);

        $this->comment('Publishing Wechat Reply Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'assets']);

        $this->comment('Publishing Wechat Reply Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'config']);

//        $this->registerHorizonServiceProvider();

        $this->info('Wechat Reply scaffolding installed successfully.');
    }

    protected function registerHorizonServiceProvider()
    {
    }
}
