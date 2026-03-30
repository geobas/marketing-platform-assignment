<?php

namespace Tests\Feature;

use App\Actions\ListLeadsAction;
use App\Actions\StoreLeadAction;
use App\Actions\UpdateLeadAction;
use App\Contracts\LeadRepositoryInterface;
use App\DTOs\LeadData;
use App\DTOs\UpdateLeadData;
use App\Models\Lead;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LeadRoutesTest extends TestCase
{
    /**
     * The API key to use for requests.
     */
    protected string $apiKey;

    /**
     * Set up the test environment before each test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->apiKey = config('app.api_key');
    }

    /**
     * Get headers with API key.
     */
    protected function headers(): array
    {
        return [
            'X-API-KEY' => $this->apiKey,
        ];
    }

    #[Test]
    public function it_returns_paginated_leads()
    {
        $paginator = new LengthAwarePaginator([], 0, 10);

        $action = Mockery::mock(ListLeadsAction::class);
        $action->shouldReceive('execute')
            ->once()
            ->andReturn($paginator);

        $this->app->instance(ListLeadsAction::class, $action);

        $response = $this->getJson('/api/leads?per_page=10', $this->headers());

        $response->assertOk()->assertJsonStructure(['data']);
    }

    #[Test]
    public function it_stores_a_lead()
    {
        $payload = [
            'full_name' => 'Akis Testakis',
            'email' => 'dummy@yahoo.com',
            'consent' => true,
        ];

        $action = Mockery::mock(StoreLeadAction::class);
        $action->shouldReceive('execute')
            ->once()
            ->with(Mockery::type(LeadData::class));

        $this->app->instance(StoreLeadAction::class, $action);

        $response = $this->postJson('/api/leads', $payload, $this->headers());

        $response->assertCreated()->assertJson(['message' => 'Lead stored successfully.']);
    }

    #[Test]
    public function it_updates_a_lead()
    {
        $leadId = '65c3ab987df4a210ec12be99';

        $payload = [
            'full_name' => 'Akis Testakis',
            'email' => 'dummy@yahoo.com',
            'consent' => true,
        ];

        $fakeLead = new Lead;
        $fakeLead->_id = $leadId;
        $fakeLead->email = 'old@yahoo.com';
        $fakeLead->full_name = 'Old Name';

        $repository = Mockery::mock(LeadRepositoryInterface::class);
        $repository->shouldReceive('findById')
            ->once()
            ->with($leadId)
            ->andReturn($fakeLead);

        $repository->shouldReceive('existsByEmailExceptId')
            ->once()
            ->with('dummy@yahoo.com', $leadId)
            ->andReturn(false);

        $this->app->instance(LeadRepositoryInterface::class, $repository);

        $action = Mockery::mock(UpdateLeadAction::class);
        $action->shouldReceive('execute')
            ->once()
            ->with(Mockery::type(UpdateLeadData::class));

        $this->app->instance(UpdateLeadAction::class, $action);

        $response = $this->putJson("/api/leads/{$leadId}", $payload, $this->headers());

        $response->assertOk()->assertJson(['message' => 'Lead updated successfully.']);
    }
}
