<?php

namespace Giagara\AsanaWebhook\Tests\Feature;

use Giagara\AsanaWebhook\Tests\Classes\FakeInvokableClass;
use Giagara\AsanaWebhook\Tests\Classes\FakeInvokableErrorClass;
use Giagara\AsanaWebhook\Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class HttpTest extends TestCase
{
    public function test_controller_handle_hook_secret_correctly()
    {

        config(['asana-webhook.routes' => [
            'webhook' => FakeInvokableClass::class,
        ]]);

        $secret = Str::random(10);

        $this->reloadRoutes();

        $response = $this->post(
            'api/webhook',
            json_decode(file_get_contents(__DIR__.'/../fixtures/payload.json'), true),
            [
                'X-Hook-Secret' => $secret,
            ]
        );

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $response->assertHeader('X-Hook-Secret', $secret);

    }

    public function test_controller_abort_if_header_not_set()
    {

        config(['asana-webhook.routes' => [
            'webhook' => FakeInvokableClass::class,
        ]]);

        $this->reloadRoutes();

        $response = $this->post(
            'api/webhook',
            json_decode(file_get_contents(__DIR__.'/../fixtures/payload.json'), true)
        );

        $response->assertStatus(Response::HTTP_FORBIDDEN);

    }

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

        $response->assertStatus(Response::HTTP_NO_CONTENT);

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

        $response->assertStatus(Response::HTTP_NOT_FOUND);

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

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);

    }
}
