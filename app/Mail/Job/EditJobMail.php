<?php

namespace App\Mail\Job;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\{Content, Envelope};
use Illuminate\Queue\SerializesModels;

class EditJobMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly Job $job
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: __(':app: Anúncio Editado', ['app' => config('app.name')]));
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.job.edit-job');
    }
}
