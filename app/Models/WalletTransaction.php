<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vendor_id',
        'payment_id',
        'razorpay_signature',
        'previous_balance',
        'current_balance',
        'amount',
        'order_id',
        'remark',
        'status',
        'user_type',
    ];

    public static function getTransactionsByUser($userID, $userType, $pagination) {
         $data = static::where('user_id', $userID);
         if(!empty($userType)){
           $data->where('user_type', $userType);
         }
         return $data->orderBy('created_at', 'DESC')->paginate($pagination);
    }
}
