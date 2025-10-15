<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Article;

class NewArticleEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Article $article)
    {
        //
    }
    /**
     * Create a new event instance.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('test'),
        ];
    }
    public function broadcastWith(): array
    {
        return [
            'article'=>$this->article,
        ];
    }
}