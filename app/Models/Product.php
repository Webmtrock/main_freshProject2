<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'SKU',
        'name',
        'qty',
        'qty_type',
        'min_qty',
        'max_qty',
        'market_price',
        'regular_price',
        'content',
        'tax_id',
        'tax_id_2',
        'image',
        'status',
    ];

    public function Category() {
        return $this->hasOne(Category::class, 'id','category_id');
    }

    public static function getProductsByCategoryId($category_id = null) {
        return static::where('category_id', $category_id)->get();
    }

    public function tax() {
        return $this->hasOne(Tax::class, 'id', 'tax_id')->where('status', '=', 1);
    }

    public function tax2() {
        return $this->hasOne(Tax::class, 'id', 'tax_id_2')->where('status', '=', 1);
    }

    public static function getProductDetailsByID($id = null) {
        return static::where('id', $id)->with(['Category.tax','tax','tax2'])->first();
    }

    public static function getProductDetailsByName($name = null) {
        return static::where('name', 'like', '%'.$name.'%')->with(['Category.tax','tax'])->first();
    }

    public static function getAllActiveProduct($keyword = null) {
        $data = static::where(function ($query_new) use ($keyword) {
                        $query_new->where('name', 'like', '%'.$keyword.'%')
                        ->where('status',1);
        })->get();
        return $data;
    }

    public static function getActiveProductDetailsByID($id = null) {
        // return static::where('id', $id)->with(['Category.tax','tax'])->first();
        $data = static::where(function ($query_new) use ($id) {
                        $query_new->where('id', $id)
                        ->where('status',1);
        })->first();
        return $data;
    }
   
}
