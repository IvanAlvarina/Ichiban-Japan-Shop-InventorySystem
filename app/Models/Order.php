<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'total',
        'downpayment',
        'balance',
        'order_date',
        'status', // pending, completed, cancelled
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }

    protected $casts = [
        'order_date' => 'date',
    ];
}
