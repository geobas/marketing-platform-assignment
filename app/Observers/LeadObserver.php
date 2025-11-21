<?php

namespace App\Observers;

use App\Jobs\SyncLeadToMailchimp;
use App\Models\Lead;

class LeadObserver
{
    /**
     * Handle the Lead 'created' event.
     */
    public function created(Lead $lead): void
    {
        if ($lead->consent) {
            SyncLeadToMailchimp::dispatch(
                null,
                $lead->email,
                $lead->full_name
            );
        }
    }

    /**
     * Handle the Lead 'updated' event.
     */
    public function updated(Lead $lead): void
    {
        if ($lead->consent && $lead->wasChanged(['email', 'full_name'])) {
            $oldEmail = $lead->getOriginal('email');

            SyncLeadToMailchimp::dispatch(
                $oldEmail,
                $lead->email,
                $lead->full_name
            );
        }
    }
}
