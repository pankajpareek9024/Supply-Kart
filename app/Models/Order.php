<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'assigned_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'delivered_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function deliveryBoy()
    {
        return $this->belongsTo(DeliveryBoy::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'desc');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'          => 'warning',
            'processing'       => 'info',
            'packed'           => 'primary',
            'out_for_delivery' => 'secondary',
            'delivered'        => 'success',
            'cancelled'        => 'danger',
            default            => 'secondary',
        };
    }

    public function getPaymentStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'paid'    => 'success',
            'failed'  => 'danger',
            'pending' => 'warning',
            default   => 'secondary',
        };
    }
}
