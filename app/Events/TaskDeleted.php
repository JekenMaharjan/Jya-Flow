<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public array $taskData,
        public string $userEmail
    )
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

    //Define custom payload sent to listeners.
    public function broadcastWith(): array
    {
        return [
            'id'                  => $this->taskData['id'],
            'user_id'             => $this->taskData['user_id'],
            'title'               => $this->taskData['title'],
            'description'         => $this->taskData['description'],
            'status'              => $this->taskData['status']?->value ?? $this->taskData['status'],
            'priority'            => $this->taskData['priority']?->value ?? $this->taskData['priority'],
            'collaborator_email'  => $this->taskData['collaborator_email'],
            'due_at'              => $this->taskData['due_at'],

            'user_email'          => $this->userEmail,
        ];
    }
}
