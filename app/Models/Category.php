<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'tax_id',
        'image',
        'status',
        'admin_commission_type',
        'admin_commission',
    ];

    public function Products() {
        return $this->hasMany(Products::class, 'category_id');
    }

    public static function getAllActiveCategoriesNameId() {
        return static::where('status', '=', 1)->pluck('name', 'id');
    }

    public static function getTaxIdByCategoryId($id) {
        return static::where('id', $id)->with(['tax'])->first('tax_id');
    }

    public function tax() {
        return $this->hasOne(Tax::class, 'id', 'tax_id')->where('status', '=', 1);
    }
    public static function lastestCategory() {

        return static::orderby('id','desc')->take(12)->get();
    }
}
