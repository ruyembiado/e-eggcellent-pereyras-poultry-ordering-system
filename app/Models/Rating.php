<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $table = "ratings";

    protected $fillable = [
        'order_id',
        'service_speed',
        'egg_quality',
        'egg_size_accuracy',
        'comment',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
