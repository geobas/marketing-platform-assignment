<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use App\Exceptions\LeadRepositoryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class LeadRepositoryExceptionTest extends TestCase
{
    public function test_render_returns_json_when_request_expects_json(): void
    {
        $exception = new LeadRepositoryException(
            message: 'Something went wrong',
            internalMessage: 'DB error',
            status: 422
        );

        $request = Request::create('/test', 'GET');
        $request->headers->set('Accept', 'application/json');

        $response = $exception->render($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(
            ['error' => 'Something went wrong'],
            $response->getData(true)
        );
    }

    public function test_render_redirects_back_when_not_json(): void
    {
        $exception = new LeadRepositoryException;

        $request = Request::create('/test', 'GET');

        // Start session and set previous URL properly
        $this->app->make('session')->start();
        $this->app->make('session')->setPreviousUrl(url('/previous'));

        $response = $exception->render($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(url('/previous'), $response->getTargetUrl());
        $this->assertTrue(session()->has('error'));
        $this->assertEquals(
            'Failed to save or update lead',
            session('error')
        );
    }

    public function test_report_logs_error_with_internal_message(): void
    {
        Log::shouldReceive('error')
            ->once()
            ->with('LeadRepositoryException: DB failure');

        $exception = new LeadRepositoryException(
            message: 'User message',
            internalMessage: 'DB failure'
        );

        $exception->report();
    }
}
