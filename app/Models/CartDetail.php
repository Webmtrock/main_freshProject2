<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'vendor_product_variant_id',
        'qty',
        'user_id',
        'vendor_product_id',
    ];

    public static function getVarint($userID,$variantID) {
        return static::where('user_id',$userID)->where('vendor_product_variant_id',$variantID)->first();
    }

    public static function getCartByUserAndID($userID,$id) {
        return static::where('user_id',$userID)->where('id',$id)->first();
    }

    public function getProductData(){
        return $this->hasOne(VendorProduct::class,'id','vendor_product_id');
    }

    public function getVariantData(){
        return $this->hasOne(VendorProductVariant::class,'id','vendor_product_variant_id');
    }

    // public static function removeCartItemById($id) {
    //     return static::where('id',$id)->delete();
    // }
}
