<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = ['order_number', 'user_id', 'shipping_address', 'total_amount', 'status', 'delivery_date', 'comment', 'type_of_service', 'pick_up_datetime', 'delivery_notes'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }
    
    public function walkin()
    {
        return $this->hasOne(Walkin::class);
    }
}
