<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouses'; // Specify the table name if not following Laravel's plural convention.

    // You can define fillable or guarded properties
    protected $fillable = ['name', 'location'];

    public function products(){
        return $this->hasMany('products');
    }
}
