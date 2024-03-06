<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorProductVariant extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'vendor_product_id',
        'market_price',
        'variant_qty',
        'variant_qty_type',
        'min_qty',
        'max_qty',
        'price',
    ];

    public function getOffPriceAttribute() {
        $marketPrice=!empty($this->market_price)?$this->market_price : $this->price ;

        $getDiscount=($this->price*100)/$marketPrice;
        
        $netDiscountPercent=100-number_format((float)$getDiscount, 0, '.', '');
        return !empty($netDiscountPercent)? $netDiscountPercent : 0;
    }

    public function getProduct(){
        return $this->hasOne(VendorProduct::class,'id','vendor_product_id');
      }
}
