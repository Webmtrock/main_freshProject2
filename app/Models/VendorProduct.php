<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorProduct extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'category_id',
        'product_id',
        'SKU',
        'name',
        'image',
        'status',
    ];

    public function vendor() {
        return $this->hasOne(User::class, 'id', 'vendor_id');
    }

    public function vendorDetail() {
        return $this->hasOne(VendorProfile::class, 'id', 'vendor_id');
    }

    public function category() {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function product() {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    // public function getProductByCategoryId() {
    //     return $this->hasOne(Product::class, 'category_id', 'category_id');
    // }
     

    public function variants() {
        return $this->hasMany(VendorProductVariant::class, 'vendor_product_id', 'id');
    }
    
    public function singleVariants() {
        return $this->hasOne(VendorProductVariant::class, 'vendor_product_id', 'id');
    }
   
    public static function getBestFreshProducts() {

        return static::where('category_id', 5)->where('status',true)->take(10)->orderBy('id','desc')->get();

    }
   
    public static function getLatestProductsByVendor($vendorID, $limit, $keyword=null) {

        // return static::where('vendor_id',$vendorID)->where('status',true)->paginate($limit);
        return static::join('products', 'products.id','vendor_products.product_id')
        ->select('vendor_products.*', 'products.name')
        ->where(function($query) use ($vendorID, $limit, $keyword) {$query->where('vendor_products.vendor_id',$vendorID)->where('products.name', 'like', '%'.$keyword.'%')->where('vendor_products.status',true);})
        ->paginate($limit);
        // ->where('vendor_id',$vendorID)->where('status',true)->paginate($limit);

    }
    public static function getProductbyID($productId) {

        return static::where('id',$productId)->where('status',true)->first();

    }
    public static function latestProducts() {

        return static::where('status',true)->orderBy('id','desc')->get();

    }
    public static function getProductByVendorID($vendorId) {
        return static::join('users', 'vendor_products.vendor_id', '=', 'users.id')->select('vendor_products.*', 'users.as_vendor_verified')->where('users.as_vendor_verified', true)->where('vendor_id',$vendorId)->where('vendor_products.status',true)->get();
    }
    public static function getProductbyCategory($CategoryID) {
        // return static::where('category_id',$CategoryID)->where('status',true)->get();
        return static::join('users', 'vendor_products.vendor_id', '=', 'users.id')->select('vendor_products.*', 'users.as_vendor_verified')->where('users.as_vendor_verified', true)->where('category_id',$CategoryID)->where('vendor_products.status',true)->get();

    }
    public static function getProductVariantById($variantId) {

        return VendorProductVariant::where('id',$variantId)->first();
        
    }
    public static function getProductVariantByIdAndProduct($variantId,$productId) {

        return VendorProductVariant::where('id',$variantId)->where('vendor_product_id',$productId)->first();
        
    }

    public static function getVariantByProductAndCategoryId($vendorID, $productId, $CategoryID) {
        return static::where('vendor_id', $vendorID)->where('category_id', $CategoryID)->where('product_id', $productId)->with('variants')->first();
    }

    public static function getAllProductsByVendorId($vendorId, $keyword = null) {
        // return static::where('vendor_id', $vendorId)->get();
        $data = static::join('products', 'vendor_products.product_id', '=', 'products.id')
                        ->select('vendor_products.*', 'products.name as product_name')
                        ->where('vendor_id', $vendorId);
        
        if($keyword) {
            $data->where('products.name', 'like', '%'.$keyword.'%');
        }
        return $data->get();
    }

    public static function getAllVendorsIdByProductName($keyword = null) {
        return static::join('products', 'vendor_products.product_id', '=', 'products.id')->where('name', 'like', '%'.$keyword.'%')->distinct()->pluck('vendor_id');
        // return static::join('products', 'vendor_products.product_id', '=', 'products.id')->select('vendor_products.vendor_id', 'products.name')->get();
    }

    public static function getActiveVendorProductDetailsByID($id) {
        $data = static::where(function ($query_new) use ($id) {
                        $query_new->where('id', $id)
                        ->where('status',1);
        })->first();
        return $data;
    }
    
}
