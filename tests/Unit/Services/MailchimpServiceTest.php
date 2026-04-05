<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Exceptions\MailchimpServiceException;
use App\Services\MailchimpService;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use MailchimpMarketing\ApiClient;
use Mockery;
use Tests\TestCase;

class MailchimpServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    private function makeService($listsMock): MailchimpService
    {
        config()->set('services.mailchimp.list_id', 'test_list_id');
        config()->set('services.mailchimp.api_key', 'fake');
        config()->set('services.mailchimp.server_prefix', 'us1');

        $client = new ApiClient;

        $client->lists = $listsMock;

        return new MailchimpService($client);
    }

    public function test_add_to_list_success(): void
    {
        $listsMock = Mockery::mock();
        $listsMock->shouldReceive('addListMember')
            ->once()
            ->with('test_list_id', Mockery::on(fn ($data) => $data['email_address'] === 'john@example.com'
                && $data['merge_fields']['FNAME'] === 'John'
                && $data['merge_fields']['LNAME'] === 'Doe'))
            ->andReturnNull();

        $service = $this->makeService($listsMock);

        $service->addToList('john@example.com', 'John Doe');

        $this->assertTrue(true); // no exception = success
    }

    public function test_add_to_list_handles_request_exception(): void
    {
        $listsMock = Mockery::mock();

        $exception = Mockery::mock(RequestException::class);
        $exception->shouldReceive('getResponse->getBody->__toString')
            ->andReturn('API error');

        $listsMock->shouldReceive('addListMember')
            ->once()
            ->andThrow($exception);

        Log::shouldReceive('error')
            ->once()
            ->with('Mailchimp addToList failed', Mockery::type('array'));

        $service = $this->makeService($listsMock);

        $this->expectException(MailchimpServiceException::class);
        $this->expectExceptionMessage('Failed to subscribe john@example.com to Mailchimp.');

        $service->addToList('john@example.com', 'John Doe');
    }

    public function test_add_to_list_handles_generic_throwable(): void
    {
        $listsMock = Mockery::mock();

        $listsMock->shouldReceive('addListMember')
            ->once()
            ->andThrow(new \Exception('Boom'));

        Log::shouldReceive('error')
            ->once()
            ->with('Mailchimp addToList unexpected error', Mockery::type('array'));

        $service = $this->makeService($listsMock);

        $this->expectException(MailchimpServiceException::class);
        $this->expectExceptionMessage('Failed to subscribe contact to Mailchimp. Please try again later.');

        $service->addToList('john@example.com', 'John Doe');
    }

    public function test_update_contact_success(): void
    {
        $listsMock = Mockery::mock();

        $listsMock->shouldReceive('setListMember')
            ->once()
            ->with(
                'test_list_id',
                md5(strtolower('old@example.com')),
                Mockery::on(fn ($data) => $data['email_address'] === 'new@example.com'
                    && $data['merge_fields']['FNAME'] === 'John'
                    && $data['merge_fields']['LNAME'] === 'Doe')
            )
            ->andReturnNull();

        $service = $this->makeService($listsMock);

        $service->updateContact('old@example.com', 'new@example.com', 'John Doe');

        $this->assertTrue(true);
    }

    public function test_update_contact_handles_request_exception(): void
    {
        $listsMock = Mockery::mock();

        $exception = Mockery::mock(RequestException::class);
        $exception->shouldReceive('getResponse->getBody->__toString')
            ->andReturn('API error');

        $listsMock->shouldReceive('setListMember')
            ->once()
            ->andThrow($exception);

        Log::shouldReceive('error')
            ->once()
            ->with('Mailchimp updateContact failed', Mockery::type('array'));

        $service = $this->makeService($listsMock);

        $this->expectException(MailchimpServiceException::class);
        $this->expectExceptionMessage('Failed to subscribe new@example.com to Mailchimp.');

        $service->updateContact('old@example.com', 'new@example.com', 'John Doe');
    }

    public function test_sync_contact_calls_add_to_list_when_old_email_is_null(): void
    {
        $service = Mockery::mock(MailchimpService::class)
            ->makePartial();

        $service->shouldReceive('addToList')
            ->once()
            ->with('new@example.com', 'John Doe');

        /** @var MailchimpService $service */
        $service->syncContact(null, 'new@example.com', 'John Doe');

        $this->addToAssertionCount(1);
    }

    public function test_sync_contact_calls_update_contact_when_old_email_exists(): void
    {
        $service = Mockery::mock(MailchimpService::class)
            ->makePartial();

        $service->shouldReceive('updateContact')
            ->once()
            ->with('old@example.com', 'new@example.com', 'John Doe');

        /** @var MailchimpService $service */
        $service->syncContact('old@example.com', 'new@example.com', 'John Doe');

        $this->addToAssertionCount(1);
    }
}
