<?php

namespace Giagara\AsanaWebhook\Tests\Classes;

class FakeMiddleware
{
    public function handle($request, $next)
    {
        return $next($request);
    }
    
}