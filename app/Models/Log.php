<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $fillable = ['type', 'table', 'by', 'rid'];
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'by');
    }
}
