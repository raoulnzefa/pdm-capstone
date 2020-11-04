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
Route::get('/admin/users', 'Backend\AdminCrudController@index')->name('users');

Route::get('/admin/categories','Backend\CategoryController@index')->name('categories');
//Route::resource('/admin/product','Backend\ProductController');

Route::get('/admin/products', 'Backend\ProductController@index')->name('products');
Route::get('/admin/product/new','Backend\ProductController@create');
Route::get('/admin/product/{product}/edit', 'Backend\ProductController@edit');
Route::get('/admin/product/add-stock/{product}', 'Backend\ProductController@addStock');

Route::get('/admin/inventory', 'Backend\InventoryController@index')->name('inventory');

Route::get('/admin/orders', 'Backend\OrderController@index')->name('orders');


Route::get('/admin/order/{order}', 'Backend\OrderController@viewOrder')->name('order_details_admin');
//Route::get('/admin/invoices', 'Backend\InvoiceController@index');
Route::get('/admin/order/{order}/invoice', 'Backend\InvoiceController@viewInvoice')->name('admin.view_invoice');

Route::get('/admin/order/{order}/ship', 'Backend\ShipController@shipOrder');

Route::get('/admin/order/{order}/cancel', 'Backend\OrderController@cancelOrderRefund');

Route::get('/admin/customers', 'Backend\CustomerController@index')->name('customers');


Route::post('/order/cancel', 'Backend\OrderController@cancelOrder');

Route::get('admin/sales', 'Backend\SalesController@index')->name('sales');

//Route::get('admin/reason', 'Backend\ReasonController@index')->name('reason');

Route::post('admin/decline-cancellation-request', 'Backend\CancellationController@submitDecline')->name('submit_cancellation_decline');

//Frontend
Route::get('/', 'Frontend\HomePageController@index')->name('frontend_homepage');

Route::post('/register', 'Frontend\CustomerAuthController@register')->name('customer_register');
Route::get('/login', 'Frontend\CustomerAuthController@showLoginForm')->name('customer_login');
Route::post('/login', 'Frontend\CustomerAuthController@login')->name('customer.login');
Route::get('/logout', 'Frontend\CustomerAuthController@logout')->name('customer.logout');
Route::get('/create-account', 'Frontend\CustomerAuthController@showRegisterForm')->name('customer_registration');
Route::get('/email-verified/{email}/{token}', 'Frontend\CustomerAuthController@emailVerified')->name('email.verified');


//Customer Password Reset
// Password Reset Routes...
Route::get('/forgot-password', 'Frontend\CustomerForgotPasswordController@showLinkRequestForm')->name('customer.password.request');

Route::post('/password/email', 'Frontend\CustomerForgotPasswordController@sendResetLinkEmail')->name('customer.password.email');
        
Route::get('/password/reset/{token}', 'Frontend\CustomerResetPasswordController@showResetForm')->name('customer.password.reset');
        
Route::post('/password/reset', 'Frontend\CustomerResetPasswordController@reset');


Route::get('/products', 'Frontend\ProductPageController@index')->name('frontend_product_page');

Route::get('/products/{category}', 'Frontend\ProductPageController@productsByCategory')->name('frontend.products.by.category');

Route::get('/category/{category}/type/{type}', 'Frontend\ProductPageController@productsByType');

Route::get('/product/{category}/{product}', 'Frontend\ViewProductController@viewProduct')->name('customer_view_product');

Route::get('/about-us', 'Frontend\PageController@aboutUs');
// Route::get('/contact-us', function() {
// 	return view('frontend.contact_us');
// });


// Route::get('/my-account/order-status', 'Frontend\OrderStatusController@index')->name('customer.order_status');
// Route::get('/my-account/completed-orders', 'Frontend\CompletedOrderController@index')->name('customer.completed');
Route::get('/my-account/orders', 'Frontend\OrdersController@index')->name('customer.orders');
Route::get('/my-account/account-details', 'Frontend\AccountDetailsController@index')->name('customer.account_details');
Route::get('/my-account/order/{order}', 'Frontend\CustomerController@orderDetails')->name('customer.view_order');
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

Route::get('/order/success', 'Frontend\CheckoutController@orderReceived')->name('order.received');

