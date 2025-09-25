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

    public function user()
    {
        return $this->hasOneThrough(
            User::class, // The model you want to access
            Order::class, // The intermediate model
            'id',        // Foreign key on the intermediate table (firstKey)
            'id',        // Foreign key on the final table (secondKey)
            'order_id',  // Local key on the current model (localKey)
            'user_id'    // Local key on the intermediate model (secondLocalKey )
        );
    }
}
