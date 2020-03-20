<?php

/*
 * This file is part of the achais/laravel-wechat-reply.
 *
 * (c) achais <i@achais.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    // Replies Module
    Route::get('/replies/rules', 'RepliesController@rules');
    Route::get('/replies/rules/show', 'RepliesController@rulesShow');
    Route::post('/replies/rules', 'RepliesController@rulesCreate');
    Route::put('/replies/rules', 'RepliesController@rulesUpdate');
    Route::delete('/replies/rules', 'RepliesController@rulesDestroy');
});

// View Route...
Route::any('/auth', 'HomeController@auth')->name('wechat-reply.auth');
Route::any('/logout', 'HomeController@logout')->name('wechat-reply.logout');
Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('wechat-reply.index');
