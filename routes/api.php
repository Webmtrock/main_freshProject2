<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\VendorController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\StoreController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderAddressController;
use App\Http\Controllers\Api\OrdersController;
use App\Http\Controllers\Api\ReferAndEearController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\FaqController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\WithdrawalController;
use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\SupportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Without login api's - Without Token
Route::post('login', [AuthController::class, 'login']);
Route::POST('verify-otp', [AuthController::class, 'verifyOtp']);
Route::GET('logout', [AuthController::class, 'logout']);
Route::POST('resend-otp', [AuthController::class, 'resendOtp']);


// 
Route::get('categories', [CategoryController::class, 'list']);

Route::get('products', [ProductController::class, 'list']);
Route::get('product/{productId}', [ProductController::class, 'view']);
//FAQ List
Route::get('faq-list', [FaqController::class, 'list']);
// Page
Route::get('pages', [PageController::class, 'pages']);

Route::GET('banks-list', [BankController::class, 'banksList']);

Route::group(['as' => 'api.', 'middleware' =>['auth:api']], function(){
    Route::GET('category/{categoryId}', [CategoryController::class, 'view']);
    Route::GET('single-category', [CategoryController::class, 'singleCategory']);
    Route::GET('stores', [VendorController::class, 'list']);
    Route::GET('user-profile', [AuthController::class, 'userProfile']);
    Route::POST('update-profile', [AuthController::class, 'updateProfile']);
    Route::GET('home', [HomeController::class, 'home']);
    Route::GET('near-stores', [HomeController::class, 'nearStores']);
    Route::GET('store-details/{id}', [StoreController::class, 'storeDetails']);
    Route::GET('search', [HomeController::class, 'searchResult']);
    Route::POST('add-cart', [CartController::class, 'addToCart']);
    Route::GET('my-cart', [CartController::class, 'myCart']);
    Route::GET('cart-related-product', [CartController::class, 'cartRelatedProduct']);
    Route::POST('order-tip', [CartController::class, 'orderTip']);
    Route::POST('coupon-apply', [CartController::class, 'couponApply']);
    Route::POST('update-cart', [CartController::class, 'updateCart']);
    Route::GET('remove-coupon', [CartController::class, 'removeCoupon']);
    Route::POST('remove-cart-item', [CartController::class, 'removeCartItem']);
    Route::GET('my-address', [OrderAddressController::class, 'addressList']);
    Route::POST('remove-address', [OrderAddressController::class, 'removeAddress']);
    Route::POST('add-address', [OrderAddressController::class, 'addAddress']);
    Route::POST('edit-address', [OrderAddressController::class, 'editAddress']);
    Route::POST('choose-order-address', [OrderAddressController::class, 'chooseOrderAddress']);
    Route::GET('coupons', [CouponController::class, 'list']);
    Route::GET('remove-tip', [CartController::class, 'removeTip']);
    Route::POST('order', [OrdersController::class, 'addOrder']);
    Route::GET('my-orders', [OrdersController::class, 'myOrders']);
    Route::GET('refer-and-earn', [ReferAndEearController::class, 'referAndeEarn']);
    Route::GET('wallet', [WalletController::class, 'myWallet']);
    Route::GET('order-details', [OrdersController::class, 'orderDetails']);
    Route::GET('notification-list', [NotificationController::class, 'notificationList']);
    Route::POST('update-location', [AuthController::class, 'updateLocation']);
    Route::POST('order-accept', [OrdersController::class, 'orderAccept']);
    Route::GET('driver-delivery-request-list', [DriverController::class, 'driverDeliveryRequestList']);
    Route::POST('assigned-order', [DriverController::class, 'assignedOrder']);
    Route::GET('assigned-order-list', [DriverController::class, 'assignedOrderList']);
    Route::POST('driver-order-status-update', [DriverController::class, 'orderStatusUpdate']);
    Route::POST('verify-delivery', [DriverController::class, 'verifyDelivery']);
    Route::POST('resend-delivery-otp', [DriverController::class, 'resendDeliveryOtp']);
    Route::GET('driver-delivery-mode-update', [DriverController::class, 'driverDeliveryModeUpdate']);
    Route::GET('vendor-dashboard', [VendorController::class, 'dashboard']);
    Route::POST('vendor-register', [AuthController::class, 'vendorRegister']);
    Route::POST('driver-register', [AuthController::class, 'driverRegister']);
    Route::GET('vendor-information', [VendorController::class, 'vendorInformation']);
    Route::POST('store-availability', [VendorController::class, 'storeAvailability']);
    Route::get('vendor-order-list', [VendorController::class, 'orderList']);
    Route::POST('vendor-reject-variant', [VendorController::class, 'rejectVariant']);
    Route::POST('vendor-add-product', [VendorController::class, 'addProduct']);
    Route::GET('vendor-product-list', [VendorController::class, 'vendorProductList']);
    Route::GET('vendor-product/{productId}', [VendorController::class, 'view']);
    Route::GET('payment-option', [OrdersController::class, 'paymentOption']);
    Route::GET('withdrawal-list', [WithdrawalController::class, 'list']);
    Route::POST('withdrawal-request', [WithdrawalController::class, 'withdrawalRequest']);
    Route::POST('vendor-product-status-update/{productId}', [VendorController::class, 'vendorProductStatusUpdate']);
    Route::GET('store-status-update', [VendorController::class, 'StoreStatusUpdate']);
    Route::GET('self-delivery-status-update', [VendorController::class, 'SelfDeliveryStatusUpdate']);
    Route::GET('store-timing', [VendorController::class, 'storeTiming']);
    Route::GET('driver-information', [DriverController::class, 'driverInformation']);
    Route::POST('add-money', [WalletController::class, 'addMoney']);
    Route::POST('online-success-payment', [OrdersController::class, 'successPayment']);
    Route::GET('account-details', [BankController::class, 'accountDetail']);
    Route::POST('add-account-details', [BankController::class, 'addAccountDetail']);
    Route::POST('vendor-information-edit', [VendorController::class, 'vendorInformationEdit']);
    Route::POST('send-image', [SupportController::class, 'sendImage']);
    Route::GET('marketing-manager-register', [AuthController::class, 'marketingManagerRegister']);
    Route::GET('marketing-manager-dashboard', [AuthController::class, 'marketingManagerdashboard']);
    
});
Route::GET('test-otp', [AuthController::class, 'testOtp']);
Route::GET('test-verify-otp', [AuthController::class, 'testverifyOtp']);
Route::GET('test-resend-otp', [AuthController::class, 'testresendOtp']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
