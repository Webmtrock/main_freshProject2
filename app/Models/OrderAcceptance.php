<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAcceptance extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'is_pickup',
        'status',
    ];
    public function order() {
        return $this->hasOne(Order::class, 'id','order_id');
    }
    public function driver() {
        return $this->hasOne(Order::class, 'id','user_id');
    }
    public static function getOrderRequestByDrver($userId) {
        return static::leftJoin('orders', 'orders.id', '=', 'order_acceptances.order_id')
        ->select('orders.driver_id','order_acceptances.*')
        ->where('orders.driver_id',null)
        ->where('order_acceptances.user_id',$userId)
        ->where('order_acceptances.status','Pending')
        ->orderBy('order_acceptances.id','desc')
        ->get();
    }
    public static function getOrderRequestByDrverAndOrder($userId,$orderId) {
        return static::where('user_id',$userId)->where('order_id',$orderId)
        ->first();
    }
}
