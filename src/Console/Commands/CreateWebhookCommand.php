<?php

namespace Giagara\AsanaWebhook\Console\Commands;



class CreateWebhookCommand extends AsanaBaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asana:create-webhook {--resource=} {--target=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a webhook in asana';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $personal_access_token = config('asana-webhook.personal_access_token');

        if (! $personal_access_token) {
            $this->error('Personal access token not set. Add it in yout .env');

            return 90;
        }

        $resource = $this->getResource();
        $target = $this->getTarget();

        $response = $this->getClient()
            ->post('https://app.asana.com/api/1.0/webhooks', [
                'data' => [
                    'resource' => $resource,
                    'target' => $target,
                ],
            ]);

        $this->info('Response with status '.$response->status());

        if ($response->failed()) {

            $this->displayErrors($response->body());

            return 91;
        }

        $this->info('Webhook created successfully');

    }

    private function getResource(): string
    {
        $resource = $this->option('resource');

        if (! $resource) {
            $resource = $this->ask('Insert resource name');
        }

        return $resource;
    }

    private function getTarget(): string
    {
        $target = $this->option('target');

        if (! $target) {
            $target = $this->ask('Insert target');
        }

        return $target;
    }

    
}
