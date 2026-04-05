<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions;

use App\Exceptions\MailchimpServiceException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class MailchimpServiceExceptionTest extends TestCase
{
    public function test_render_returns_json_when_request_expects_json(): void
    {
        $exception = new MailchimpServiceException(
            message: 'Custom error',
            internalMessage: 'API failure',
            status: 400
        );

        $request = Request::create('/test', 'GET');
        $request->headers->set('Accept', 'application/json');

        $response = $exception->render($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals(
            ['error' => 'Custom error'],
            $response->getData(true)
        );
    }

    public function test_render_redirects_back_when_not_json(): void
    {
        $exception = new MailchimpServiceException;

        $request = Request::create('/test', 'GET');

        // Properly set previous URL for `back()`
        $this->app->make('session')->start();
        $this->app->make('session')->setPreviousUrl(url('/previous'));

        $response = $exception->render($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals(url('/previous'), $response->getTargetUrl());
        $this->assertTrue(session()->has('error'));
        $this->assertEquals(
            'Mailchimp service error',
            session('error')
        );
    }

    public function test_report_logs_error_with_internal_message(): void
    {
        Log::shouldReceive('error')
            ->once()
            ->with('MailchimpServiceException: API failure');

        $exception = new MailchimpServiceException(
            message: 'User-facing message',
            internalMessage: 'API failure'
        );

        $exception->report();
    }
}
