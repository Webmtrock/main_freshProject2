<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'tax_percent',
        'status'
    ];

    public static function getAllActiveTaxes() {
        return static::where('status', '=', 1)->orderBy('title', 'ASC')->get();
    }

    public static function getTaxById($id) {
        return static::where('id', $id)->first();
    }
}
