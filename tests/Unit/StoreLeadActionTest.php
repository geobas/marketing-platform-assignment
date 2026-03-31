<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Actions\StoreLeadAction;
use App\Contracts\LeadRepositoryInterface;
use App\DTOs\LeadData;
use App\Models\Lead;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StoreLeadActionTest extends TestCase
{
    #[Test]
    public function it_calls_repository_create_and_returns_lead()
    {
        $dto = new LeadData(
            fullName: 'Akis Testakis',
            email: 'dummy@yahoo.com',
            consent: true
        );

        $createdLead = new Lead([
            'full_name' => 'Akis Testakis',
            'email' => 'dummy@yahoo.com',
            'consent' => true,
        ]);

        $repository = Mockery::mock(LeadRepositoryInterface::class);

        $repository->shouldReceive('create')
            ->once()
            ->with($dto)
            ->andReturn($createdLead);

        $action = new StoreLeadAction($repository);

        $lead = $action->execute($dto);

        $this->assertInstanceOf(Lead::class, $lead);
        $this->assertEquals('Akis Testakis', $lead->full_name);
        $this->assertEquals('dummy@yahoo.com', $lead->email);
        $this->assertTrue($lead->consent);
    }
}
