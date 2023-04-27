<?php

namespace Giagara\AsanaWebhook\Contracts;

interface AsanaActionInterface
{

    public function __invoke(array $payload) : void;
    
}