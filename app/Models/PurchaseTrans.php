<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseTrans extends Model
{
    use HasFactory;
    protected $fillable = ['purchase_id', 'product_id', 'qty', 'price'];

    public function products()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
}
