<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address_id',
        'coupon_code',
        'item_total',
        'delivery_charges',
        'tip_amount',
        'grand_total',
    ];


    public static function getUserCart($userId) {
        return CartDetail::where('user_id',$userId)->get();
    }

    public static function userTempCartData($userId) {
        return Cart::where('user_id',$userId)->first();
    }

    public function cartDetails(){
        return $this->hasOne(CartDetail::class,'cart_id','id');
    }

    public function deliveryAddress(){
        return $this->hasOne(UserAddress::class,'id','address_id');
    }
}
