<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\ShoppingListItem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ItemAdded implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

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
            'name' => $this->item->name,
            'emoji' => $this->item->emoji,
            'category' => $this->item->category,
            'quantity' => $this->item->quantity,
            'unit' => $this->item->unit,
            'preferred_store' => $this->item->preferred_store,
            'is_bought' => false,
        ];
    }
}
