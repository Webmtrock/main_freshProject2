<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAddress extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'location',
        'flat_no',
        'street',
        'landmark',
        'address_type',
    ];
    public static function getAddressesByUser($userID) {
        return static::where('user_id', $userID)->get();
    }

    public static function getAddressesByUserAndID($userID,$id) {
        return static::where('user_id', $userID)->where('id', $id)->first();
    }
}
