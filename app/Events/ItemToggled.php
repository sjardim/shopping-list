<?php

namespace App\Events;

use App\Models\ShoppingListItem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ItemToggled implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly ShoppingListItem $item) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("shopping.{$this->item->list->share_token}"),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'item_id' => $this->item->id,
            'is_bought' => $this->item->is_bought,
        ];
    }
}
