<?php

namespace Giagara\AsanaWebhook\Http\Middleware;

class AsanaWebhookMiddleware
{
    public function handle($request, $next)
    {
        /**
         * Handshake management
         */
        if ($request->hasHeader('X-Hook-Secret')) {
            return response()
                ->noContent()
                ->header('X-Hook-Secret', $request->header('X-Hook-Secret'));
        }

        /**
         * Security management
         */
        if (! $request->hasHeader('x-asana-request-signature')) {
            abort(403);
        }

        return $next($request);
    }
}
