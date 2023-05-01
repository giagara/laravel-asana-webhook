<?php

namespace Giagara\AsanaWebhook\Console\Commands;

class ListWebhookCommand extends AsanaBaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asana:list-webhook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List asana webhooks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (! $this->checkTokenInConfig()) {
            return 90;
        }

        $response = $this->getClient()
            ->get('https://app.asana.com/api/1.0/webhooks', [
                'workspace' => config('asana-webhook.workspace_id'),
            ]);

        $this->info('Response with status '.$response->status());

        if ($response->failed()) {

            $this->displayErrors($response->body());

            return 91;
        }

        $this->info('Webhook list');

        $webhooks = collect($response->json()['data'] ?? []);

        $webhooks = $webhooks
            ->filter(fn ($item) => $item['active'])
            ->map(function ($item) {
                return [
                    'webhook_id' => $item['gid'] ?? '--',
                    'resource_id' => $item['resource']['gid'] ?? '--',
                    'name' => $item['resource']['name'] ?? '--',
                    'type' => $item['resource']['resource_type'] ?? '--',
                    'target' => $item['target'] ?? '--',
                ];
            });

        $this->table(
            ['webhook ID', 'Resource ID', 'Name', 'Type', 'Target'],
            $webhooks->toArray()
        );

    }
}
