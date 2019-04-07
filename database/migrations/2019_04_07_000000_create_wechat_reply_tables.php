<?php

/*
 * This file is part of the achais/laravel-wechat-reply.
 *
 * (c) achais <i@achais.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatReplyTables extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        $tableNames = config('wechat_reply.table_names');

        Schema::create($tableNames['rules'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('rule_name', 60);
            $table->string('reply_mode')->default('random'); // random,all
            $table->timestamps();
        });

        Schema::create($tableNames['keywords'], function (Blueprint $table) use ($tableNames) {
            $table->increments('id');
            $table->string('keyword')->index();
            $table->boolean('full_match')->default(false);
            $table->unsignedInteger('weixin_rule_id');
            $table->timestamps();

            $table->foreign('weixin_rule_id')->references('id')->on($tableNames['rules'])->onDelete('cascade');
        });

        Schema::create($tableNames['replies'], function (Blueprint $table) use ($tableNames) {
            $table->increments('id');
            $table->string('type'); // appmsg,text,image,audio,video
            $table->string('content');
            $table->unsignedInteger('weixin_rule_id');
            $table->timestamps();

            $table->foreign('weixin_rule_id')->references('id')->on($tableNames['rules'])->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        $tableNames = config('wechat_reply.table_names');

        Schema::drop($tableNames['rules']);
        Schema::drop($tableNames['keywords']);
        Schema::drop($tableNames['replies']);
    }
}
