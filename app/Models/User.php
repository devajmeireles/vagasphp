<?php

namespace App\Models;

use App\Enums\SocialiteProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Builder, Casts\Attribute, Collection, Relations\HasMany};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_admin'          => 'boolean',
        'email_verified_at' => 'datetime',
        'provider'          => SocialiteProvider::class,
    ];

    protected function avatar(): Attribute
    {
        return Attribute::make(get: fn ($value) => $value ?? sprintf('https://ui-avatars.com/api/?name=%s&color=474a8a&background=474a8a&color=FFF', $this->name));
    }

    public function job(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function candidacy(): HasMany
    {
        return $this->hasMany(Candidacy::class);
    }

    public function login(): string
    {
        return __('app.user.login', ['provider' => $this->provider->name]);
    }
}
