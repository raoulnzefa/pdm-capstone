<?php

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

Route::get('/welcome', function () {
    return view('welcome');
});


//Backend
Route::get('/admin/login', 'Backend\AdminAuthController@showLoginForm')->name('admin_login');
Route::post('/admin/login', 'Backend\AdminAuthController@login')->name('admin.login');
Route::get('/admin/dashboard', 'Backend\DashboardController@index')->name('admin_dashboard');
Route::get('/admin/logout', 'Backend\AdminAuthController@logout')->name('admin.logout');

// Password Reset Routes...
Route::get('/admin/password/reset', 'Backend\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');

Route::post('/admin/password/email', 'Backend\AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
        
Route::get('/admin/password/reset/{token}', 'Backend\AdminResetPasswordController@showResetForm')->name('admin.password.reset');
        
Route::post('/admin/password/reset', 'Backend\AdminResetPasswordController@reset');

//Route::get('/admin/user/new', 'Backend\AdminCrudController@create');
//Route::get('/admin/user/{admin}', 'Backend\UserController@editAccount');
// Route::get('/admin/edit-account/{admin}', 'Backend\UserController@editAccount');
//Route::get('/admin/user/change-password/{admin}', 'Backend\UserController@changePass');
Route::get('/admin/users', 'Backend\AdminCrudController@index');

Route::get('/admin/categories','Backend\CategoryController@index')->name('categories');
//Route::resource('/admin/product','Backend\ProductController');

Route::get('/admin/products/catalog', 'Backend\ProductController@index')->name('product_catalog');
Route::get('/admin/product/new','Backend\ProductController@create');
Route::get('/admin/product/{product}/edit', 'Backend\ProductController@edit');
Route::get('/admin/product/add-stock/{product}', 'Backend\ProductController@addStock');

Route::get('/admin/inventory', 'Backend\InventoryController@index')->name('inventory');
Route::get('/admin/orders', 'Backend\OrderController@index')->name('orders');


Route::get('/admin/order/{order}/details', 'Backend\OrderController@viewOrder')->name('order_details_admin');
Route::get('/admin/invoices', 'Backend\InvoiceController@index');
Route::get('/admin/order/{order}/invoice', 'Backend\InvoiceController@viewInvoice');

Route::get('/admin/order/{order}/ship', 'Backend\ShipController@shipOrder');

Route::get('/admin/order/{order}/cancel', 'Backend\OrderController@cancelOrderRefund');

Route::get('/admin/customers', 'Backend\CustomerController@index');


Route::post('/order/cancel', 'Backend\OrderController@cancelOrder');

Route::get('admin/sales', 'Backend\SalesController@index');

Route::get('admin/reason', 'Backend\ReasonController@index');

Route::get('admin/user-logs', 'Backend\UserLogsController@index');

Route::post('admin/decline-cancellation-request', 'Backend\CancellationController@submitDecline')->name('submit_cancellation_decline');

//Frontend
Route::get('/', 'Frontend\HomePageController@index')->name('frontend_homepage');

Route::post('/register', 'Frontend\CustomerAuthController@register')->name('customer_register');
Route::get('/login', 'Frontend\CustomerAuthController@showLoginForm')->name('customer_login');
Route::post('/login', 'Frontend\CustomerAuthController@login')->name('customer.login');
Route::get('/logout', 'Frontend\CustomerAuthController@logout')->name('customer.logout');
Route::get('/register', 'Frontend\CustomerAuthController@showRegisterForm')->name('customer_regisration');
Route::get('/email-verified/{email}/{token}', 'Frontend\CustomerAuthController@emailVerified')->name('email.verified');


//Customer Password Reset
// Password Reset Routes...
Route::get('/password/reset', 'Frontend\CustomerForgotPasswordController@showLinkRequestForm')->name('customer.password.request');

Route::post('/password/email', 'Frontend\CustomerForgotPasswordController@sendResetLinkEmail')->name('customer.password.email');
        
Route::get('/password/reset/{token}', 'Frontend\CustomerResetPasswordController@showResetForm')->name('customer.password.reset');
        
Route::post('/password/reset', 'Frontend\CustomerResetPasswordController@reset');


Route::get('/products', 'Frontend\ProductPageController@index')->name('frontend_product_page');

Route::get('/products/category/{category}', 'Frontend\ProductPageController@productsByCategory');

Route::get('/category/{category}/type/{type}', 'Frontend\ProductPageController@productsByType');

Route::get('/product/view/{product}', 'Frontend\ViewProductController@viewProduct');

Route::get('/about-us', 'Frontend\PageController@aboutUs');
// Route::get('/contact-us', function() {
// 	return view('frontend.contact_us');
// });

Route::get('/my-account', 'Frontend\CustomerController@index')->name('customer.my-account');
Route::get('/my-account/order-status', 'Frontend\OrderStatusController@index')->name('customer.order_status');
// Route::get('/my-account/completed-orders', 'Frontend\CompletedOrderController@index')->name('customer.completed');
Route::get('/my-account/order-history', 'Frontend\OrderHistoryController@index')->name('customer.order_history');
Route::get('/my-account/account-details', 'Frontend\AccountDetailsController@index')->name('customer.account_details');
Route::get('/order/{order}/details', 'Frontend\CustomerController@orderDetails')->name('customer.order.details');
Route::get('/my-account/return-requests', 'Frontend\CustomerController@returnRequestsList')->name('customer.return_requests');

// Route::get('/my-account/change-email/{email}', 'Frontent\AccountDetailsController@changeEmailPage')->name('change.email.page');
// Route::get('/my-account/change-password/{email}', 'Frontent\AccountDetailsController@changePasswordPage')->name('change.password.page');

Route::get('/cart', 'Frontend\CartController@index');

Route::get('/checkout', 'Frontend\CheckoutController@index')->name('checkout');
//Payment
Route::post('/checkout/submit', 'Frontend\PaymentController@checkoutSubmit')->name('place_order');
Route::get('/status', 'Frontend\PaymentController@getPaymentStatus');

Route::get('/payment/status', 'Frontend\PaymentController@paymentStatus');

Route::get('/order-submitted', 'Frontend\CustomerController@orderSubmitted');
// Route::post('voucher/apply', 'Frontend\CheckoutController@applyVoucher')->name('apply_voucher');
// Route::delete('voucher/remove', 'Frontend\CheckoutController@removeVoucher')->name('remove_voucher');
//Route::get('/order/{order}/invoice', 'Frontend\CustomerController@viewInvoice');

//Route::get('/payment/{invoice}', 'Frontend\PaymentController@paymentPage');
Route::get('checkout/failed', 'Frontend\CheckoutController@checkoutFailed')->name('checkout.failed');;

Route::get('/payment/success', 'Frontend\PaymentController@paymentSuccess')->name('payment.success');

Route::get('/paypal-success', 'Frontend\CheckoutController@paypalSuccess')->name('paypal.success');

Route::get('/account/created', 'Frontend\CustomerAuthController@accountCreated')->name('account.created');

Route::get('/my-account/change-password', 'Frontend\CustomerController@changePass')->name('customer.change_pass');

Route::get('/my-account/order/number/upload-bank-deposit-slip', 'Frontend\BankDepositSlipController@upload')->name('upload_bank_deposit_slip');

Route::get('/order/received', 'Frontend\CheckoutController@orderReceived')->name('order.received');

Route::get('order/{order}/cancellation', 'Frontend\CancellationController@cancelRequest');

Route::get('order/{order}/request-return', 'Frontend\CustomerController@returnRequest');

Route::post('return-request', 'Frontend\ReturnController@store')->name('submit_request');

Route::get('return-request/submitted/{order}', 'Frontend\ReturnController@submitted')->name('return.request.submitted');

Route::get('cancellation-request/submitted', 'Frontend\CancellationController@submitted')->name('cancel.request.submitted');

Route::get('cancellation-request/{cancelRequest}/details', 'Frontend\CancellationController@details')->name('cancel_request_details');

Route::post('cancellation', 'Frontend\CancellationController@store')->name('submit_cancel_request');

Route::get('admin/customers/cancellation-requests', 'Backend\CancellationController@index')->name('cancellation_requests');
Route::get('admin/customers/return-requests', 'Backend\ReturnRequestController@index')->name('return_requests');

Route::get('my-account/cancellation-requests', 'Frontend\CustomerController@cancellationList')->name('customer.cancellation');

Route::get('admin/order/cancellation/{cancellation}/details', 'Backend\CancellationController@details')->name('cancellation_details');

Route::get('admin/cancellation-request/{cancellation}/decline', 'Backend\CancellationController@declineForm')->name('cancellation_decline_form');

Route::put('admin/cancellation-request/approve', 'Backend\CancellationController@approveRequest')->name('approve_cancellation');

Route::get('my-account/cancelled-orders', 'Frontend\CustomerController@cancelledOrders')->name('customer.cancelled_orders');

Route::get('admin/order/return/{returnRequest}/details', 'Backend\ReturnRequestController@details')->name('return_request_details');;

Route::post('search-product', 'Frontend\ProductPageController@searchProduct')->name('search.product');
Route::get('terms-and-conditions', function() {
	return view('frontend.terms_and_conditions');
});

Route::get('cancel-and-return', function() {
	return view('frontend.cancel_and_return');
});

Route::get('search-product', 'Frontend\ProductPageController@searchResult')->name('search.result');
// reports

Route::get('admin/reports/inventory', 'Backend\ReportsController@inventoryReportIndex');
Route::get('admin/reports/products', 'Backend\ReportsController@productsReportIndex');

Route::get('admin/reports/sales', 'Backend\ReportsController@salesReport');

Route::get('admin/bank-account', 'Backend\BankAccountController@index');
Route::get('admin/invoice/{invoice}', 'Backend\InvoiceController@viewInvoice');

Route::get('invoice/{invoice}', 'Frontend\InvoiceController@viewInvoice');

Route::get('admin/voucher-code', 'Backend\VoucherCodeController@index');

Route::get('featured-products', 'Frontend\FeaturedProductController@index');
Route::get('featured-products/category/{category}', 'Frontend\FeaturedProductController@category');

Route::get('order/{order}/upload-deposit-slip', 'Frontend\UploadDepositSlipController@index');
Route::post('order/upload-deposit-slip', 'Frontend\UploadDepositSlipController@store')->name('upload_deposit_slip');
Route::get('order/{order}/deposit-slip/uploaded', 'Frontend\UploadDepositSlipController@uploadedMessage')->name('uploaded_msg');

Route::get('return-request/{returnRequest}', 'Frontend\ReturnController@details')->name('return_request_details');

// Admin Return Request
Route::put('admin/order/return-request/{returnRequest}/approve', 'Backend\ReturnRequestController@approveRequest')->name('approve_return_request');

Route::put('admin/order/return-request/{returnRequest}/decline', 'Backend\ReturnRequestController@declineRequest')->name('decline_return_request');

Route::put('admin/order/return-request/{returnRequest}/refund', 'Backend\ReturnRequestController@refundRequest')->name('refund_return_request');

Route::get('admin/order/{returnRequest}/decline-return-request', '
	Backend\ReturnRequestController@declineRequestForm')->name('decline_return_form');
// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

// shipping rate
Route::get('admin/delivery-fee', 'Backend\ShippingRateController@index');


Route::get('/admin/user/edit-information', 'Backend\AdminCrudController@editInformation');

Route::get('/admin/reports/customer-list', 'Backend\CustomerController@list');

Route::get('/admin/reports/best-selling', 'Backend\ReportsController@bestSelling');

Route::put('/admin/order/return-request/complete/{returnRequest}', 'Backend\ReturnRequestController@completeReturnRequest')->name('complete_return_request');

Route::get('email-verified', 'Frontend\CustomerAuthController@customerVerified')->name('customer_verified');

Route::get('my-account/addresses', 'Frontend\AddressController@index')->name('customer_address');
Route::get('my-account/addresses/create', 'Frontend\AddressController@create')->name('create_address');
Route::get('my-account/address/edit/{address}', 'Frontend\AddressController@edit')->name('edit_address');


// orders
Route::get('/admin/orders/for-pickup', 'Backend\OrderController@forPickup')->name('orders_for_pickup');
Route::get('/admin/orders/pending-payment', 'Backend\OrderController@pendingPayment')->name('orders_pending_payment');
Route::get('/admin/orders/processing', 'Backend\OrderController@processing')->name('orders_processing');
Route::get('/admin/orders/delivered', 'Backend\OrderController@delivered')->name('orders_delivered');
Route::get('/admin/orders/completed', 'Backend\OrderController@completed')->name('orders_completed');
 
// products with variants and no variants
Route::get('/admin/products/product-no-variants', 'Backend\ProductController@productNoVariants')->name('product_no_variants');
Route::get('/admin/products/product-with-variants', 'Backend\ProductController@productWithVariants')->name('product_with_variants');

