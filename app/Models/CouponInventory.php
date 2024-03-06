<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'coupon_code',
        'discount_type',
        'total_price',
        'discounted_price',
    ];

    public function users() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function couponDetail() {
        return $this->hasOne(Coupon::class, 'coupon_code', 'coupon_code');
    }

    public static function getCouponInventoryByUser($userID,$couponCode) {
        return static::where('user_id',$userID)->where('coupon_code',$couponCode)->get();
    }
}
