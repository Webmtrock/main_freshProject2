<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\ResponseBuilder;
use App\Helper\Helper;
use App\Models\User;
use App\Models\Setting;
use App\Models\UserReferal;
use App\Models\Order;
use Craftsys\Msg91\Facade\Msg91;
use App\Models\VendorProfile;
use App\Models\DriverProfile;
use App\Models\EmailTemplate;
use App\Http\Resources\Admin\UserResource;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Validator;
use Auth;
use DB;
use App\Mail\NewSignUp;

class AuthController extends Controller
{
    /**
     * User Login/Register Function
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        try { 
          
            // Validation start
            $validSet = [
                'phone' => 'required | digits:10 | integer'
            ]; 

            $isInValid = $this->isValidPayload($request, $validSet);
            if($isInValid){
                return ResponseBuilder::error($isInValid, $this->badRequest);
            }
            // Validation end

            $user = User::findByPhone($request->phone);
            if($user) {
                if(!$user->status) {
                    return ResponseBuilder::error(trans('global.USER_BLOCKED'),$this->badRequest);
                }
                $user->device_id = $request->device_id ?? null;
                $user->device_token = $request->device_token ?? null;
                
                $data_otp = $this->sendOtp($request->phone);
                
                if(isset($data_otp['responseCode']) && ($data_otp['responseCode'] != 200)) {
                    return ResponseBuilder::error(trans('global.SOMETHING_WENT'), $this->success); 
                }

                $user->otp = isset($data_otp['otp']) ? $data_otp['otp'] : NULL;
                $user->otp_created_at = Carbon::now();
                $user->otp_verified = 0;
                $user->save();
             
                return ResponseBuilder::successMessage(trans('global.OTP_SENT'), $data_otp['responseCode']); 
            }

            if($request->referred_code) {
                $user = User::findByReferalCode($request->referred_code);
                if(!$user) {
                    return ResponseBuilder::error(trans('global.CODE_INVALID'), $this->success);
                }
                $previousBalance = $user->earned_balance;
                $bonusAmount = Setting::getDataByKey('referal_amount');
                $user->earned_balance += $bonusAmount->value;
                $user->save();

                $data_otp = $this->sendOtp($request->phone);

                if(isset($data_otp['responseCode']) && ($data_otp['responseCode'] != 200)) {
                    return ResponseBuilder::error(trans('global.SOMETHING_WENT'), $this->success); 
                }

                $userData = User::create([
                    'phone'        => $request->phone,
                    'referal_code' => Helper::generateReferCode(),
                    'otp' => isset($data_otp['otp']) ? $data_otp['otp'] : NULL,
                    'otp_created_at' => Carbon::now(),
                    'otp_verified' => 0,
                    'device_id'     => $request->device_id ?? null,
                    'device_token'  => $request->device_token ?? null,
                ]);

                UserReferal::create([
                    'referred_user_id' => $user->id,
                    'user_id' => $userData->id,
                    'amount' => $bonusAmount->value,
                ]);
                
                $remark='Earn by Refer';
                Helper::createTransaction($user->id,$previousBalance,$user->earned_balance,$bonusAmount->value,$status='E',$remark,$order=null);

                $data = trans('notifications.REFERRAL_USER');
                $userId = $user->id;
                $title = 'New Refer Alert';
                $notification_type = 'refer';
                Helper::pushNotification($data,$userId,$title, '', $notification_type);
            }
            else {

                $data_otp = $this->sendOtp($request->phone);

                if(isset($data_otp['responseCode']) && ($data_otp['responseCode'] != 200)) {
                    return ResponseBuilder::error(trans('global.SOMETHING_WENT'), $this->success); 
                }

                $userData = User::create([
                    'phone'        => $request->phone,
                    'referal_code' => Helper::generateReferCode(),
                    'otp' => isset($data_otp['otp']) ? $data_otp['otp'] : NULL,
                    'otp_created_at' => Carbon::now(),
                    'otp_verified' => 0,
                    'device_id'     => $request->device_id ?? null,
                    'device_token'  => $request->device_token ?? null,
                ]);
            }

            $userData->roles()->sync(2);
            
            /**Mail to admin */
            $settingData = Setting::getAllSettingData();
        
