<?php

namespace Tests\Unit;

use App\Actions\UpdateLeadAction;
use App\Contracts\LeadRepositoryInterface;
use App\DTOs\UpdateLeadData;
use App\Models\Lead;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateLeadActionTest extends TestCase
{
    #[Test]
    public function it_calls_repository_update_and_returns_updated_lead()
    {
        $dto = new UpdateLeadData(
            _id: '6501f8a6e8a1c8b1a0a1d0f0',
            fullName: 'Updated Name',
            email: 'updated@example.com',
            consent: true,
        );

        $updatedLead = new Lead([
            '_id' => $dto->_id,
            'full_name' => $dto->fullName,
            'email' => $dto->email,
            'consent' => $dto->consent,
        ]);

        $repository = Mockery::mock(LeadRepositoryInterface::class);

        $repository->shouldReceive('update')
            ->once()
            ->with($dto)
            ->andReturn($updatedLead);

        $action = new UpdateLeadAction($repository);

        $result = $action->execute($dto);

        $this->assertInstanceOf(Lead::class, $result);
        $this->assertEquals('Updated Name', $result->full_name);
        $this->assertEquals('updated@example.com', $result->email);
        $this->assertTrue($result->consent);
    }
}
