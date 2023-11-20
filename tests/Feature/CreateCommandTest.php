<?php

namespace Giagara\AsanaWebhook\Tests\Feature;

use Giagara\AsanaWebhook\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class CreateCommandTest extends TestCase
{
    /**
     * @dataProvider httpProvider
     */
    public function test_command_will_execute_correctly(int $status, string $fixture, int $exit_code, ?string $target, ?string $route)
    {

        config(['asana-webhook.personal_access_token' => 'test_value']);

        config(['asana-webhook.routes' => [
            'webhook' => [
                'class' => FakeInvokableClass::class,
                'name' => 'webhook-1',
                'middleware' => [FakeMiddleware::class],
            ],
        ]]);

        $this->reloadRoutes();

        Http::fake([
            'https://app.asana.com/api/1.0/webhooks' => Http::response(
                json_decode(file_get_contents(__DIR__."/../fixtures/{$fixture}.json"), true),
                $status
            ),
        ]);

        $base_command = 'asana:create-webhook --resource=1 ';

        if ($target !== null) {
            $base_command .= ' --target='.$target;
        } else {
            $base_command .= ' --route='.$route;
        }

        $this->artisan($base_command)->assertExitCode($exit_code);

    }

    public function httpProvider(): array
    {
        return [
            'ok with target' => [
                'status' => 200,
                'fixture' => '200',
                'exit_code' => 0,
                'target' => 'https://example.com',
                'route' => null,
            ],
            'error with target' => [
                'status' => 400,
                'fixture' => '400',
                'exit_code' => 91,
                'target' => 'https://example.com',
                'route' => null,
            ],
            'ok with route' => [
                'status' => 200,
                'fixture' => '200',
                'exit_code' => 0,
                'target' => null,
                'route' => 'webhook-1',
            ],
            'error with route' => [
                'status' => 400,
                'fixture' => '400',
                'exit_code' => 91,
                'target' => null,
                'route' => 'webhook-1',
            ],
        ];
    }

    public function test_command_will_return_error_if_pat_not_set()
    {

        config(['asana-webhook.personal_access_token' => null]);

        $base_command = 'asana:create-webhook --resource=1 ';

        $this->artisan($base_command)->assertExitCode(90);

    }

    public function test_command_will_return_error_if_target_or_oute_not_set()
    {

        config(['asana-webhook.personal_access_token' => 'something']);

        $base_command = 'asana:create-webhook --resource=1 ';

        $this->artisan($base_command)->assertExitCode(92);

    }

    public function test_command_will_return_error_if_target_not_set()
    {

        config(['asana-webhook.personal_access_token' => 'something']);

        config(['asana-webhook.routes' => [
            'webhook' => [
                'class' => FakeInvokableClass::class,
                'name' => 'webhook-1',
                'middleware' => [FakeMiddleware::class],
            ],
        ]]);

        $this->reloadRoutes();

        $base_command = 'asana:create-webhook --resource=1 --route=webhook';

        $this->artisan($base_command)->assertExitCode(93);

    }
}
