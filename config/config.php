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
    | Webhook routes association
    |--------------------------------------------------------------------------
    |
    | this array will match the route with an invokable class
    */

    'routes' => [
        // 'route/to/webhook' => Path/To/MyInvokableClass::class
    ],

];
