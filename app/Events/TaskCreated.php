<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Task $task)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('tasks'),
        ];
    }

    // Set explicit broadcast name for Echo listeners
    public function broadcastAs(): string
    {
        return 'TaskCreated';
    }

    //Define custom payload sent to listeners.
    public function broadcastWith(): array
    {
        return [
            'id'                  => $this->task->id,
            'user_id'             => $this->task->user_id,
            'title'               => $this->task->title,
            'description'         => $this->task->description,
            'status'              => $this->task->status?->value ?? $this->task->status,
            'priority'            => $this->task->priority?->value ?? $this->task->priority,
            'collaborator_email'  => $this->task->collaborator_email,
            'due_at'              => $this->task->due_at?->toIso8601String(),
        ];
    }
}
