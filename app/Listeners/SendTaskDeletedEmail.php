<?php

namespace App\Listeners;

use App\Events\TaskDeleted;
use App\Mail\TaskDeletedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendTaskDeletedEmail
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
    public function handle(TaskDeleted $event): void
    {
        if ($event->userEmail) {
            Mail::to($event->userEmail)->queue(new TaskDeletedMail($event->taskData));
        }
    }
}
