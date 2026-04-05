<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Controllers;

use App\Actions\ListLeadsAction;
use App\Actions\UpdateLeadAction;
use App\DTOs\UpdateLeadData;
use App\Http\Controllers\LeadController;
use App\Http\Requests\UpdateLeadRequest;
use App\Models\Lead;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Mockery;
use Tests\TestCase;

class LeadControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_view_with_leads()
    {
        $request = Request::create('/leads', 'GET', ['per_page' => 10]);

        $leads = new LengthAwarePaginator(
            [Mockery::mock(Lead::class), Mockery::mock(Lead::class)],
            2,  // total items
            10, // per page
            1   // current page
        );

        $listLeadsAction = Mockery::mock(ListLeadsAction::class);
        $listLeadsAction->shouldReceive('execute')
            ->once()
            ->with($request->all())
            ->andReturn($leads);

        $controller = new LeadController;
        $response = $controller->index($request, $listLeadsAction);

        $this->assertInstanceOf(View::class, $response);
        $this->assertArrayHasKey('perPage', $response->getData());
        $this->assertArrayHasKey('leads', $response->getData());
        $this->assertEquals(10, $response->getData()['perPage']);
        $this->assertEquals($leads, $response->getData()['leads']);
    }

    public function test_edit_returns_view_with_lead()
    {
        $lead = Mockery::mock(Lead::class);

        $controller = new LeadController;
        $response = $controller->edit($lead);

        $this->assertInstanceOf(View::class, $response);
        $this->assertArrayHasKey('lead', $response->getData());
        $this->assertEquals($lead, $response->getData()['lead']);
    }

    public function test_update_executes_action_and_redirects()
    {
        $lead = Mockery::mock(Lead::class);

        // Properly create the DTO with required fields
        $dto = new UpdateLeadData(
            '_id-123',
            'John Doe',
            'john@example.com',
            true
        );

        $updatedLead = Mockery::mock(Lead::class);
        $updatedLead->shouldReceive('getRouteKey')->andReturn(123);

        $request = Mockery::mock(UpdateLeadRequest::class);
        $request->shouldReceive('toDto')
            ->twice() // controller calls it twice
            ->andReturn($dto);

        $updateLeadAction = Mockery::mock(UpdateLeadAction::class);
        $updateLeadAction->shouldReceive('execute')
            ->once()
            ->with($dto)
            ->andReturn($updatedLead);

        // Fake the log
        Log::shouldReceive('info')
            ->once()
            ->with('Lead updated successfully.', ['lead' => $dto->toArray()]);

        $controller = new LeadController;
        $response = $controller->update($request, $lead, $updateLeadAction);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(
            route('leads.edit', ['lead' => 123]),
            $response->getTargetUrl()
        );
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Lead updated successfully.', session('success'));
    }
}
