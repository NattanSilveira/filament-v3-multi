<?php

namespace App\Jobs;

use App\Models\Document;
use App\Notifications\DocumentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class GetDocumentsToNotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    public function handle(): void
    {
        Log::info('GetDocumentsToNotifyJob started');
        $documents = Document::where('should_notify', true)
            ->whereDate('notify_at', now())
            ->get();
        foreach ($documents as $document) {
            Notification::routes([
                'mail' => $document->emails_to_notify
            ]) ->notify(new DocumentNotification($document));
        }
    }
}
