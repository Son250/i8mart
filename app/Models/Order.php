<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['product_quantity', 'total_amount', 'order_date', 'payment_method', 'shipping_address', 'status', 'customer_id'];
    public function orderItems()
    {
        return $this->hasMany('App\Models\OrderItem');
    }
  
}
