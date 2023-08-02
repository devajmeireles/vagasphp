<?php

namespace App\Mail;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly Job $job
    ) {
    }

    public function build(): self
    {
        //TODO: fazer com que parametros manipulem o tipo do e-mail

        return $this->subject(__('Anúncio :case', ['case' => $this->job->wasCreatedRecently() ? 'Criado' : 'Atualizado']))
            ->markdown('mail.job-mail', [
                'job' => $this->job,
            ]);
    }
}
