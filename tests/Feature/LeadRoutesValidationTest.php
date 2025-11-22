<?php

namespace Tests\Feature;

use App\Actions\ListLeadsAction;
use App\Actions\StoreLeadAction;
use App\Actions\UpdateLeadAction;
use App\Contracts\LeadRepositoryInterface;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LeadRoutesValidationTest extends TestCase
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
    public function list_leads_fails_if_per_page_invalid()
    {
        $action = Mockery::mock(ListLeadsAction::class);
        $this->app->instance(ListLeadsAction::class, $action);

        $response = $this->getJson('/api/leads?per_page=50', $this->headers());

        $response->assertStatus(422)->assertJsonValidationErrors(['per_page']);
    }

    #[Test]
    public function store_lead_fails_if_required_fields_missing_or_invalid()
    {
        $payload = [
            'full_name' => '',
            'email' => 'invalid-email',
            'consent' => 'not-a-boolean',
        ];

        $action = Mockery::mock(StoreLeadAction::class);
        $this->app->instance(StoreLeadAction::class, $action);

        $response = $this->postJson('/api/leads', $payload, $this->headers());

        $response->assertStatus(422)->assertJsonValidationErrors(['full_name', 'email', 'consent']);
    }

    #[Test]
    public function store_lead_fails_if_email_is_example_domain()
    {
        $payload = [
            'full_name' => 'Valid Name',
            'email' => 'john@example.com',
            'consent' => true,
        ];

        $action = Mockery::mock(StoreLeadAction::class);
        $this->app->instance(StoreLeadAction::class, $action);

        $response = $this->postJson('/api/leads', $payload, $this->headers());

        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function update_lead_fails_if_required_fields_missing_or_invalid()
    {
        $leadId = '65c3ab987df4a210ec12be99';

        $payload = [
            'full_name' => '',
            'email' => 'invalid-email',
            'consent' => 'not-a-boolean',
        ];

        $repository = Mockery::mock(LeadRepositoryInterface::class);
        $repository->shouldReceive('findById')->andReturn(null);
        $repository->shouldReceive('existsByEmailExceptId')->andReturn(false);
        $this->app->instance(LeadRepositoryInterface::class, $repository);

        $action = Mockery::mock(UpdateLeadAction::class);
        $this->app->instance(UpdateLeadAction::class, $action);

        $response = $this->putJson("/api/leads/{$leadId}", $payload, $this->headers());

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['full_name', 'email', 'consent', 'lead']);
    }

    #[Test]
    public function update_lead_fails_if_email_already_taken()
    {
        $leadId = '65c3ab987df4a210ec12be99';

        $payload = [
            'full_name' => 'John Doe',
            'email' => 'taken@example.com',
            'consent' => true,
        ];

        $repository = Mockery::mock(LeadRepositoryInterface::class);
        $repository->shouldReceive('findById')->andReturn(new \App\Models\Lead);
        $repository->shouldReceive('existsByEmailExceptId')
            ->with('taken@example.com', $leadId)
            ->andReturn(true);
        $this->app->instance(LeadRepositoryInterface::class, $repository);

        $action = Mockery::mock(UpdateLeadAction::class);
        $this->app->instance(UpdateLeadAction::class, $action);

        $response = $this->putJson("/api/leads/{$leadId}", $payload, $this->headers());

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    #[Test]
    public function update_lead_fails_if_email_is_example_domain()
    {
        $leadId = '65c3ab987df4a210ec12be99';

        $payload = [
            'full_name' => 'John Doe',
            'email' => 'jane@example.org',
            'consent' => true,
        ];

        $repository = Mockery::mock(LeadRepositoryInterface::class);
        $repository->shouldReceive('findById')->andReturn(new \App\Models\Lead);
        $repository->shouldReceive('existsByEmailExceptId')->andReturn(false);
        $this->app->instance(LeadRepositoryInterface::class, $repository);

        $action = Mockery::mock(UpdateLeadAction::class);
        $this->app->instance(UpdateLeadAction::class, $action);

        $response = $this->putJson("/api/leads/{$leadId}", $payload, $this->headers());

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
