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
        $path = Str::replace("api/", "", $request->path());

        if(! in_array($path, array_keys(config('asana-webhook.routes'))))
        {
            abort(404);
        }  

        $invokable_class = config('asana-webhook.routes')[$path];
        $invokable = new $invokable_class;

        if(! $invokable instanceof AsanaActionInterface){
            $class = get_class($invokable);

            throw new InvalidActionClassException("Action class {$class} not compatible with AsanaActionInterface");
        }

        $invokable($request->all());

        return response()->noContent();
 
    }


}