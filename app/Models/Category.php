<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories'; // Specify the table name if not following Laravel's plural convention.

    // You can define fillable or guarded properties
    protected $fillable = ['name', 'description','deleted'];

    // Optional: Relationship with products (if you want to add this)
    public function products()
    {
        return $this->hasMany(Product::class); // Assuming a product belongs to a category
    }
}
