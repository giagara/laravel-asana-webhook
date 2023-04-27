<?php

namespace Giagara\AsanaWebhook;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Giagara\AsanaWebhook\Skeleton\SkeletonClass
 */
class AsanaWebhookFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'asana-webhook';
    }
}
