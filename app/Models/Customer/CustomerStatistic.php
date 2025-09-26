<?php

namespace App\Models\Customer;

use Illuminate\Database\Eloquent\Model;

class CustomerStatistic extends Model
{
    protected $fillable = [
        'customer_id',
        'total_amount_paid',     // Total amount paid (Money Spent)
        'total_amount_refunded', // Total amount refunded (Money Refunded)
        'total_orders',
        'total_refunded_orders', // Total orders refunded 
        'total_items',
        'first_order_date',
        'last_order_date',
    ];

    protected $casts = [
        'first_order_date' => 'datetime',
        'last_order_date'  => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
