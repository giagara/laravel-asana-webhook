<?php

namespace Giagara\AsanaWebhook\Http\Controllers;

use App\Exceptions\InvalidActionClassException;
use Giagara\AsanaWebhook\Contracts\AsanaActionInterface;
use Illuminate\Http\Request;

class AsanaWebhookController
{

    public function __invoke(Request $request)
    {

        if(! in_array($request->path() , config('asana-webhook.routes')))  
        {
            abort(404);
        }  

        $invokable = config('asana-webhook.routes')[$request->path()];

        if(! $invokable instanceof AsanaActionInterface){
            $class = get_class($invokable);

            throw new InvalidActionClassException("Action class {$class} not compatible with AsanaActionInterface");
        }
        
        $invokable();
        
    }


}