<?php

namespace App\Http\Controllers;

use App\Enums\SocialiteProvider;
use App\Mail\User\UserWelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\{Auth, DB, Hash, Mail};
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Throwable;

class SocialiteController extends Controller
{
    public function redirect(SocialiteProvider $provider)
    {
        return $provider->driver()->redirect();
    }

    public function callback(SocialiteProvider $provider)
    {
        $redirect = redirect(route('index'));

        try {
            /** @var \Laravel\Socialite\Two\User $callback */
            $socialite = Socialite::driver($provider->value)->user();
        } catch (InvalidStateException $e) {
            report($e);

            return $redirect;
        }

        try {
            /** @var User|null $user */
            $user = DB::transaction(function () use ($socialite, $provider) {
                return User::query()
                    ->updateOrCreate([
                        'email' => $socialite->email,
                    ], [
                        'provider'          => $provider,
                        'username'          => $socialite->nickname,
                        'name'              => $socialite->name,
                        'email_verified_at' => now(),
                        'password'          => Hash::make(Str::random()),
                        'avatar'            => $socialite->avatar,
                    ]);
            });

            Auth::login($user);

            if ($user->wasRecentlyCreated === true) {
                Mail::to($user)->send(new UserWelcomeMail($user));

                return $redirect->with('onboarding', true);
            }

            return $redirect;
        } catch (Throwable $e) {
            report($e);
        }

        abort(500, __("Erro interno. Tente novamente!"));
    }
}
