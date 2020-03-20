<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {

    Route::get('/replies/rules','RepliesController@rules');//微信规则
    Route::get('/replies/rules/show','RepliesController@rulesShow');//微信规则详情
    Route::post('/replies/rules','RepliesController@rulesCreate');//微信规则创建
    Route::put('/replies/rules','RepliesController@rulesUpdate');//微信规则编辑
    Route::delete('/replies/rules','RepliesController@rulesDestroy');//微信规则删除

    /*
    // Dashboard Routes...
    Route::get('/stats', 'DashboardStatsController@index')->name('horizon.stats.index');

    // Workload Routes...
    Route::get('/workload', 'WorkloadController@index')->name('horizon.workload.index');

    // Master Supervisor Routes...
    Route::get('/masters', 'MasterSupervisorController@index')->name('horizon.masters.index');

    // Monitoring Routes...
    Route::get('/monitoring', 'MonitoringController@index')->name('horizon.monitoring.index');
    Route::post('/monitoring', 'MonitoringController@store')->name('horizon.monitoring.store');
    Route::get('/monitoring/{tag}', 'MonitoringController@paginate')->name('horizon.monitoring-tag.paginate');
    Route::delete('/monitoring/{tag}', 'MonitoringController@destroy')->name('horizon.monitoring-tag.destroy');

    // Job Metric Routes...
    Route::get('/metrics/jobs', 'JobMetricsController@index')->name('horizon.jobs-metrics.index');
    Route::get('/metrics/jobs/{id}', 'JobMetricsController@show')->name('horizon.jobs-metrics.show');

    // Queue Metric Routes...
    Route::get('/metrics/queues', 'QueueMetricsController@index')->name('horizon.queues-metrics.index');
    Route::get('/metrics/queues/{id}', 'QueueMetricsController@show')->name('horizon.queues-metrics.show');

    // Job Routes...
    Route::get('/jobs/pending', 'PendingJobsController@index')->name('horizon.pending-jobs.index');
    Route::get('/jobs/completed', 'CompletedJobsController@index')->name('horizon.completed-jobs.index');
    Route::get('/jobs/failed', 'FailedJobsController@index')->name('horizon.failed-jobs.index');
    Route::get('/jobs/failed/{id}', 'FailedJobsController@show')->name('horizon.failed-jobs.show');
    Route::post('/jobs/retry/{id}', 'RetryController@store')->name('horizon.retry-jobs.show');
    Route::get('/jobs/{id}', 'JobsController@show')->name('horizon.jobs.show');
    */
});

// Catch-all Route...
Route::any('/auth', 'HomeController@auth')->name('wechat-reply.auth');
Route::any('/logout', 'HomeController@logout')->name('wechat-reply.logout');
Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('wechat-reply.index');