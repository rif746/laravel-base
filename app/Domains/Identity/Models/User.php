<?php

namespace App\Domains\Identity\Models;

use App\Domains\Account\Models\Profile;
use App\Domains\Identity\Enums\UserStatus;
use App\Domains\Identity\Notifications\VerifyEmailNotification;
use App\Domains\Identity\Policies\UserPolicy;
use App\Domains\System\Traits\Model\HasFile;
use Database\Factories\Identity\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'status', 'settings'])]
#[Hidden(['password', 'remember_token'])]
#[UsePolicy(UserPolicy::class)]
class User extends Authenticatable implements Auditable, MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasFile;
    use HasRoles;
    use HasUlids;
    use Notifiable;
    use \OwenIt\Auditing\Auditable;

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
     * Attributes to include in the Audit.
     */
    protected array $auditInclude = [
        'name',
        'email',
        'status'
    ];

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
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

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
}
