<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;
    protected $fillable = ['name','deleted'];

    public function Orders()
    {
        return $this->hasMany(Order::class);
    }
}
