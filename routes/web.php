<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

Route::get('lang/change', [App\Http\Controllers\LangController::class, 'change'])->name('changeLang');

Route::post('payments/razorpay/createorder', [App\Http\Controllers\RazorPayController::class, 'createOrderid']);

Route::post('payments/getpaytmchecksum', [App\Http\Controllers\PaymentController::class, 'getPaytmChecksum']);

Route::post('payments/validatechecksum', [App\Http\Controllers\PaymentController::class, 'validateChecksum']);

Route::post('payments/initiatepaytmpayment', [App\Http\Controllers\PaymentController::class, 'initiatePaytmPayment']);

Route::middleware(['permission:terms,termsAndConditions'])->group(function () {

    Route::get('termsAndConditions', [App\Http\Controllers\TermsAndConditionsController::class, 'index'])->name('termsAndConditions');
});
Route::middleware(['permission:privacy,privacyPolicy'])->group(function () {

    Route::get('privacyPolicy', [App\Http\Controllers\TermsAndConditionsController::class, 'privacyindex'])->name('privacyPolicy');
});

Route::middleware(['permission:refund,refundPolicy'])->group(function () {

    Route::get('refund-policy', [App\Http\Controllers\RefundPolicyController::class, 'index'])->name('refund-policy');
});

Route::middleware(['permission:about_us,aboutUs'])->group(function () {

    Route::get('about-us', [App\Http\Controllers\AboutUsController::class, 'index'])->name('about-us');
});

Route::middleware(['permission:support,support'])->group(function () {

    Route::get('support', [App\Http\Controllers\SupportController::class, 'index'])->name('support');
});


Route::middleware(['permission:items,items.create'])->group(function () {

    Route::get('/item/create/{id}', [App\Http\Controllers\ItemController::class, 'create']);
});

Route::middleware(['permission:coupons,coupons.create'])->group(function () {

    Route::get('/coupons/create/{id}', [App\Http\Controllers\CouponController::class, 'create']);
});

