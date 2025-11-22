<?php

namespace Tests\Unit;

use App\DTOs\LeadData;
use App\DTOs\UpdateLeadData;
use App\Jobs\SyncLeadToMailchimp;
use App\Models\Lead;
use App\Repositories\LeadRepository;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LeadRepositoryTest extends TestCase
{
    protected LeadRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new LeadRepository(new Lead);

        // Clean the leads collection before each test
        Lead::query()->delete();

        // Prevent Mailchimp job from executing
        Queue::fake(); // ensures queued jobs do not run
        Bus::fake();   // allows asserting jobs dispatched
    }

    #[Test]
    public function it_creates_a_lead_and_dispatches_mailchimp_job()
    {
        $data = new LeadData(
            fullName: 'Akis Testakis',
            email: 'dummy@yahoo.com',
            consent: true
        );

        $lead = $this->repository->create($data);

        $this->assertNotNull($lead->_id);
        $this->assertEquals('Akis Testakis', $lead->full_name);
        $this->assertEquals('dummy@yahoo.com', $lead->email);
        $this->assertTrue($lead->consent);

        // Assert the Mailchimp job was dispatched
        Bus::assertDispatched(SyncLeadToMailchimp::class, fn ($job) => $job->newEmail === 'dummy@yahoo.com');
    }

    #[Test]
    public function it_updates_a_lead_and_dispatches_mailchimp_job()
    {
        $lead = Lead::factory()->create([
            'full_name' => 'Akis Testakis',
            'email' => 'dummy@yahoo.com',
            'consent' => true,
        ]);

        $updateData = new UpdateLeadData(
            _id: (string) $lead->_id,
            fullName: 'Geo Bas',
            email: 'geobas@yahoo.com',
            consent: true
        );

        $updatedLead = $this->repository->update($updateData);

        $this->assertEquals('Geo Bas', $updatedLead->full_name);
        $this->assertEquals('geobas@yahoo.com', $updatedLead->email);

        // Assert the Mailchimp job was dispatched
        Bus::assertDispatched(SyncLeadToMailchimp::class, fn ($job) => $job->newEmail === 'geobas@yahoo.com');
    }

    #[Test]
    public function it_finds_a_lead_by_id_or_returns_null()
    {
        $lead = Lead::factory()->create();

        $found = $this->repository->findById((string) $lead->_id);
        $this->assertEquals($lead->_id, $found->_id);

        $notFound = $this->repository->findById('6501f8a6e8a1c8b1a0a1d0f0');
        $this->assertNull($notFound);
    }

    #[Test]
    public function it_checks_email_existence_excluding_id()
    {
        $lead1 = Lead::factory()->create(['email' => 'a@example.com']);
        $lead2 = Lead::factory()->create(['email' => 'b@example.com']);

        $this->assertFalse(
            $this->repository->existsByEmailExceptId('a@example.com', (string) $lead1->_id)
        );

        $this->assertTrue(
            $this->repository->existsByEmailExceptId('a@example.com', (string) $lead2->_id)
        );
    }

    #[Test]
    public function it_returns_paginated_leads()
    {
        Lead::factory()->count(7)->create();

        $results = $this->repository->list(['per_page' => 5]);

        $this->assertEquals(5, $results->count());
        $this->assertEquals(7, $results->total());
        $this->assertEquals(2, $results->lastPage());
    }
}
