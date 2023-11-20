<?php

namespace Giagara\AsanaWebhook\Http\Controllers;

use App\Exceptions\InvalidActionClassException;
use Giagara\AsanaWebhook\Contracts\AsanaActionInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AsanaWebhookController
{
    public function __invoke(Request $request)
    {
        $path = Str::replace('api/', '', $request->path());

        $invokable_config = config('asana-webhook.routes')[$path];
        $invokable = is_array($invokable_config)
            ? new $invokable_config['class']
            : new $invokable_config;

        if (! $invokable instanceof AsanaActionInterface) {
            $class = get_class($invokable);

            throw new InvalidActionClassException("Action class {$class} not compatible with AsanaActionInterface");
        }

        $invokable($request->all());

        return response()->noContent();

    }
}
