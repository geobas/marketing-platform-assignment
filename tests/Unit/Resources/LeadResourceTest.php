<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Http\Resources\LeadResource;
use App\Models\Lead;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LeadResourceTest extends TestCase
{
    #[Test]
    public function it_transforms_lead_resource_correctly()
    {
        $lead = new Lead;
        $lead->id = '123';
        $lead->full_name = 'John Doe';
        $lead->email = 'john@test.com';
        $lead->consent = true;

        $resource = new LeadResource($lead);

        $array = $resource->toArray(request());

        $this->assertSame([
            'id' => '123',
            'full_name' => 'John Doe',
            'email' => 'john@test.com',
            'consent' => true,
        ], $array);
    }
}
