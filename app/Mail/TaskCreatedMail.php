<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class TaskCreatedMail extends Mailable  implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Task $task;

    /**
     * Create a new message instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Task Created : '{$this->task->title}'",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // Ensure user relation is loaded for the view
        $this->task->loadMissing('user');

        // Render the blade view with the task data
        $html = view('emails.tasks.task-created', ['task' => $this->task])->render();
        
        // Inline the CSS using the bundled CssToInlineStyles class
        $inlinedHtml = (new CssToInlineStyles())->convert($html);
        
        // Return as a raw HTML string Content object
        return new Content(
            htmlString: $inlinedHtml,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
