<?php

namespace App\Notifications;

use App\Actions\Job\GithubUrlGenerator;
use App\Models\{Job, User};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CandidacyJobNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Job $job,
        public readonly User $user
    ) {
    }

    public function via($notifiable): array
    {
        return array_keys($notifiable->routes);
    }

    public function toMail(): MailMessage
    {
        $mail = new MailMessage();

        $mail->subject(__('Nova Candidatura Recebida'))
            ->greeting(__('Olá, :name!', ['name' => $this->job->user->name]))
            ->line(__('Anúncio: :title', ['title' => $this->job->title]))
            ->line(__('Candidato: :name', ['name' => $this->user->name]))
            ->line(__('E-mail do Candidato: :email', ['email' => $this->user->email]))
            ->line(__('Telefone do Candidato: :phone', ['phone' => $this->user->phone ?? '-/-']))
            ->salutation('');

        if ($this->user->username !== null && $this->job->configuration->get('github') === true) {
            $mail->action(__('Visualizar GitHub'), GithubUrlGenerator::execute($this->user));
        }

        if ($this->user->resume !== null && $this->job->configuration->get('resume') === true) {
            $mail->attach(public_path('storage/resumes/' . $this->user->resume), [
                'as'   => sprintf('curriculo_%s.pdf', $this->user->name),
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}
