<?php

namespace App\Models\Product;

use App\Models\Sale\OrderItem;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'is_active',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function receives()
    {
        return $this->hasMany(Receive::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'receives', 'product_id', 'supplier_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
