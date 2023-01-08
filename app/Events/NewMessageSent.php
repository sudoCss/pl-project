<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ChatMessage;

// use App\Http\Controllers\ChatMessageController;
// use App\Http\Controllers\ChatController;

class NewMessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * NewMessageSent contructor
     *
     * @param ChatMessage $chatMessage
     */
    public function __construct(private ChatMessage $chatMessage)
    {
        //
    }

    /**
     * Broadcast's event name
     *
     * @return string
     */
    public function broadcastOn() : string
    {
        return new PrivateChannel('chat.'.$this->chatMessage->chat_id);
    }

    /**
     * Data sending back to client
     *
     * @return array
     */
    public function broadcastAs()
    {
        return 'message.sent';
    }

    /**
     * Data sending back to client
     *
     * @return array
     */
    public function broadcastWith() : array
    {
        return [
            'chat_id' => $this->chatMessage->chat_id,
            'message' => $this->chatMessage->toArray(),
        ];
    }
}
