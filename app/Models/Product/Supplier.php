<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone_number',
    ];

    public function receives()
    {
        return $this->hasMany(Receive::class);
    }
}
