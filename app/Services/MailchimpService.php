<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use MailchimpMarketing\ApiClient;
use Throwable;

class MailchimpService
{
    /**
     * Constructor
     */
    public function __construct(
        protected ApiClient $client = new ApiClient,
        protected string $listId = '',
    ) {
        $this->client->setConfig([
            'apiKey' => config('services.mailchimp.api_key'),
            'server' => config('services.mailchimp.server_prefix'),
        ]);

        $this->listId = config('services.mailchimp.list_id');
    }

    /**
     * Subscribe a new contact to the list.
     */
    public function addToList(string $email, string $fullName): void
    {
        [$first, $last] = $this->splitName($fullName);

        try {
            $this->client->lists->addListMember($this->listId, [
                'email_address' => $email,
                'status' => 'subscribed',
                'merge_fields' => [
                    'FNAME' => $first,
                    'LNAME' => $last,
                ],
            ]);
        } catch (Throwable $e) {
            Log::error('Mailchimp addToList failed', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Update an existing contact.
     */
    public function updateContact(string $oldEmail, string $newEmail, string $fullName): void
    {
        [$first, $last] = $this->splitName($fullName);

        $subscriberHash = md5(strtolower($oldEmail));

        try {
            $this->client->lists->setListMember(
                $this->listId,
                $subscriberHash,
                [
                    'email_address' => $newEmail,
                    'status_if_new' => 'subscribed',
                    'merge_fields' => [
                        'FNAME' => $first,
                        'LNAME' => $last,
                    ],
                ]
            );
        } catch (Throwable $e) {
            Log::error('Mailchimp updateContact failed', [
                'old_email' => $oldEmail,
                'new_email' => $newEmail,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Sync contact: add or update.
     */
    public function syncContact(?string $oldEmail, string $newEmail, string $fullName): void
    {
        if ($oldEmail === null) {
            // First time: add only if consent is true
            $this->addToList($newEmail, $fullName);
        } else {
            // Update or create if missing
            $this->updateContact($oldEmail, $newEmail, $fullName);
        }
    }

    /**
     * Split full name into first and last name.
     */
    private function splitName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName), 2);

        return [
            $parts[0] ?? '',
            $parts[1] ?? '',
        ];
    }
}
