<?php

namespace App\Models\Product;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Receive extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'supplier_id',
        'quantity',
        'unit_price',
        'total_amount',
    ];

    protected $casts = [
        'quantity'     => 'integer',
        'unit_price'   => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
