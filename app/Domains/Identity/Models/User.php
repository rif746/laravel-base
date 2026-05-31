<?php

namespace App\Domains\Identity\Models;

use App\Domains\Account\Models\Profile;
use App\Domains\Identity\Policies\UserPolicy;
use Database\Factories\Identity\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'settings'])]
#[Hidden(['password', 'remember_token'])]
#[UsePolicy(UserPolicy::class)]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * Cast attributes
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'settings' => 'collection',
    ];

    protected $with = ['roles'];

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    public function getRoleNameAttribute()
    {
        return $this->roles->first()->name ?? '-';
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
}
