<?php

namespace App\Models\Customer;

use App\Models\Product\Product;
use App\Models\Sale\Order;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'address',
    ];

    // Relations
    public function statistic()
    {
        return $this->hasOne(CustomerStatistic::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
