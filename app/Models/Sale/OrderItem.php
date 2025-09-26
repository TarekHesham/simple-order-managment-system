<?php

namespace App\Models\Sale;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        'refunded_quantity',
        'refunded_amount'
    ];

    protected $casts = [
        'quantity'          => 'integer',
        'unit_price'        => 'decimal:2',
        'total_price'       => 'decimal:2',
        'refunded_quantity' => 'integer',
        'refunded_amount'   => 'decimal:2'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function returns()
    {
        return $this->hasMany(OrderItemReturn::class);
    }
}
