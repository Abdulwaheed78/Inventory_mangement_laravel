<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products'; // Specify the table name if not following Laravel's plural convention.

    // You can define fillable or guarded properties
    protected $fillable = ['name','sku','category_id','price','stock_quantity','warehouse_id','status', 'description',];

    public function category(){
        return $this->belongsTo(Category::class);
    }


    public function warehouse(){
        return $this->belongsTo(Warehouse::class);
    }

    public function purtrans(){
        return $this->HasMany(PurchaseTrans::class);
    }

}
