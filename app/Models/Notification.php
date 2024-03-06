<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'body',
        'notification_type',
        'seen'
    ];
    public static function getNotificationByuser($userID) {
        return static::where('user_id',$userID)->Orderby('id','desc')->get();
    }
    public static function getNotificationById($id) {
        return static::where('id',$id)->first();
    }
}
