<?php

namespace Giagara\AsanaWebhook\Tests\Feature;

use Giagara\AsanaWebhook\Tests\Classes\FakeInvokableClass;
use Giagara\AsanaWebhook\Tests\Classes\FakeInvokableErrorClass;
use Giagara\AsanaWebhook\Tests\TestCase;

class HttpTest extends TestCase
{
    public function test_controller_handle_data_correctly()
    {

        config(['asana-webhook.routes' => [
            'webhook' => FakeInvokableClass::class,
        ]]);

        $this->reloadRoutes();

        $response = $this->post(
            'api/webhook',
            json_decode(file_get_contents(__DIR__.'/../fixtures/payload.json'), true),
            [
                'x-asana-request-signature' => 'a valid signature',
            ]
        );

        $response->assertStatus(204);

    }

    public function test_controller_with_invalid_route()
    {

        config(['asana-webhook.routes' => [
            'webhook' => FakeInvokableClass::class,
        ]]);

        $this->reloadRoutes();

        $response = $this->post(
            'api/webhook-error',
            json_decode(file_get_contents(__DIR__.'/../fixtures/payload.json'), true),
            [
                'x-asana-request-signature' => 'a valid signature',
            ]
        );

        $response->assertStatus(404);

    }

    public function test_controller_with_invalid_class()
    {

        config(['asana-webhook.routes' => [
            'webhook' => FakeInvokableErrorClass::class,
        ]]);

        $this->reloadRoutes();

        $response = $this->post(
            'api/webhook',
            json_decode(file_get_contents(__DIR__.'/../fixtures/payload.json'), true),
            [
                'x-asana-request-signature' => 'a valid signature',
            ]
        );

        $response->assertStatus(500);

    }
}
