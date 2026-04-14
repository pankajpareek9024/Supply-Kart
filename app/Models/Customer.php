<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     */
    protected $table = 'customers';

    /**
     * The guard used for authentication.
     */
    protected $guard = 'customer';

    /**
     * Allow mass assignment for all fields.
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'otp',
        'otp_expire_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'otp_expire_at' => 'datetime',
        'is_active'     => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
