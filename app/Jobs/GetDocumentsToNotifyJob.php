<?php

namespace App\Jobs;

use App\Models\Document;
use App\Models\SystemLog;
use App\Notifications\DocumentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
        SystemLog::create([
            'content' => 'GetDocumentsToNotifyJob',
            'class' => 'GetDocumentsToNotifyJob',
            'method' => 'handle',
            'line' => __LINE__,
            'file' => __FILE__,
            'trace' => json_encode(debug_backtrace()),
            'user_id' => null,
        ]);

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
