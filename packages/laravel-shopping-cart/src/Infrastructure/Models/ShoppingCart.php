<?php

namespace Arneon\LaravelShoppingCart\Infrastructure\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    use HasFactory;
    protected $table = 'shopping_carts';
    protected $fillable = [
        'cart_code',
        'session_id',
        'items',
        'total_price',
        'status',
        'customer_id',
    ];

}

