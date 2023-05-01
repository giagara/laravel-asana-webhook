<?php

namespace Giagara\AsanaWebhook\Tests;

use Giagara\AsanaWebhook\AsanaWebhookServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            AsanaWebhookServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }

    protected function reloadRoutes(): void
    {
        (new AsanaWebhookServiceProvider(app()))->boot();
    }
}
