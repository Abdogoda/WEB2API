<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'country',
        'city',
        'state',
        'zip_code',
        'status',
        'email_verified_at',
        'otp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions(): mixed
    {
        return $this->roles->map->permissions->flatten()->pluck('name')->unique();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function hasPermissionTo(string $permission): bool
    {
        return $this->permissions()->contains($permission);
    }

    public function hasRoleTo(string $role): bool
    {
        return $this->roles->contains('name', $role);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
