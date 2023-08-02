<?php

namespace App\Mail\User;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly User $user
    ) {
    }

    public function build(): self
    {
        return $this->subject(__('Bem-vindo(a) ao :app, :name!', [
            'app'  => config('app.name'),
            'name' => $this->user->name,
        ]))->markdown('mail.user.user-welcome', [
            'user' => $this->user,
        ]);
    }
}
