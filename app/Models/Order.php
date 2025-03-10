<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'total_amount', 'order_date', 'stage_id', 'status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class);
    }

    public function trans()
    {
        return $this->hasMany(OrderTrans::class, 'order_id', 'id');
    }
}
