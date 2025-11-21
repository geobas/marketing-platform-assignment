<?php

namespace App\Jobs;

use App\Services\MailchimpService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncLeadToMailchimp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The time (in seconds) before retrying the job.
     */
    public $backoff = [10, 30, 60];

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ?string $oldEmail,
        public string $newEmail,
        public string $fullName
    ) {}

    /**
     * Execute the job.
     */
    public function handle(MailchimpService $mailchimp): void
    {
        $mailchimp->syncContact(
            $this->oldEmail,
            $this->newEmail,
            $this->fullName
        );
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error('Failed to sync lead to Mailchimp', [
            'oldEmail' => $this->oldEmail ?? '',
            'newEmail' => $this->newEmail,
            'fullName' => $this->fullName,
            'error' => $exception->getMessage(),
        ]);
    }
}
