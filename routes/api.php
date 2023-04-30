<?php

use Giagara\AsanaWebhook\AsanaWebhook;

foreach (config('asana-webhook.routes') as $route => $config_route) {
    //Route::post($route, AsanaWebhookController::class)->name('asana-webhook-'.Str::snake($route));
    AsanaWebhook::createRoute($route, $config_route);
}
