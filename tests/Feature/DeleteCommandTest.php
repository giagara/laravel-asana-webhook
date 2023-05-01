<?php

namespace Giagara\AsanaWebhook\Tests\Feature;

use Giagara\AsanaWebhook\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class DeleteCommandTest extends TestCase
{
    /**
     * @dataProvider httpProvider
     */
    public function test_command_will_execute_correctly(int $status, int $exit_code)
    {

        config(['asana-webhook.personal_access_token' => 'test_value']);

        $webhook_gid = '1234';

        Http::fake([
            'https://app.asana.com/api/1.0/webhooks/'.$webhook_gid => Http::response(
                json_decode(file_get_contents(__DIR__.'/../fixtures/delete.json'), true),
                $status
            ),
        ]);

        $this->artisan('asana:delete-webhook '.$webhook_gid)->assertExitCode($exit_code);

    }

    public function httpProvider(): array
    {
        return [
            'ok' => [
                'status' => 200,
                'exit_code' => 0,
            ],
            'error' => [
                'status' => 400,
                'exit_code' => 91,
            ],
        ];
    }
}
