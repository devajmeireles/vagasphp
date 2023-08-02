<?php

namespace App\Channels;

use App\Actions\Job\{GithubUrlGenerator, ResumeUrlGenerator};
use App\Models\{Job, User};
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Throwable;

class SlackChannel
{
    private readonly Job $job;

    private readonly User $user;

    public array $url = [];

    public function send($notifiable, Notification $notification): void
    {
        $this->job  = $notification->job;
        $this->user = $notification->user;

        $this->url = [
            'github' => GithubUrlGenerator::execute($this->user),
            'resume' => ResumeUrlGenerator::execute($this->user),
        ];

        try {
            Http::post($this->job->notification, [
                'type'   => 'mrkdwn',
                'text'   => __('Nova Candidatura Recebida'),
                'blocks' => [
                    [
                        'type' => 'section',
                        'text' => [
                            'type' => 'mrkdwn',
                            'text' => $this->content(),
                        ],
                    ],
                    [
                        'type'     => 'actions',
                        'elements' => $this->buttons(),
                    ],
                ],
            ]);
        } catch (Throwable $e) {
            report($e);
        }
    }

    private function content(): string
    {
        $content = <<<CONTENT
*:clipboard: Nova Candidatura Recebida*

Anúncio: *:title*
Candidato: :name
E-mail do Candidato: :email
Telefone do Candidato: :phone

CONTENT;

        if ($this->user->username !== null && $this->job->configuration->get('github') === true) {
            $content .= "\nGitHub: :github";
        }

        if ($this->user->resume !== null && $this->job->configuration->get('resume') === true) {
            $content .= "\nCurrículo: :resume";
        }

        return __($content, [
            'title'  => $this->job->title,
            'name'   => $this->user->name,
            'email'  => $this->user->email,
            'phone'  => $this->user->phone ?? ':x:',
            'github' => $this->url['github'] ?? ':x:',
            'resume' => $this->url['resume'] ?? ':x:',
        ]);
    }

    private function buttons(): array
    {
        $buttons = [];

        if ($this->user->username !== null && $this->job->configuration->get('github') === true) {
            $buttons[] = [
                'type' => 'button',
                'text' => [
                    'type' => 'plain_text',
                    'text' => __('Visualizar GitHub'),
                ],
                'url' => $this->url['github'],
            ];
        }

        if ($this->user->resume !== null && $this->job->configuration->get('resume') === true) {
            $buttons[] = [
                'type' => 'button',
                'text' => [
                    'type' => 'plain_text',
                    'text' => __('Visualizar Currículo'),
                ],
                'url' => $this->url['resume'],
            ];
        }

        return $buttons;
    }
}
