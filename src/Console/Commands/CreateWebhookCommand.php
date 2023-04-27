<?php

namespace Giagara\AsanaWebhook\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CreateWebhookCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:asana-webhook {--resource=} {--target=}';

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

        if(! $personal_access_token){
            $this->error("Personal access token not set. Add it in yout .env");
            return 90;
        }

        $resource = $this->getResource();
        $target = $this->getTarget();

        $response = $this->getClient()
        ->post("https://app.asana.com/api/1.0/webhooks", [
            "data" => [
                "resource" => $resource,
                "target" => $target
            ]
        ]);

        $this->info("Response with status " . $response->status());

        if($response->failed()){
            
            $this->displayErrors($response->body());

            return 91;
        }

        $this->info("Webhook created successfully");


    }

    private function getResource() : string
    {
        $resource = $this->option('resource');

        if(!$resource){
            $resource = $this->ask('Insert resource name');
        }

        return $resource;
    }

    private function getTarget() : string
    {
        $target = $this->option('target');

        if(!$target){
            $target = $this->ask('Insert target');
        }

        return $target;
    }

    private function getClient() : \Illuminate\Http\Client\PendingRequest
    {

        return Http::withToken(config('asana-webhook.personal_access_token'))
            ->timeout(5);

    }

    private function displayErrors(string $responseBody) : void
    {
        $this->error("Error while creating webhook:");

        foreach(json_decode($responseBody, true)["errors"] ?? [] as $error){

            $this->error($error["message"] ?? "Unknown message");

            $this->warn($error["help"] ?? "");

        }
    }
}
