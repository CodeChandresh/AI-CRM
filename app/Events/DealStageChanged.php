<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\Deal;
use App\Models\User;

class DealStageChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The deal instance.
     *
     * @var \App\Models\Deal
     */
    public $deal;

    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\Deal  $deal
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct(Deal $deal, User $user)
    {
        $this->deal = $deal;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('deal-stage-changed');
    }

    /**
     * Get the data about the event.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'deal' => $this->deal,
            'user' => $this->user,
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'deal.stage.changed';
    }
}