<?php

namespace App\Models\Sale;

use App\Models\Customer\Customer;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'total_amount',
        'refunded_amount',
        'status', // pending, completed, cancelled, refunded, partial_refund
    ];

    protected $casts = [
        'total_amount'    => 'decimal:2',
        'refunded_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function sale()
    {
        return $this->hasOne(Sale::class);
    }



    protected static function booted()
    {
        static::creating(function ($order) {
            $year = Carbon::now()->format('Y');
            $shortUuid = strtoupper(substr(Str::uuid()->toString(), 0, 8));

            $order->order_number = "ORD-{$year}-{$shortUuid}";
        });
    }
}
