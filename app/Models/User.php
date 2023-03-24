<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use HasPermissions;

    protected $guard_name =  'api';

    public const ROLES = [
        'lead' => 'lead',
        'seller' => 'seller',
        'admin' => 'admin',
    ];

    public const PERMISSIONS = [
        'leads.index' => 'leads.index',
        'leads.show' => 'leads.show',
        'leads.store' => 'leads.store',
        'leads.update' => 'leads.update',
        'leads.delete' => 'leads.delete',
        'sellers.index' => 'sellers.index',
        'sellers.show' => 'sellers.show',
        'sellers.store' => 'sellers.store',
        'sellers.update' => 'sellers.update',
        'sellers.delete' => 'sellers.delete',
        'quotes.index' => 'quotes.index',
        'quotes.show' => 'quotes.show',
        'quotes.store' => 'quotes.store',
        'quotes.update' => 'quotes.update',
        'quotes.delete' => 'quotes.delete',
        'services.index' => 'services.index',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'dni',
        'email',
        'password',
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
    ];

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
