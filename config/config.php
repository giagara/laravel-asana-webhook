<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Asana personal access token
    |--------------------------------------------------------------------------
    */

    'personal_access_token' => env('ASANA_PERSONAL_ACCESS_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Asana workspace ID (requried only if you want to list webhooks)
    |--------------------------------------------------------------------------
    */

    'workspace_id' => env('ASANA_WORKSPACE_ID'),

    /*
    |--------------------------------------------------------------------------
    | Webhook routes association
    |--------------------------------------------------------------------------
    |
    | this array will match the route with an invokable class
    */

    'routes' => [
        // 'route/to/webhook' => Path/To/MyInvokableClass::class
        // 'route/to/webhook' => [
        //     'class' => Path\To\MyInvokableClass::class,
        //     'name' => 'webhook-1',
        //     'middleware' => [Middleware1::class],
        // ]
    ],

];
