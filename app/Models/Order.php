<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasUuids;

    protected $fillable = [
        'order_number',
        'user_id',
        'subtotal',
        'tax',
        'shipping_cost',
        'discount',
        'total',
        'currency',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_country',
        'shipping_zip_code',
        'billing_name',
        'billing_email',
        'billing_phone',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_country',
        'billing_zip_code',
        'payment_method',
        'payment_status',
        'transaction_id',
        'paid_at',
        'status',
        'notes',
        'shipped_at',
        'tracking_number',
        'tracking_url',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            'refunded' => 'secondary',
        ];

        return $statuses[$this->status] ?? 'secondary';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $statuses = [
            'pending' => 'warning',
            'paid' => 'success',
            'failed' => 'danger',
            'refunded' => 'secondary',
        ];

        return $statuses[$this->payment_status] ?? 'secondary';
    }

    public function getFormattedTotalAttribute()
    {
        return $this->currency . ' ' . number_format($this->total, 2);
    }

    public function getFormattedSubtotalAttribute()
    {
        return $this->currency . ' ' . number_format($this->subtotal, 2);
    }

    public function getFormattedTaxAttribute()
    {
        return $this->currency . ' ' . number_format($this->tax, 2);
    }

    public function getFormattedShippingCostAttribute()
    {
        return $this->currency . ' ' . number_format($this->shipping_cost, 2);
    }

    public function getFormattedDiscountAttribute()
    {
        return $this->currency . ' ' . number_format($this->discount, 2);
    }
}
