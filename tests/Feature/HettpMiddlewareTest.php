<?php

namespace Giagara\AsanaWebhook\Tests\Feature;

use Giagara\AsanaWebhook\AsanaWebhookServiceProvider;
use Giagara\AsanaWebhook\Tests\Classes\FakeInvokableClass;
use Giagara\AsanaWebhook\Tests\Classes\FakeMiddleware;
use Giagara\AsanaWebhook\Tests\TestCase;

class HettpMiddlewareTest extends TestCase
{
    public function test_controller_handle_data_correctly_with_one_middleware()
    {

        config(['asana-webhook.routes' => [
            'webhook' => [
                'class' => FakeInvokableClass::class,
                'name' => 'webhook-1',
                'middleware' => [FakeMiddleware::class],
            ]
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
}
