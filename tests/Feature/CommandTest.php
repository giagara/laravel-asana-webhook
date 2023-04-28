<?php

namespace Giagara\AsanaWebhook\Tests\Feature;

use Giagara\AsanaWebhook\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class CommandTest extends TestCase
{
    /**
     * @dataProvider httpProvider
     */
    public function test_command_will_execute_correctyl(int $status, string $fixture, int $exit_code)
    {

        config(['asana-webhook.personal_access_token' => 'test_value']);

        Http::fake([
            'https://app.asana.com/api/1.0/webhooks' => Http::response(
                json_decode(file_get_contents(__DIR__."/../fixtures/{$fixture}.json"), true),
                $status
            ),
        ]);

        $this->artisan('make:asana-webhook --resource=1 --target=2')->assertExitCode($exit_code);

    }

    public function httpProvider(): array
    {
        return [
            'ok' => [
                'status' => 200,
                'fixture' => '200',
                'exit_code' => 0,
            ],
            'error' => [
                'status' => 400,
                'fixture' => '400',
                'exit_code' => 91,
            ],
        ];
    }
}
