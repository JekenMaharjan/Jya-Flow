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

    /**
     * Create a new message instance.
     */
    public function __construct(public Task $task)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Task Created : '{$this->task->title}'",
            // from: new Address('JyaFlow@gmail.com', 'Task Management System'),
            // replyTo: [
            //     new Address('JyaFlowSupport@gmail.com', 'Task Management System Support Team'),
            // ],
            // tags: ['task-system', 'creation-notice'],
            // metadata: [
            //     'task_id' => (string) $this->task->id,
            //     'user_id' => (string) $this->task->user_id,
            //     'priority' => $this->task->priority->value ?? (string) $this->task->priority,
            //     'status' => $this->task->status->value ?? 'pending',
            // ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
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
