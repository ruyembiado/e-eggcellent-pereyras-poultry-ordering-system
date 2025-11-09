<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Walkin extends Model
{
    use HasFactory;
    
    protected $table = "walkins";

    protected $fillable = [
        'order_id',
        'customer_name',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
