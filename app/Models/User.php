<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enum\GenderType;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'bio',
        'gender',
        'password',
        'settings',
        'photo_profile',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'settings' => 'array',
        'status' => 'boolean',
        'gender' => GenderType::class,
    ];

    protected $appends = ['role_name'];

    //
    // Attribute
    //

    public function getRoleNameAttribute()
    {
        return $this->roles->first()->name;
    }

    //
    // Relation
    //

    //
    // Scope
    //

    public function scopeSearch($query, $search)
    {
        return $query->orWhere('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
    }
}
