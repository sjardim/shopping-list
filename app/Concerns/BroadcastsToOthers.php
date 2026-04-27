<?php

declare(strict_types=1);

namespace App\Concerns;

trait BroadcastsToOthers
{
    /**
     * Broadcast an event excluding the current user's socket when a valid
     * socket ID is present. Guards against the "undefined" socket ID that
     * occurs when Echo hasn't finished its handshake yet, and silently
     * skips the dispatch when Reverb is disabled (REVERB_ENABLED=false).
     */
    protected function broadcastToOthers(mixed $event): void
    {
        if (! config('lista.reverb.enabled')) {
            return;
        }

        $socketId = request()->header('X-Socket-ID');
        $pending = broadcast($event);

        if ($socketId && $socketId !== 'undefined') {
            $pending->toOthers();
        }
    }
}
