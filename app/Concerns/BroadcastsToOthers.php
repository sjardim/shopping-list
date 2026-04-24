<?php

declare(strict_types=1);

namespace App\Concerns;

trait BroadcastsToOthers
{
    /**
     * Broadcast an event excluding the current user's socket when a valid
     * socket ID is present. Guards against the "undefined" socket ID that
     * occurs when Echo hasn't finished its handshake yet.
     */
    protected function broadcastToOthers(mixed $event): void
    {
        $socketId = request()->header('X-Socket-ID');
        $pending = broadcast($event);

        if ($socketId && $socketId !== 'undefined') {
            $pending->toOthers();
        }
    }
}
