<?php

namespace AppModules\Authorization\Tests\Unit;

use Mockery;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use AppModules\Authorization\Services\ExternalAuthorizationService;

class ExternalAuthorizationServiceTest extends TestCase
{
    public function test_it_authorizes_transfer_when_service_returns_authorized()
    {
        $mockClient = Mockery::mock(Client::class);
        $mockClient->shouldReceive('get')
            ->andReturn(new Response(200, [], json_encode(['status' => 'success', 'data' => ['authorization' => true]])));

        $service = new ExternalAuthorizationService($mockClient);

        $this->assertTrue($service->authorize());
    }

    public function test_it_fails_to_authorize_when_service_does_not_return_authorized()
    {
        $mockClient = Mockery::mock(Client::class);
        $mockClient->shouldReceive('get')
            ->andReturn(new Response(200, [], json_encode(['status' => 'fail', 'data' => ['authorization' => false]])));

        $service = new ExternalAuthorizationService($mockClient);

        $this->assertFalse($service->authorize());
    }
}
