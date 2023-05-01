<?php

namespace Giagara\AsanaWebhook\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

abstract class AsanaBaseCommand extends Command
{
    protected function checkTokenInConfig(): bool
    {
        $personal_access_token = config('asana-webhook.personal_access_token');

        if (! $personal_access_token) {
            $this->error('Personal access token not set. Add it in yout .env');

            return false;
        }

        return true;
    }

    protected function getClient(): \Illuminate\Http\Client\PendingRequest
    {

        return Http::withToken(config('asana-webhook.personal_access_token'))
            ->withoutVerifying()
            ->timeout(5);

    }

    protected function displayErrors(string $responseBody): void
    {
        $this->error('Error while executing request:');

        foreach (json_decode($responseBody, true)['errors'] ?? [] as $error) {

            $this->error($error['message'] ?? 'Unknown message');

            $this->warn($error['help'] ?? '');

        }
    }
}
