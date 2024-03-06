<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AppSettingController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\VendorProductController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\CouponInventoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\WithdrawalRequestController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\WalletTransactionController;
use App\Http\Controllers\Admin\CommissionController;

Auth::routes();

Route::redirect('/', '/login');
// Route::redirect('/home', '/');
Route::GET('/{slug}', [PageController::class, 'viewPage']);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth']], function () {

    Route::GET('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::RESOURCE('banks', BankController::class);
    Route::RESOURCE('users', UserController::class);
    Route::GET('users/change-status/{id}', [UserController::class, 'changeStatus'])->name('users.change-status');
    Route::POST('users/add-fund/{id}', [UserController::class, 'addFund'])->name('users.add-fund');
    Route::POST('users/revoke-fund/{id}', [UserController::class, 'revokeFund'])->name('users.revoke-fund');
    Route::GET('user-referals', [UserController::class, 'userReferal'])->name('user-referal');
    Route::RESOURCE('site-setting', SettingController::class);
    Route::RESOURCE('app-setting', AppSettingController::class);
    Route::RESOURCE('pages', PageController::class);
    Route::RESOURCE('categories', CategoryController::class);
    Route::GET('categories/change-status/{id}', [CategoryController::class, 'changeStatus'])->name('categories.change-status');
    Route::RESOURCE('admin-products', ProductController::class);
    Route::GET('admin-products/change-status/{id}', [ProductController::class, 'changeStatus'])->name('admin-products.change-status');
    Route::RESOURCE('vendor-products', VendorProductController::class);
    Route::GET('vendor-products/change-status/{id}', [VendorProductController::class, 'changeStatus'])->name('vendor-products.change-status');
    Route::GET('vendor-products/products/{category_id}', [VendorProductController::class, 'getProductsByCategory'])->name('vendor-products.products');
    Route::GET('vendor-products/products-details/{product_id}', [VendorProductController::class, 'getProductsById'])->name('vendor-products.products-details');
    Route::RESOURCE('coupons', CouponController::class);
    Route::GET('coupons/change-status/{id}', [CouponController::class, 'changeStatus'])->name('coupons.change-status');

    Route::RESOURCE('sliders', SliderController::class);
    Route::GET('sliders/change-status/{id}', [SliderController::class, 'changeStatus'])->name('sliders.change-status');
    Route::RESOURCE('faqs', FaqController::class);
    Route::GET('faqs/change-status/{id}', [FaqController::class, 'changeStatus'])->name('faqs.change-status');
    Route::RESOURCE('email-templates', EmailTemplateController::class);
    Route::GET('email-templates/change-status/{id}', [EmailTemplateController::class, 'changeStatus'])->name('email-templates.change-status');
    Route::GET('banks/change-status/{id}', [BankController::class, 'changeStatus'])->name('banks.change-status');
    Route::GET('coupon-inventories', [CouponInventoryController::class, 'index'])->name('coupon-inventories.index');
    Route::RESOURCE('orders', OrderController::class);
    Route::POST('orders/change-order-status/{id}', [OrderController::class, 'changeOrderStatus'])->name('orders.change-order-status');
    Route::RESOURCE('taxes', TaxController::class);
    Route::GET('taxes/change-status/{id}', [TaxController::class, 'changeStatus'])->name('taxes.change-status');
    Route::GET('admin-products/categories-tax/{category_id}', [ProductController::class, 'getTaxByCategory'])->name('admin-products.categories-tax');
    Route::GET('withdrawal-requests', [WithdrawalRequestController::class, 'index'])->name('withdrawal-requests.index');
    Route::GET('withdrawal-requests/action/{id}', [WithdrawalRequestController::class, 'withdrawalAction'])->name('withdrawal-requests.action');
    Route::RESOURCE('notifications', NotificationController::class);
    Route::RESOURCE('permissions', PermissionController::class);
    Route::RESOURCE('roles', RoleController::class);
    Route::GET('wallet-transactions', [WalletTransactionController::class, 'index'])->name('wallet-transactions.index');;
    Route::GET('admin-commissions', [CommissionController::class, 'adminCommission'])->name('admin-commissions');;
    Route::GET('tax-commissions', [CommissionController::class, 'taxCommission'])->name('tax-commissions');;
    Route::GET('orders/download-invoice/{id}', [OrderController::class, 'downloadInvoice'])->name('orders.download-invoice');
});
