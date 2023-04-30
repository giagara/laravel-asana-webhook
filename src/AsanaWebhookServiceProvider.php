<?php

namespace Giagara\AsanaWebhook;

use Giagara\AsanaWebhook\Console\Commands\CreateWebhookCommand;
use Giagara\AsanaWebhook\Console\Commands\ListWebhookCommand;
use Giagara\AsanaWebhook\Http\Middleware\AsanaWebhookMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AsanaWebhookServiceProvider extends ServiceProvider
{
    public static function basePath(string $path): string
    {
        return __DIR__.'/..'.$path;
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {

        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('asana-webhook.php'),
            ], 'asana-webhook-config');

            $this->commands([
                CreateWebhookCommand::class,
                ListWebhookCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'asana-webhook');

        // Register the main class to use with the facade
        $this->app->singleton('asana-webhook', function () {
            return new AsanaWebhook;
        });
    }

    /**
     * Register the Log Viewer routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'prefix' => '/api',
            'namespace' => 'Giagara\AsanaWebhook\Http\Controllers',
            'middleware' => AsanaWebhookMiddleware::class,
        ], function () {
            $this->loadRoutesFrom(self::basePath('/routes/api.php'));
        });
    }
}
