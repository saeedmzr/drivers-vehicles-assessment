<?php

namespace Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Domain\User\Entities\UserEntity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable,HasUuids;
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
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
    public function toEntity(): UserEntity
    {
        return new UserEntity(
            id: $this->id,
            name: $this->name,
            email: $this->email,
            emailVerifiedAt: $this->email_verified_at,
            createdAt: $this->created_at,
            updatedAt: $this->updated_at,
            deletedAt: $this->deleted_at
        );
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

}
