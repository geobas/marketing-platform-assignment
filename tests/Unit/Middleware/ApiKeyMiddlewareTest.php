<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApiKeyMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Define a test route using the middleware
        Route::middleware(ApiKeyMiddleware::class)
            ->get('/test-api-key', fn () => response()->json(['success' => true]));
    }

    #[Test]
    public function it_allows_request_with_valid_api_key(): void
    {
        config(['app.api_key' => 'valid-key']);

        $response = $this->getJson('/test-api-key', [
            'X-API-KEY' => 'valid-key',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);
    }

    #[Test]
    public function it_blocks_request_with_invalid_api_key(): void
    {
        config(['app.api_key' => 'valid-key']);

        $response = $this->getJson('/test-api-key', [
            'X-API-KEY' => 'wrong-key',
        ]);

        $response->assertUnauthorized()
            ->assertJson([
                'error' => 'Unauthorized',
            ]);
    }

    #[Test]
    public function it_blocks_request_when_api_key_is_missing(): void
    {
        config(['app.api_key' => 'valid-key']);

        $response = $this->getJson('/test-api-key');

        $response->assertUnauthorized()
            ->assertJson([
                'error' => 'Unauthorized',
            ]);
    }
}
