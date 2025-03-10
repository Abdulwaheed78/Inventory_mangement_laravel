<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = ['supplier_id', 'total_amount', 'purchase_date', 'status'];

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function trans(){
        return $this->hasMany(PurchaseTrans::class,'purchase_id','id');
    }

}
