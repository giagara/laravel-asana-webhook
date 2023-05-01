<?php

namespace Giagara\AsanaWebhook\Console\Commands;

use Symfony\Component\Routing\Exception\RouteNotFoundException;

class CreateWebhookCommand extends AsanaBaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asana:create-webhook {--resource=} {--target=} {--route=}';

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

        $resource = $this->getParam('resource');
        $target = $this->getParam('target', false);
        $route = $this->getParam('route', false);

        if ($target === '' && $route === '') {
            $this->error('Insert target or route name');

            return 92;
        }

        if ($target === '') {
            try {
                $target = route('asana-webhook-'.$route);
            } catch (RouteNotFoundException $th) {
                $this->error($th->getMessage());

                return 93;
            }
        }

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

    private function getParam(string $param_name, bool $mandatory = true): string
    {
        $param_value = $this->option($param_name);

        if (! $param_value && $mandatory) {
            $param_value = $this->ask("Insert {$param_name} name");
        }

        return $param_value ?? '';
    }
}
