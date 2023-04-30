<?php

namespace Giagara\AsanaWebhook\Tests\Feature;

use Giagara\AsanaWebhook\Tests\TestCase;
use Illuminate\Support\Facades\Http;

class ListCommandTest extends TestCase
{
    /**
     * @dataProvider httpProvider
     */
    public function test_command_will_execute_correctly(int $status, int $exit_code, bool $check_table)
    {

        config(['asana-webhook.personal_access_token' => 'test_value']);
        config(['asana-webhook.workspace_id' => '123456789']);

        Http::fake([
            'https://app.asana.com/api/1.0/webhooks?workspace=123456789' => Http::response(
                json_decode(file_get_contents(__DIR__."/../fixtures/get_webhooks.json"), true),
                $status
            ),
        ]);

        $execution = $this->artisan('asana:list-webhook')->assertExitCode($exit_code);

        if($check_table)
        {
            $execution->expectsTable(
                ["webhook ID", "Resource ID", "Name", "Type", "Target"], 
                [
                [1234, 5678, "Test prj", "project", "https://asana-webhook-demo.example.com/api/webhook"],
                [7777, 6666, "Test prj 3", "project", "https://asana-webhook-demo.example.com/api/webhook3"],
            ]);
        }

    }

    public function httpProvider(): array
    {
        return [
            'ok' => [
                'status' => 200,
                'exit_code' => 0,
                'check_table' => true,
            ],
            'error' => [
                'status' => 400,
                'exit_code' => 91,
                'check_table' => false,
            ],
        ];
    }
}
