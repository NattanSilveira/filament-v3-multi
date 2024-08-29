<?php

use App\Jobs\GetDocumentsToNotifyJob;
use Illuminate\Support\Facades\Schedule;
use Spatie\Health\Commands\DispatchQueueCheckJobsCommand;

Schedule::job(GetDocumentsToNotifyJob::class)->everyTenSeconds();
Schedule::command(DispatchQueueCheckJobsCommand::class)->everyMinute();
