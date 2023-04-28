<?php

namespace Giagara\AsanaWebhook\Tests\Classes;

use Giagara\AsanaWebhook\Contracts\AsanaActionInterface;

class FakeInvokableClass implements AsanaActionInterface
{
    public function __invoke(array $payload): void
    {

        // do something

    }
}
