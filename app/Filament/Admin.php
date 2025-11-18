<?php

namespace App\Filament;

use Database\Factories\AdminFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable implements FilamentUser, HasName
{

    use  HasApiTokens, HasFactory, Notifiable;

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    protected $guard_name = 'admin';

    protected $fillable = [
        'email',
        'password'
    ];

    public function getFilamentName(): string
    {
        return 'Admin';
    }
    protected static function newFactory(): AdminFactory
    {
        return AdminFactory::new();
    }
}
