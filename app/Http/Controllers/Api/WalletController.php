<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Helper\ResponseBuilder;
use App\Models\WalletTransaction;
use App\Http\Resources\Admin\WalletTransactionCollection;
use Auth;
class WalletController extends Controller
{
    /**
    * My Wallet function
    *
    * @return \Illuminate\Http\Response
    */
    public function myWallet(Request $request){
        try {
            $user = Auth::guard('api')->user(); 
            $pagination = isset($request->pagination) ? $request->pagination : 10;
            $walletTransactions = WalletTransaction::getTransactionsByUser($user->id, $request->user_type, $pagination);
            $data['totalAmount'] = ($user->wallet_balance ?? 0) + ($user->earned_balance ?? 0);
            $data['withdrawalbleAmount'] = $user->earned_balance ?? 0;
            $data['unutilisedAmount'] = $user->wallet_balance ?? 0;
            $data['walletTransactions']  = new WalletTransactionCollection($walletTransactions);

            return ResponseBuilder::successWithPagination($walletTransactions, $data, trans('global.my_wallet'), $this->success);
        } catch (\Exception $e) {
            return ResponseBuilder::error(trans('global.SOMETHING_WENT'), $this->badRequest);
        }
    }

    /**
    * Add Money function
    *
    * @return \Illuminate\Http\Response
    */
    public function addMoney(Request $request){
        try {
            $user = Auth::guard('api')->user();

            // Validation start
            $validSet = [
                'payment_id' => 'required',
                'razorpay_signature' => 'required',
                'amount' => 'required',
                'response' => 'required | in:success,error'
            ]; 

            $isInValid = $this->isValidPayload($request, $validSet);
            if($isInValid){
                return ResponseBuilder::error($isInValid, $this->badRequest);
            }
            // Validation end

            $data = WalletTransaction::create([
                'user_id' => $user->id,
                'payment_id' => $request->payment_id,
                'razorpay_signature' => $request->razorpay_signature,
                'previous_balance' => $user->wallet_balance,
                'current_balance' => $request->response == 'success' ? $user->wallet_balance + $request->amount : $user->wallet_balance,
                'amount' => $request->amount,
                'status' => $request->response == 'success' ? 'C' : 'F',
                'remark' => 'Add Money in Wallet',
            ]);

            $user->wallet_balance = ($request->response == 'success') ? $user->wallet_balance + $request->amount : $user->wallet_balance;
            $user->save();

            return ResponseBuilder::success(trans('global.ADD_MONEY_SUCCESS'), $this->success,$data);
        } catch (\Exception $e) {
            return ResponseBuilder::error(trans('global.SOMETHING_WENT'),$this->badRequest);
        }
    }
}
