<?php

namespace Tests\Unit;

use App\Actions\ListLeadsAction;
use App\Contracts\LeadRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListLeadsActionTest extends TestCase
{
    #[Test]
    public function it_calls_repository_list_and_returns_a_paginator()
    {
        $input = ['per_page' => 10];

        // Fake paginator instance
        $paginator = new LengthAwarePaginator(
            items: [],
            total: 0,
            perPage: 10,
            currentPage: 1
        );

        $repository = Mockery::mock(LeadRepositoryInterface::class);

        $repository->shouldReceive('list')
            ->once()
            ->with($input)
            ->andReturn($paginator);

        $action = new ListLeadsAction($repository);

        $result = $action->execute($input);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
        $this->assertCount(0, $result->items());
    }
}
