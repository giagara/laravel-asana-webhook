<?php

namespace Giagara\AsanaWebhook\Console\Commands;

class DeleteWebhookCommand extends AsanaBaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asana:delete-webhook {gid}';

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

        $webhook_gid = $this->argument('gid');

        $response = $this->getClient()
            ->delete('https://app.asana.com/api/1.0/webhooks/'.$webhook_gid);

        $this->info('Response with status '.$response->status());

        if ($response->failed()) {

            $this->displayErrors($response->body());

            return 91;
        }

        $this->info('Webhook list deleted correctly');

    }
}
