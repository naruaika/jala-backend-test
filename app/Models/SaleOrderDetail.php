<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleOrderDetail extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sale_order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    /**
     * The sale order that belong to the sale order detail.
     */
    public function saleOrder()
    {
        return $this->hasOne(SaleOrder::class);
    }

    /**
     * The product that belong to the sale order.
     */
    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
