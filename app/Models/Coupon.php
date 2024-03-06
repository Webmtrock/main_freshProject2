<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'coupon_code',
        'coupon_details',
        'valid_from',
        'valid_to',
        'discount_type',
        'amount',
        'max_user',
        'remainig_user',
        'max_reedem',
        'max_discount',
        'min_order_value',
        'status'
    ];

    public function vendor() {
        return $this->hasOne(User::class, 'id', 'vendor_id');
    }

    public static function getCoupons() {
        return static::where('status', 1)->get();
    }
    public static function getCouponsByCode($couponCode) {
        return static::where('coupon_code',$couponCode)->where('status', 1)->first();
    }
    public static function getCouponsByVendor($vendorId) {

        $todayDate = date('Y-m-d');
        return static::where('status', 1)->where('valid_from','<=', $todayDate)->where('valid_to','>=', $todayDate)->whereRaw('FIND_IN_SET(?,vendor_id)', $vendorId)->get();
    }
    public static function getCouponByVendor($couponCode,$vendorId) {

        $todayDate = date('Y-m-d');
        return static::where('status', 1)->where('coupon_code',$couponCode)->whereRaw('FIND_IN_SET(?,vendor_id)', $vendorId)->first();
    }
}
