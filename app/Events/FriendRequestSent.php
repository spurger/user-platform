<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FriendRequestSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sender;
    public $recipient;

    /**
     * Create a new event instance.
     */
    public function __construct(User $sender, User $recipient)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [];
    }
}
