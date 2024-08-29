<?php

use Illuminate\Support\Facades\Route;

//route to dispatch the job
Route::get('/dispatch-job', function () {
    \App\Jobs\GetDocumentsToNotifyJob::dispatch();
    return 'Job dispatched';
});