Route::middleware(['permission:category,categories'])->group(function () {

    Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories');
});
Route::middleware(['permission:category-edit,categories.edit'])->group(function () {

    Route::get('/categories/edit/{id}', [App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
});
Route::middleware(['permission:category-create,categories.create'])->group(function () {

    Route::get('/categories/create', [App\Http\Controllers\CategoryController::class, 'create'])->name('categories.create');
});

Route::middleware(['permission:users,users'])->group(function () {

    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
});
Route::middleware(['permission:users-edit,users.edit'])->group(function () {

    Route::get('/users/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
});
Route::middleware(['permission:users-view,users.view'])->group(function () {

    Route::get('/users/view/{id}', [App\Http\Controllers\UserController::class, 'view'])->name('users.view');
});

Route::get('/users/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('users.profile');

Route::post('/users/profile/update/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.profile.update');

Route::middleware(['permission:users-create,users.create'])->group(function () {

    Route::get('/users/create', [App\Http\Controllers\UserController::class, 'create'])->name('users.create');
});
Route::middleware(['permission:items,items'])->group(function () {

    Route::get('/items', [App\Http\Controllers\ItemController::class, 'index'])->name('items');
});
Route::middleware(['permission:items-edit,items.edit'])->group(function () {

    Route::get('/items/edit/{id}', [App\Http\Controllers\ItemController::class, 'edit'])->name('items.edit');
});
Route::middleware(['permission:items-create,items.create'])->group(function () {

    Route::get('/item/create', [App\Http\Controllers\ItemController::class, 'create'])->name('items.create');
});

Route::middleware(['permission:drivers,drivers'])->group(function () {

    Route::get('/drivers', [App\Http\Controllers\DriverController::class, 'index'])->name('drivers');
});
Route::middleware(['permission:drivers-edit,drivers.edit'])->group(function () {

    Route::get('/drivers/edit/{id}', [App\Http\Controllers\DriverController::class, 'edit'])->name('drivers.edit');
});
Route::middleware(['permission:drivers-create,drivers.create'])->group(function () {

    Route::get('/drivers/create', [App\Http\Controllers\DriverController::class, 'create'])->name('drivers.create');
});
Route::middleware(['permission:drivers-view,drivers.view'])->group(function () {

    Route::get('/drivers/view/{id}', [App\Http\Controllers\DriverController::class, 'view'])->name('drivers.view');
});
Route::middleware(['permission:orders,orders'])->group(function () {

    Route::get('/orders/', [App\Http\Controllers\OrderController::class, 'index'])->name('orders');
});

Route::middleware(['permission:orders-edit,orders.edit'])->group(function () {

    Route::get('/orders/edit/{id}', [App\Http\Controllers\OrderController::class, 'edit'])->name('orders.edit');
});

Route::middleware(['permission:coupons,coupons'])->group(function () {

    Route::get('/coupons', [App\Http\Controllers\CouponController::class, 'index'])->name('coupons');
});
Route::middleware(['permission:coupons-edit,coupons.edit'])->group(function () {

    Route::get('/coupons/edit/{id}', [App\Http\Controllers\CouponController::class, 'edit'])->name('coupons.edit');
});

Route::middleware(['permission:coupons-create,coupons.create'])->group(function () {

    Route::get('/coupons/create', [App\Http\Controllers\CouponController::class, 'create'])->name('coupons.create');
});
Route::middleware(['permission:payments,payments'])->group(function () {

    Route::get('/payments', [App\Http\Controllers\AdminPaymentsController::class, 'index'])->name('payments');
});


Route::middleware(['permission:dynamic-notifications-index,dynamic-notification.index'])->group(function () {

    Route::get('dynamic-notification', [App\Http\Controllers\DynamicNotificationController::class, 'index'])->name('dynamic-notification.index');
});
Route::middleware(['permission:dynamic-notifications-save,dynamic-notification.save'])->group(function () {

    Route::get('dynamic-notification/save/{id?}', [App\Http\Controllers\DynamicNotificationController::class, 'save'])->name('dynamic-notification.save');
});

Route::get('dynamic-notification/delete/{id}', [App\Http\Controllers\DynamicNotificationController::class, 'delete'])->name('dynamic-notification.delete');

Route::middleware(['permission:god-eye,map'])->group(function () {
    Route::get('/map', [App\Http\Controllers\MapController::class, 'index'])->name('map');
});

Route::post('/map/get_order _info', [App\Http\Controllers\MapController::class, 'getOrderInfo'])->name('map.getOrderInfo');

Route::prefix('settings')->group(function () {

    Route::middleware(['permission:currency,currencies'])->group(function () {

        Route::get('/currencies', [App\Http\Controllers\CurrencyController::class, 'index'])->name('currencies');
    });
    Route::middleware(['permission:currency-edit,currencies.edit'])->group(function () {

        Route::get('/currencies/edit/{id}', [App\Http\Controllers\CurrencyController::class, 'edit'])->name('currencies.edit');
    });
    Route::middleware(['permission:currency-create,currencies.create'])->group(function () {

        Route::get('/currencies/create', [App\Http\Controllers\CurrencyController::class, 'create'])->name('currencies.create');
    });

    Route::middleware(['permission:global-setting,settings.app.globals'])->group(function () {

        Route::get('app/globals', [App\Http\Controllers\SettingsController::class, 'globals'])->name('settings.app.globals');
    });

    Route::middleware(['permission:admin-commission,settings.app.adminCommission'])->group(function () {

        Route::get('app/adminCommission', [App\Http\Controllers\SettingsController::class, 'adminCommission'])->name('settings.app.adminCommission');
    });

    Route::middleware(['permission:delivery-charge,settings.app.deliveryCharge'])->group(function () {

        Route::get('app/deliveryCharge', [App\Http\Controllers\SettingsController::class, 'deliveryCharge'])->name('settings.app.deliveryCharge');
    });

    Route::middleware(['permission:payment-method,payment-method'])->group(function () {

        Route::get('payments/stripe', [App\Http\Controllers\SettingsController::class, 'stripe'])->name('settings.payments.stripe');
        Route::get('payments/applepay', [App\Http\Controllers\SettingsController::class, 'applepay'])->name('settings.payments.applepay');
        Route::get('payments/razorpay', [App\Http\Controllers\SettingsController::class, 'razorpay'])->name('settings.payments.razorpay');
        Route::get('payments/cod', [App\Http\Controllers\SettingsController::class, 'cod'])->name('settings.payments.cod');
        Route::get('payments/paypal', [App\Http\Controllers\SettingsController::class, 'paypal'])->name('settings.payments.paypal');
        Route::get('payments/paytm', [App\Http\Controllers\SettingsController::class, 'paytm'])->name('settings.payments.paytm');
        Route::get('payments/wallet', [App\Http\Controllers\SettingsController::class, 'wallet'])->name('settings.payments.wallet');
        Route::get('payments/payfast', [App\Http\Controllers\SettingsController::class, 'payfast'])->name('settings.payments.payfast');
        Route::get('payments/paystack', [App\Http\Controllers\SettingsController::class, 'paystack'])->name('settings.payments.paystack');
        Route::get('payments/flutterwave', [App\Http\Controllers\SettingsController::class, 'flutterwave'])->name('settings.payments.flutterwave');
        Route::get('payments/mercadopago', [App\Http\Controllers\SettingsController::class, 'mercadopago'])->name('settings.payments.mercadopago');
        Route::get('payments/orangepay', [App\Http\Controllers\SettingsController::class, 'orangepay'])->name('settings.payments.orangepay');
        Route::get('payments/midtrans', [App\Http\Controllers\SettingsController::class, 'midtrans'])->name('settings.payments.midtrans');
        Route::get('payments/xendit', [App\Http\Controllers\SettingsController::class, 'xendit'])->name('settings.payments.xendit');
    });

    Route::middleware(['permission:language,settings.app.languages'])->group(function () {
        Route::get('/languages', [App\Http\Controllers\SettingsController::class, 'languages'])->name('settings.languages');
    });

    // the below route with the middlware 'langauge' is working for save, edit, and create routes
    Route::middleware(['permission:language-' . ((str_contains(Request::url(), 'save')) ? (explode("save", Request::url())[1] ? "edit" : "create") : Request::url()) .',settings.app.languages.' . ((str_contains(Request::url(), 'save')) ? (explode("save", Request::url())[1] ? "edit" : "create") : Request::url())])->group(function () {
        Route::get('/languages/save/{id?}', [App\Http\Controllers\SettingsController::class, 'saveLanguage'])->name('settings.languages.save');
    });
});

Route::middleware(['permission:general-notifications-send,notification.send'])->group(function () {

    Route::get('/notification/send', [App\Http\Controllers\NotificationController::class, 'send'])->name('notification/send');
});

Route::middleware(['permission:general-notifications,notification'])->group(function () {

    Route::get('/notification', [App\Http\Controllers\NotificationController::class, 'index'])->name('notification');
});

Route::post('broadcastnotification', [App\Http\Controllers\NotificationController::class, 'broadcastnotification'])->name('broadcastnotification');

Route::get('/orders/print/{id}', [App\Http\Controllers\OrderController::class, 'orderprint'])->name('vendors.orderprint');


Route::get('payment/success', [App\Http\Controllers\PaymentController::class, 'paymentsuccess'])->name('payment.success');

Route::get('payment/failed', [App\Http\Controllers\PaymentController::class, 'paymentfailed'])->name('payment.failed');

Route::get('payment/pending', [App\Http\Controllers\PaymentController::class, 'paymentpending'])->name('payment.pending');

Route::middleware(['permission:banners,banners'])->group(function () {

    Route::get('/banners', [App\Http\Controllers\BannerController::class, 'index'])->name('banners');
});
Route::middleware(['permission:banners-create,banners.create'])->group(function () {

    Route::get('/banners/create', [App\Http\Controllers\BannerController::class, 'create'])->name('banners.create');
});
Route::middleware(['permission:banners-edit,banners.edit'])->group(function () {

    Route::get('/banners/edit/{id}', [App\Http\Controllers\BannerController::class, 'edit'])->name('banners.edit');
});
Route::middleware(['permission:cms,cms'])->group(function () {

    Route::get('cms', [App\Http\Controllers\CmsController::class, 'index'])->name('cms');
});
Route::middleware(['permission:cms-edit,cms.edit'])->group(function () {

    Route::get('/cms/edit/{id}', [App\Http\Controllers\CmsController::class, 'edit'])->name('cms.edit');
});
Route::middleware(['permission:cms-create,cms.create'])->group(function () {

    Route::get('/cms/create', [App\Http\Controllers\CmsController::class, 'create'])->name('cms.create');
});

Route::middleware(['permission:item-attribute,attributes'])->group(function () {

    Route::get('/attributes', [App\Http\Controllers\AttributeController::class, 'index'])->name('attributes');
});
Route::middleware(['permission:item-attribute-edit,attributes.edit'])->group(function () {

    Route::get('/attributes/edit/{id}', [App\Http\Controllers\AttributeController::class, 'edit'])->name('attributes.edit');
});
Route::middleware(['permission:item-attribute-create,attributes.create'])->group(function () {

    Route::get('/attributes/create', [App\Http\Controllers\AttributeController::class, 'create'])->name('attributes.create');
});

Route::middleware(['permission:footer-template,footer'])->group(function () {

    Route::get('footerTemplate', [App\Http\Controllers\SettingsController::class, 'footerTemplate'])->name('footerTemplate');
});


Route::middleware(['permission:homepage-template,homepage'])->group(function () {

    Route::get('/homepageTemplate', [App\Http\Controllers\SettingsController::class, 'homepageTemplate'])->name('homepageTemplate');
});
Route::middleware(['permission:landingpage-template,landingpage'])->group(function () {

    Route::get('/landingpageTemplate', [App\Http\Controllers\SettingsController::class, 'landingpageTemplate'])->name('landingpageTemplate');
});

Route::middleware(['permission:header-template,header'])->group(function () {

    Route::get('/headerTemplate', [App\Http\Controllers\SettingsController::class, 'headerTemplate'])->name('headerTemplate');
});

Route::middleware(['permission:reports,report.index'])->group(function () {

    Route::get('report/{type}', [App\Http\Controllers\ReportController::class, 'index'])->name('report.index');
});

Route::middleware(['permission:tax,tax'])->group(function () {

    Route::get('/tax', [App\Http\Controllers\TaxController::class, 'index'])->name('tax');
});
Route::middleware(['permission:tax-edit,tax.edit'])->group(function () {

    Route::get('/tax/edit/{id}', [App\Http\Controllers\TaxController::class, 'edit'])->name('tax.edit');
});
Route::middleware(['permission:tax-create,tax.create'])->group(function () {

    Route::get('/tax/create', [App\Http\Controllers\TaxController::class, 'create'])->name('tax.create');
});

Route::middleware(['permission:email-template-index,email-templates.index'])->group(function () {

    Route::get('email-templates', [App\Http\Controllers\SettingsController::class, 'emailTemplatesIndex'])->name('email-templates.index');
});
Route::middleware(['permission:email-template-edit,email-templates.edit'])->group(function () {

    Route::get('email-templates/save/{id?}', [App\Http\Controllers\SettingsController::class, 'emailTemplatesSave'])->name('email-templates.save');
});

Route::get('email-templates/delete/{id}', [App\Http\Controllers\SettingsController::class, 'emailTemplatesDelete'])->name('email-templates.delete');

Route::post('send-email', [App\Http\Controllers\SendEmailController::class, 'sendMail'])->name('sendMail');

Route::middleware(['permission:roles,role.index'])->group(function () {

    Route::get('role', [App\Http\Controllers\RoleController::class, 'index'])->name('role.index');
});
Route::middleware(['permission:roles-save,role.save'])->group(function () {

    Route::get('role/save', [App\Http\Controllers\RoleController::class, 'save'])->name('role.save');

    Route::post('role/store', [App\Http\Controllers\RoleController::class, 'store'])->name('role.store');
});
Route::middleware(['permission:roles-delete,role.delete'])->group(function () {

    Route::get('role/delete/{id}', [App\Http\Controllers\RoleController::class, 'delete'])->name('role.delete');
});
Route::middleware(['permission:roles-edit,role.edit'])->group(function () {

    Route::get('role/edit/{id}', [App\Http\Controllers\RoleController::class, 'edit'])->name('role.edit');

    Route::post('role/update/{id}', [App\Http\Controllers\RoleController::class, 'update'])->name('role.update');
});

Route::middleware(['permission:admins,admin.users'])->group(function () {

    Route::get('admin-users', [App\Http\Controllers\UserController::class, 'adminUsers'])->name('admin.users');
});
Route::middleware(['permission:admins-users-create,admin.users.create'])->group(function () {

    Route::get('admin-users/create', [App\Http\Controllers\UserController::class, 'createAdminUsers'])->name('admin.users.create');

    Route::post('admin-users/store', [App\Http\Controllers\UserController::class, 'storeAdminUsers'])->name('admin.users.store');
});
Route::middleware(['permission:admins-users-delete,admin.users.delete'])->group(function () {

    Route::get('admin-users/delete/{id}', [App\Http\Controllers\UserController::class, 'deleteAdminUsers'])->name('admin.users.delete');
});
Route::middleware(['permission:admins-users-edit,admin.users.edit'])->group(function () {

    Route::get('admin-users/edit/{id}', [App\Http\Controllers\UserController::class, 'editAdminUsers'])->name('admin.users.edit');

    Route::post('admin-users/update/{id}', [App\Http\Controllers\UserController::class, 'updateAdminUsers'])->name('admin.users.update');
});

Route::middleware(['permission:brands,brands'])->group(function () {

    Route::get('/brands', [App\Http\Controllers\BrandController::class, 'brand'])->name('brands');
});
Route::middleware(['permission:brands-create,brands.create'])->group(function () {

    Route::get('/brands/create', [App\Http\Controllers\BrandController::class, 'brandCreate'])->name('brands.create');
});
Route::middleware(['permission:brands-edit,brands.edit'])->group(function () {

    Route::get('/brands/edit/{id}', [App\Http\Controllers\BrandController::class, 'brandEdit'])->name('brands.edit');
});

Route::middleware(['permission:wallet-transaction,wallet.transaction'])->group(function () {

    Route::get('wallet-transactions', [App\Http\Controllers\TransactionController::class, 'index'])->name('walletstransaction');
    Route::get('/wallet-transactions/{id}', [App\Http\Controllers\TransactionController::class, 'index'])->name('users.walletstransaction');
});
Route::middleware(['permission:app-notifications,app.notification'])->group(function () {

    Route::get('notifications', [App\Http\Controllers\DynamicNotificationController::class, 'index'])->name('notifications.index');
});
Route::middleware(['permission:app-notifications-view,app.notification.view'])->group(function () {

    Route::get('/notifications/view/{id}', [App\Http\Controllers\DynamicNotificationController::class, 'view'])->name('notifications.view');
});

Route::middleware(['permission:pending_orders,pending.orders'])->group(function () {

    Route::get('/pending-driver-allocation/', [App\Http\Controllers\OrderController::class, 'driverAllocation'])->name('orders.driver.allocation');
});
Route::post('/delete-user/{id}', [App\Http\Controllers\DeleteUserAuthenticationController::class, 'deleteUser'])->name('delete-user');
Route::post('store-firebase-service', [App\Http\Controllers\HomeController::class, 'storeFirebaseService'])->name('store-firebase-service');
