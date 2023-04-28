<?php

namespace Giagara\AsanaWebhook\Tests\Classes;

class FakeInvokableErrorClass
{
    public function __invoke(array $payload) : void
    {

        // do something

    }

}