Route::post('return-request', 'Frontend\ReturnController@store')->name('submit_request');

Route::get('return-request/submitted/{order}', 'Frontend\ReturnController@submitted')->name('return.request.submitted');

Route::get('admin/cancellation-requests', 'Backend\CancellationController@index')->name('cancellation_requests');
Route::get('admin/replacements', 'Backend\ReplacementRequestController@index')->name('replacements');

Route::get('admin/replacement/{requestId}', 'Backend\ReplacementRequestController@details');

Route::get('admin/order/cancellation/{cancellation}/details', 'Backend\CancellationController@details')->name('cancellation_details');

Route::get('admin/cancellation-request/{cancellation}/decline', 'Backend\CancellationController@declineForm')->name('cancellation_decline_form');

Route::put('admin/cancellation-request/approve', 'Backend\CancellationController@approveRequest')->name('approve_cancellation');


Route::get('admin/order/return/{returnRequest}/details', 'Backend\ReturnRequestController@details')->name('return_request_details');;

Route::post('search-product', 'Frontend\ProductPageController@searchProduct')->name('search.product');
Route::get('terms-and-conditions','Frontend\PageController@termsAndConditions')->name('terms_and_conditions');

Route::get('return-policy', 'Frontend\PageController@cancelAndReturn')->name('return_policy');

Route::get('search-product', 'Frontend\ProductPageController@searchResult')->name('search.result');

Route::get('admin/bank-account', 'Backend\BankAccountController@index')->name('bank_account');
Route::get('admin/invoice/{invoice}', 'Backend\InvoiceController@viewInvoice');

Route::post('my-account/order/{order}/invoice', 'Frontend\InvoiceController@generateInvoice')->name('customer.invoice');

// Route::get('admin/voucher', 'Backend\VoucherCodeController@index')->name('voucher');

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
Route::get('admin/shipping-rate', 'Backend\ShippingRateController@index')->name('shipping_rate');


Route::get('/admin/user/edit-information', 'Backend\AdminCrudController@editInformation');

Route::put('/admin/order/return-request/complete/{returnRequest}', 'Backend\ReturnRequestController@completeReturnRequest')->name('complete_return_request');

Route::get('email-verified', 'Frontend\CustomerAuthController@customerVerified')->name('customer_verified');

Route::get('my-account/addresses', 'Frontend\CustomerAddressController@index')->name('customer_address');
Route::get('my-account/addresses/create', 'Frontend\CustomerAddressController@create')->name('create_address');
Route::get('my-account/address/edit/{address}', 'Frontend\CustomerAddressController@edit')->name('edit_address');


// orders
Route::post('cart/add', 'Frontend\CartController@addProduct');

// request replacement
Route::get('order/{order}/request-replacement', 'Frontend\ReplacementRequestController@requestReplacement');
Route::get('my-account/replacements', 'Frontend\ReplacementRequestController@index')->name('customer.replacements');
Route::post('replacement/request/store', 'Frontend\ReplacementRequestController@store');
Route::get('replacement-request/submitted', 'Frontend\ReplacementRequestController@submitted')->name('replacement.request.submitted');

// reports pdf
Route::post('admin/report/inventory', 'Backend\ReportsController@generateInventory');
Route::post('admin/report/customer-list', 'Backend\ReportsController@generateCustomer');
Route::post('admin/report/sales', 'Backend\ReportsController@generateSales');
Route::post('admin/report/audit-trail', 'Backend\ReportsController@generateAuditTrail');
Route::post('admin/order/invoice', 'Backend\ReportsController@generateInvoice');
Route::post('admin/report/best-selling', 'Backend\ReportsController@generateBestSelling');

// customer admin details
Route::get('admin/customer/{customerId}', 'Backend\CustomerController@view');

// admin registration new deployment
Route::get('admin/register', 'Backend\AdminAuthController@registrationForm')->name('admin.registration');
Route::get('admin/registration/success', 'Backend\AdminAuthController@registrationSuccess');

// Audit Trail
Route::get('admin/audit-trail', 'Backend\AuditTrailController@index')->name('audit_trail');


// discount 
Route::get('admin/discount', 'Backend\DiscountController@index')->name('discount');

// company details
Route::get('admin/company-details', 'Backend\CompanyDetailsController@index')->name('company_details');