<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

foreach (config('asana-webhook.routes') as $route => $invokable) {
    Route::post($route, AsanaWebhookController::class)->name('asana-webhook-'.Str::snake($route));
}
