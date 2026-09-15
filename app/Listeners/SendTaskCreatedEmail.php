<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Mail\TaskCreatedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendTaskCreatedEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskCreated $event): void
    {
        if ($event->task->user) {
            Mail::to($event->task->user)->send(new TaskCreatedMail($event->task));
        }
    }
}
