<?php

namespace App\Models\Sale;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class OrderItemReturn extends Model
{
    protected $fillable = [
        'order_item_id', // Order Item ID
        'processed_by', // User ID
        'quantity',
        'reason',
        'amount',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'amount'   => 'decimal:2',
    ];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
