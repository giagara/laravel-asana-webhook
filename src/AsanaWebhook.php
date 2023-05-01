<?php

namespace Giagara\AsanaWebhook;

use Giagara\AsanaWebhook\Http\Controllers\AsanaWebhookController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class AsanaWebhook
{
    public static function createRoute(string $uri, string|array $config_data): \Illuminate\Routing\Route
    {

        $route_config = $config_data;

        if (! is_array($config_data)) {
            $route_config = [];
            $route_config['class'] = $config_data;
            $route_config['name'] = Str::slug($uri);
            $route_config['middleware'] = [];
        }

        return Route::post($uri, AsanaWebhookController::class)
            ->middleware($route_config['middleware'])
            ->name('asana-webhook-'.$route_config['name']);

    }
}