            $img = url('/'.config('app.logo').'/'.$settingData['logo_1']);
            $mailData = EmailTemplate::getMailByMailCategory(strtolower('new user register'));
            if(isset($mailData)) {

                $arr1 = array('{image}', '{number}');
                $arr2 = array($img, $request->phone);

                $msg = $mailData->email_content;
                $msg = str_replace($arr1, $arr2, $msg);
               
                $config = [
                    'from_email' => isset($mailData->from_email) ? $mailData->from_email : env('MAIL_FROM_ADDRESS'),
                    'name' => isset($mailData->from_name) ? $mailData->from_name : env('MAIL_FROM_NAME'),
                    'subject' => $mailData->email_subject, 
                    'message' => $msg,
                ];
  
                if(isset($settingData['admin_mail']) && !empty($settingData['admin_mail'])){
                    Mail::to($settingData['admin_mail'])->send(new NewSignUp($config));
                }
            }
            
            return ResponseBuilder::successMessage(trans('global.OTP_SENT'), $data_otp['responseCode']); 
        } catch (\Exception $e) {
            return ResponseBuilder::error(trans('global.SOMETHING_WENT'),$this->badRequest);
        }
    }

    /**
     * OTP Verification
     * @param \Illuminate\Http\Request $request, phone, otp
     * @return \Illuminate\Http\Response
     */
    public function verifyOtp(Request $request) {
        try {
            // Validation start
            $validSet = [
                'phone'     => 'required | digits:10 | integer | exists:users,phone',
                'otp'       => 'required | digits:4',
            ]; 

            $isInValid = $this->isValidPayload($request, $validSet);
            if($isInValid){
                return ResponseBuilder::error($isInValid, $this->badRequest);
            }
            // Validation end

            $user = User::findByPhone($request->phone);

            $message = $user->otp_verified ? trans('global.MOBILE_VERIFIED') : trans('global.USER_VERIFIED');

            if((isset($user->otp_created_at)) && ((strtotime($user->otp_created_at) + 900) < strtotime(now()))) {
                return ResponseBuilder::error(trans('global.OTP_EXPIRED'), $this->success);
            }
            if((isset($user->otp)) && ($request->otp != $user->otp)) {
                return ResponseBuilder::error(trans('global.INVALID_OTP'), $this->success);
            }
            
            $user->otp = NULL;
            $user->otp_created_at = NULL;
            $user->otp_verified = 1;
            $user->save();
 
            $token = $user->createToken('Token')->accessToken;
            $data = $this->setAuthResponse($user);
            
            return ResponseBuilder::successwithToken($token, $data, $message, $this->success);

        } catch (\Exception $e) {
            return ResponseBuilder::error(trans('global.SOMETHING_WENT'),$this->badRequest);
        }
    }

    /**
     * User Resend Otp Verify Function
     * @param \Illuminate\Http\Request $request, phone, otp
     * @return \Illuminate\Http\Response
     */
    public function resendOtp(Request $request) {
        try {
            // Validation start
            $validSet = [
                'phone' => 'required | digits:10 | integer',
            ]; 

            $isInValid = $this->isValidPayload($request, $validSet);
            if($isInValid){
                return ResponseBuilder::error($isInValid, $this->badRequest);
            }
            // Validation end
 
            $user = User::findByPhone($request->phone);
            $data_otp_resend = $this->sendOtp($request->phone);
            $user->otp = isset($data_otp_resend['otp']) ? $data_otp_resend['otp'] : NULL;
            $user->otp_created_at = now();
            $user->save();

            if(isset($data_otp_resend['responseCode']) && ($data_otp_resend['responseCode'] != 200)) {
                return ResponseBuilder::error(isset($data_otp_resend['message']) ? $data_otp_resend['message'] : trans('global.SOMETHING_WENT'), $this->success); 
            }
            return ResponseBuilder::successMessage(trans('global.OTP_SENT'), $data_otp_resend['responseCode']); 

        } catch (\Exception $e) {
            return ResponseBuilder::error(trans('global.SOMETHING_WENT'),$this->badRequest);
        }
    }

    /**
     * User Profile Update
     * @param \Illuminate\Http\Request $request, name, email, phone
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request) {
        try {
            $user = Auth::guard('api')->user();
            // Validation start
            $validSet = [
                'name' => 'required',
                'email' => 'nullable | email',
                'profile_image' => 'mimes:jpeg,png,jpg',
            ]; 

            $isInValid = $this->isValidPayload($request, $validSet);
            if($isInValid){
                return ResponseBuilder::error($isInValid, $this->badRequest);
            }
            // Validation end

            $imagePath = config('app.profile_image');
            $profileImageOld = $user->profile_image;

            $user->name = isset($request->name) ? $request->name : '';
            $user->email = isset($request->email) ? $request->email : '';
            $user->profile_image = $request->hasfile('profile_image') ? Helper::storeImage($request->file('profile_image'), $imagePath, $profileImageOld) : (isset($profileImageOld) ? $profileImageOld : '');
            $user->update();

            $data = $this->setAuthResponse($user);

            return ResponseBuilder::successMessage(trans('global.profile_updated'), $this->success, $data); 
            
        } catch (\Exception $e) {
            return ResponseBuilder::error(trans('global.SOMETHING_WENT'),$this->badRequest);
        }
    }

    /**
     * User Profile
     * @return \Illuminate\Http\Response
     */
    public function userProfile() {
        try {
            $user = Auth::guard('api')->user();  
            $data = $this->setAuthResponse($user);
            return ResponseBuilder::successMessage(trans('global.profile_detail'), $this->success, $data); 
        } catch (\Exception $e) {
            return ResponseBuilder::error(trans('global.SOMETHING_WENT'),$this->badRequest);
        }
    }

    /**
     * User logout Function
     * @return \Illuminate\Http\Response
     */
    public function logout() {
        try {
            if(!Auth::guard('api')->check()) {
                return ResponseBuilder::error($this->msg['LOGIN'], $this->badRequest);
            }
            
            Auth::guard('api')->user()->token()->revoke();
            
            return ResponseBuilder::successMessage($this->msg['LOG_OUT'], $this->success); 
        } catch (\Exception $e) {
            return ResponseBuilder::error(trans('global.SOMETHING_WENT'), $this->badRequest);
        }
    }

    public function updateLocation(Request $request) {
        try {
            $user = Auth::guard('api')->user();
       
            $validSet = [
                'latitude' => 'required',
                'longitude' => 'required',
            ]; 
            $isInValid = $this->isValidPayload($request, $validSet);

            if($isInValid){
                return ResponseBuilder::error($isInValid, $this->badRequest);
            }
            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
            $user->save();
            
            return ResponseBuilder::successMessage(trans('global.location_update'), $this->success); 

        } catch (\Exception $e) {
            return ResponseBuilder::error(trans('global.SOMETHING_WENT'), $this->badRequest);
        }
    }
    public function setAuthResponse($user) {
        return $this->response->user =  new UserResource($user);
    }

    /**
     * User Vendor Register Function
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function vendorRegister(Request $request) {
        DB::beginTransaction();
        try {
            $user = Auth::guard('api')->user();

            $vendor_exists = vendorProfile::getDataByUserId($user->id);

            if($vendor_exists) {
                return ResponseBuilder::error(trans('global.VENDOR_REGISTRATION_EXISTS'), $this->success);
            }
            // Validation start
            $validSet = [
                'store_name' => 'required',
                'store_image' => 'required | mimes:jpeg,png,jpg',
                'aadhar_number' => 'required | unique:vendor_profiles,aadhar_no',
                'pan_card_number' => 'required | unique:vendor_profiles,pan_no',
                'delivery_range' => 'required',
                'bank_statement' => 'required | mimes:jpeg,png,jpg',
                'pan_card_image' => 'required | mimes:jpeg,png,jpg',
                'aadhar_card_front' => 'required | mimes:jpeg,png,jpg',
                'aadhar_card_back' => 'required | mimes:jpeg,png,jpg',
                'location' => 'required',
                'address' => 'required',
            ]; 

            $isInValid = $this->isValidPayload($request, $validSet);
            if($isInValid){
                return ResponseBuilder::error($isInValid, $this->badRequest);
            }
            // Validation end
            
            $imagePath = config('app.vendor_document');
            $getLatiLong = $this->lookForPoints($request->location);
            
            $vendorData = VendorProfile::create(
            [
                'user_id' => $user->id,
                'store_name' => $request->store_name,
                'aadhar_no' => $request->aadhar_number,
                'pan_no' => strtoupper($request->pan_card_number),
                'store_image' => $request->hasfile('store_image') ? Helper::storeImage($request->file('store_image'), $imagePath, $request->storeImageOld) : (isset($request->storeImageOld) ? $request->storeImageOld : ''),
                'bank_statement' => $request->hasfile('bank_statement') ? Helper::storeImage($request->file('bank_statement'), $imagePath, $request->bankStatementOld) : (isset($request->bankStatementOld) ? $request->bankStatementOld : ''),
                'pan_card_image' => $request->hasfile('pan_card_image') ? Helper::storeImage($request->file('pan_card_image'), $imagePath, $request->panCardImageOld) : (isset($request->panCardImageOld) ? $request->panCardImageOld : ''),
                'aadhar_front_image' => $request->hasfile('aadhar_card_front') ? Helper::storeImage($request->file('aadhar_card_front'), $imagePath, $request->aadharCardFrontOld) : (isset($request->aadharCardFrontOld) ? $request->aadharCardFrontOld : ''),
                'aadhar_back_image' => $request->hasfile('aadhar_card_back') ? Helper::storeImage($request->file('aadhar_card_back'), $imagePath, $request->aadharCardBackOld) : (isset($request->aadharCardBackOld) ? $request->aadharCardBackOld : ''),
                'address' => $request->address,
                'location' => $request->location,
                'long'   =>  $getLatiLong['geometry']['location']['lng'] ?? '',
                'lat'    =>  $getLatiLong['geometry']['location']['lat'] ?? '',
                'status' => $request->vendorVerify ? $request->vendorVerify : 0,
            ]);

            $user->is_vendor = 1;
            $user->delivery_range = $request->delivery_range;
            $user->save();

            DB::table('role_user')->insert(['user_id' => $user->id, 'role_id' => 4]);

            /**Mail to admin */
            $settingData = Setting::getAllSettingData();
        
            $img = url('/'.config('app.logo').'/'.$settingData['logo_1']);
            $mailData = EmailTemplate::getMailByMailCategory(strtolower('approval pending'));
            if(isset($mailData)) {

                $arr1 = array('{image}', '{user_type}', '{number}');
                $arr2 = array($img, 'Vendor', $user->phone);

                $msg = $mailData->email_content;
                $msg = str_replace($arr1, $arr2, $msg);
               
                $config = [
                    'from_email' => isset($mailData->from_email) ? $mailData->from_email : env('MAIL_FROM_ADDRESS'),
                    'name' => isset($mailData->from_name) ? $mailData->from_name : env('MAIL_FROM_NAME'),
                    'subject' => 'Vendor' .$mailData->email_subject, 
                    'message' => $msg,
                ];
  
                if(isset($settingData['admin_mail']) && !empty($settingData['admin_mail'])){
                    Mail::to($settingData['admin_mail'])->send(new NewSignUp($config));
                }
            }

            DB::commit();
            return ResponseBuilder::successMessage(trans('global.VENDOR_REGISTRATION'), $this->success);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseBuilder::error(trans('global.SOMETHING_WENT'),$this->badRequest);
        }
    }

    /**
     * User Driver Register Function
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function driverRegister(Request $request) {
        DB::beginTransaction();
        try {
            $user = Auth::guard('api')->user();

            $driver_exists = driverProfile::getDataByUserId($user->id);

            if($driver_exists) {
                return ResponseBuilder::error(trans('global.DRIVER_REGISTRATION_EXISTS'), $this->success);
            }
            // Validation start
            $validSet = [
                'dob'               =>  'required | date | before:today',
                'aadhar_number'      => 'required | unique:driver_profiles,aadhar_no',
                'pan_card_number'    => 'required | unique:driver_profiles,pan_no',
                'vehicle_no'         => 'required | unique:driver_profiles,vehicle_no',
                'licence_no'         => 'required | unique:driver_profiles,licence_no',
                'bank_statement'     => 'required | mimes:jpeg,png,jpg',
                'pan_card_image'     => 'required | mimes:jpeg,png,jpg',
                'aadhar_front_image' => 'required | mimes:jpeg,png,jpg',
                'aadhar_back_image'  => 'required | mimes:jpeg,png,jpg',
                'licence_front_image'=> 'required | mimes:jpeg,png,jpg',
                'licence_back_image' => 'required | mimes:jpeg,png,jpg',
            ]; 

            $isInValid = $this->isValidPayload($request, $validSet);
            if($isInValid){
                return ResponseBuilder::error($isInValid, $this->badRequest);
            }
            // Validation end
            
            $imagePath = config('app.driver_document');
            
            $vendorData = DriverProfile::create([
                'user_id'   => $user->id,
                'dob'       => $request->dob,
                'aadhar_no' => $request->aadhar_number,
                'pan_no'    => strtoupper($request->pan_card_number),
                'vehicle_no' => $request->vehicle_no,
                'licence_no' => $request->licence_no,
                'bank_statement' => $request->hasfile('bank_statement') ? Helper::storeImage($request->file('bank_statement'), $imagePath) : '',
                'pan_card_image' => $request->hasfile('pan_card_image') ? Helper::storeImage($request->file('pan_card_image'), $imagePath) :'',
                'aadhar_front_image' => $request->hasfile('aadhar_front_image') ? Helper::storeImage($request->file('aadhar_front_image'), $imagePath) :  '',
                'aadhar_back_image' => $request->hasfile('aadhar_back_image') ? Helper::storeImage($request->file('aadhar_back_image'), $imagePath) : '',
                'licence_front_image' => $request->hasfile('licence_front_image') ? Helper::storeImage($request->file('licence_front_image'), $imagePath) : '',
                'licence_back_image' => $request->hasfile('licence_back_image') ? Helper::storeImage($request->file('licence_back_image'), $imagePath) : '',
            ]);

            $user->is_driver = true;
            $user->save();
            
            DB::table('role_user')->insert(['user_id' => $user->id, 'role_id' => 3]);

            /**Mail to admin */
            $settingData = Setting::getAllSettingData();
        
            $img = url('/'.config('app.logo').'/'.$settingData['logo_1']);
            $mailData = EmailTemplate::getMailByMailCategory(strtolower('approval pending'));
            if(isset($mailData)) {

                $arr1 = array('{image}', '{user_type}', '{number}');
                $arr2 = array($img, 'Driver', $user->phone);

                $msg = $mailData->email_content;
                $msg = str_replace($arr1, $arr2, $msg);
               
                $config = [
                    'from_email' => isset($mailData->from_email) ? $mailData->from_email : env('MAIL_FROM_ADDRESS'),
                    'name' => isset($mailData->from_name) ? $mailData->from_name : env('MAIL_FROM_NAME'),
                    'subject' => 'Driver' .$mailData->email_subject, 
                    'message' => $msg,
                ];
  
                if(isset($settingData['admin_mail']) && !empty($settingData['admin_mail'])){
                    Mail::to($settingData['admin_mail'])->send(new NewSignUp($config));
                }
            }
            DB::commit();
            return ResponseBuilder::successMessage(trans('global.DRIVER_REGISTRATION'), $this->success);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseBuilder::error(trans('global.SOMETHING_WENT'),$this->badRequest);
        }
    }

    /**
     * User Marketing Manager Register Function
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function marketingManagerRegister(Request $request) {
        DB::beginTransaction();
        try {
            $user = Auth::guard('api')->user();

            // $vendor_exists = vendorProfile::getDataByUserId($user->id);

            if($user->is_marketing_manager) {
                return ResponseBuilder::error(trans('global.MARKETING_MANAGER_REGISTRATION_EXISTS'), $this->success);
            }
            
            $user->is_marketing_manager = 1;
            $user->save();

            DB::table('role_user')->insert(['user_id' => $user->id, 'role_id' => 7]);

            DB::commit();
            return ResponseBuilder::successMessage(trans('global.MARKETING_MANAGER_REGISTRATION'), $this->success);

        } catch (\Exception $e) {
            DB::rollback();
            return ResponseBuilder::error(trans('global.SOMETHING_WENT'),$this->badRequest);
        }
    }

    /**
     * Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function marketingManagerdashboard(Request $request)
    {
        try {
            $user = Auth::guard('api')->user();
            
            if(!$user->as_marketing_manager_verified) {
                return ResponseBuilder::error(trans('global.MARKETING_MANAGER_UNVERIFIED'), $this->badRequest);
            }
            
            $users_list = UserReferal::where('referred_user_id', $user->id)->get();

            $users = [];
            
            if($users_list) {
                $total_earning = 0;
                foreach ($users_list as $user_list) {
                    $total_earning += $user_list->amount;
                    $users[] = $user_list->user_id;
                }
            }

            $total_orders = Order::whereIn('user_id', $users)->count();
            $this->response->referral_link = url('/').'/'.$user->referal_code;
            $this->response->referral_code = $user->referal_code;
            $this->response->earning = $total_earning ?? 0;
            $this->response->total_orders = $total_orders ?? 0;
            $this->response->users_list = $users_list->map(function($users_list){ return[
                'name' => isset($users_list->users) ? $users_list->users->name : '', 
                'phone' => isset($users_list->users) ? $users_list->users->phone : '',
                'profile_image' => (string)isset($users_list->users) && isset($users_list->users->profile_image) ? url(config('app.profile_image').'/'.$users_list->users->profile_image) : '',
            ];});
            
            return ResponseBuilder::success(trans('global.MARKETING_MANAGER_INFORMATION'), $this->success, $this->response);
            
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(),$this->badRequest);
            return ResponseBuilder::error(trans('global.SOMETHING_WENT'),$this->badRequest);
        }
    }
}
