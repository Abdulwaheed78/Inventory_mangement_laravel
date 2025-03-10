<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['ordid', 'pmid', 'amount', 'date'];

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'ordid');
    }
    public function pmode()
    {
        return $this->hasOne(PaymentMode::class, 'id', 'pmid');
    }
}
