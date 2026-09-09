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

class TaskDueDateSoonMail extends Mailable implements ShouldQueue
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
            subject: "Task Due : '{$this->task->title}' is due on {$this->task->due_at}",
            // from: new Address('JyaFlow@gmail.com', 'Task Management System'),
            // replyTo: [
            //     new Address('JyaFlowSupport@gmail.com', 'Task Management System Support Team'),
            // ],
            // tags: ['task-system', 'due-date-notice'],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $html = view('emails.tasks.task-due-date-soon', ['task' => $this->task])->render();
        
        $inlineHtml = (new CssToInlineStyles())->convert($html);

        return new Content(
            htmlString: $inlineHtml,
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
