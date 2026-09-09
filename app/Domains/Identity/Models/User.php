<?php

namespace App\Domains\Identity\Models;

use App\Attributes\Model\Audit;
use App\Domains\Identity\Enums\UserStatus;
use App\Domains\Identity\Notifications\ResetPasswordNotification;
use App\Domains\Identity\Notifications\VerifyEmailNotification;
use App\Domains\Identity\Policies\UserPolicy;
use App\Domains\System\Traits\Model\HasFile;
use Database\Factories\Identity\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'status', 'settings'])]
#[Hidden(['password', 'remember_token', 'settings'])]
#[UsePolicy(UserPolicy::class)]
#[UseFactory(UserFactory::class)]
#[Audit(
    label: 'user',
    only: ['email', 'name', 'status'],
    events: ['created', 'updated', 'deleted']
)]
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasFile;
    use HasRoles;
    use HasUlids;
    use Notifiable;

    /**
     * Cast attributes
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'settings' => 'collection',
        'status' => UserStatus::class,
    ];

    /**
     * Attributes for default value.
     */
    protected $attributes = [
        'password' => 'password'
    ];

    public function sendPasswordResetNotification($token): void
    {
        // This overrides the default CanResetPassword trait method
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification);
    }

    public function uniqueIds(): array
    {
        return ['ulid'];
    }

    public function getRoleNameAttribute()
    {
        return $this->roles->first()->name ?? '-';
    }

    public function avatar(): MorphOne
    {
        return $this->hasSingleFile('avatar');
    }

    public function userActivity(): HasMany
    {
        return $this->hasMany(UserActivity::class);
    }
}
