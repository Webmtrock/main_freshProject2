<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\ResponseBuilder;
use App\Helper\Helper;
use Auth;

class SupportController extends Controller
{
    public function sendImage(Request $request) {
        try {
            $user = Auth::guard('api')->user();

            // Validation start
            $validSet = [
                'image' => 'required',
            ]; 

            $isInValid = $this->isValidPayload($request, $validSet);
            if($isInValid){
                return ResponseBuilder::error($isInValid, $this->badRequest);
            }
            // Validation end

            $imagePath = config('app.support_image');

            $image = $request->hasfile('image') ? Helper::storeImage($request->file('image'), $imagePath, null) : '';
            $this->response->image = isset($image) ? url($imagePath.'/'.$image) : '';
            return ResponseBuilder::success(trans('global.IMAGE_SAVED'), $this->success, $this->response);

        } catch (\Exception $e) {
            return ResponseBuilder::error(trans('global.SOMETHING_WENT'),$this->badRequest);
        }
    }
}
