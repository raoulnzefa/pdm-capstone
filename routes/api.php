<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
// Admin
Route::post('admin/create', 'Api\AdminController@store');
Route::post('admin/update/{admin}', 'Api\AdminController@updateUser');

Route::put('admin/change-password/{admin}', 'Api\AdminController@changePass');
Route::put('admin/edit-account/{admin}', 'Api\AdminController@updateAccount');
Route::get('admin/name/{admin}', 'Api\AdminController@adminName');
Route::put('admin/change-status/{status}/id/{admin}', 'Api\AdminController@changeStatus');
Route::put('admin/change-role/{role}/id/{admin}', 'Api\AdminController@changeRole');
Route::get('admin/list', 'Api\AdminController@list');

// Category
Route::get('category/list', 'Api\CategoryController@list');
Route::resource('category', 'Api\CategoryController');

//Product
Route::post('product/create-no-variant', 'Api\ProductController@createNoVariant');
Route::post('product/update-no-variant/{product}', 'Api\ProductController@updateNoVariant');

Route::post('product/create-with-variant', 'Api\ProductController@createWithVariant');
Route::post('product/update-with-variant/{product}', 'Api\ProductController@updateWithVariant');

Route::post('product/catalog/{product}', 'Api\ProductController@updateProductCatalog');

Route::get('products', 'Api\ProductController@productsTable');
Route::put('product/add-stock/{product}', 'Api\ProductController@addStock');
//Admin Side
//Customer list
Route::get('/customers', 'Api\CustomerController@list');
// Order not view
Route::get('order/not-view', 'Api\OrderController@orderNotView');
Route::put('order/viewed/{order}', 'Api\OrderController@updateOrderViewed');

//Customer Registration
//Route::post('register', 'Api\CustomerController@register');
Route::post('customer/{customer}', 'Api\CustomerController@update');
Route::get('customer/name/{customer}', 'Api\CustomerController@customerName');

//Cart
Route::post('cart/add-cart', 'Api\CartController@addCartPage');
Route::get('cart/quantity/{customer}', 'Api\CartController@cartQuantity');
Route::get('cart/products/{customer}', 'Api\CartController@customerCart');
Route::put('cart/update/{cart}', 'Api\CartController@updateQuantity');
Route::delete('cart/remove/{cart}', 'Api\CartController@removeProduct');
Route::get('cart/total-details/{customer}', 'Api\CartController@cartTotalDetails');
Route::post('cart/update', 'Api\CartController@updateCart');


//Invoice
Route::post('invoice', 'Api\InvoiceController@createInvoice');
Route::get('invoice/order/{order}', 'Api\InvoiceController@getInvoiceOrder');
Route::get('invoice/list', 'Api\InvoiceController@invoiceList');
Route::put('invoice/send/{invoice}', 'Api\InvoiceController@sendInvoice');
Route::put('invoice/status/{invoice}', 'Api\InvoiceController@updateStatus');



//Product page website
Route::get('featured-product', 'Api\ProductController@featuredProducts');
Route::get('product-page', 'Api\ProductController@productPage');
Route::get('product-by-category/{category}', 'Api\ProductController@productByCategory');
Route::get('product-by-type/{type}', 'Api\ProductController@productByType');

Route::put('customer/change-password/{customer}', 'Api\CustomerController@changePassword');
//Route::put('order/ship/{order}', 'Api\OrderController@shipOrder');
//Route::put('order/{order}/send-shipment-email', 'Api\OrderController@sendShipmentEmail');

// Route::post('ship-order', 'Api\OrderController@shipOrder');
Route::post('ship-order', 'Api\OrderController@saveShipOrder');
Route::put('order/{order}/received-payment', 'Api\OrderController@receivedPayment');

Route::get('orders', 'Api\OrderController@viewOrders');
Route::get('order/details/{order}', 'Api\OrderController@orderDetails');
Route::get('order-status/{customer}', 'Api\OrderController@customerOrderStatus');
Route::get('customer/order/history/{customer}', 'Api\OrderController@customerOrderHistory');
Route::get('order/{order}/invoice', 'Api\OrderController@orderInvoice');
Route::put('order/cancel/{order}', 'Api\OrderController@cancelOrder');


Route::post('cancel-order', 'Api\CancelOrderController@store');
Route::get('cancel-order/{cancelOrder}', 'Api\CancelOrderController@get');

Route::post('cancel-product-qty', 'Api\CancelOrderController@updateCancelQty');
Route::post('order/payment-received', 'Api\OrderController@markAsPaid');
Route::post('order/complete-order', 'Api\OrderController@completeOrder');

Route::get('sales', 'Api\SalesController@salesOrder');

Route::get('provinces', 'Api\ProvinceController@list');

Route::get('municipalities/province/{province}', 'Api\MunicipalityController@list');

Route::get('barangays/municipal/{municipal}', 'Api\BarangayController@list');


// Shipping Company

Route::get('orders/today', 'Api\OrderController@ordersToday');

Route::post('reason', 'Api\ReasonController@store');
Route::put('reason/{reason}', 'Api\ReasonController@update');
Route::get('reason/list', 'Api\ReasonController@list');
Route::get('reason/return', 'Api\ReasonController@returnReasons');
Route::get('reason/cancellation', 'Api\ReasonController@cancellationReasons');


Route::put('order/received/{order}', 'Api\OrderController@orderReceived');

Route::get('orders/cancellation-requests', 'Api\CancellationController@cancellationList');
Route::get('orders/cancellation-requests/{customer}', 'Api\CancellationController@customerCancellationList');
// Route::post('cancellation', 'Api\CancellationController@storeCancellation');
// Route::put('cancellation/{cancellation}', 'Api\CancellationController@updateStatus');
// Route::get('cancellation/{cancellation}', 'Api\CancellationController@getDetails');
Route::post('cancel-request', 'Api\CancelOrderRequestController@storeCancelRequest');

Route::get('customer/cancelled-orders/{customer}', 'Api\OrderController@customerCancelledOrders');


Route::post('return-request', 'Api\ReturnRequestController@storeReturnRequest');
Route::get('return-request/list', 'Api\ReturnRequestController@returnRequestList');
Route::get('return-request/{return_request}', 'Api\ReturnRequestController@returnRequestDetails');
Route::put('return-request/{return_request}', 'Api\ReturnRequestController@updateReturnRequestStatus');

Route::put('return-request/replacement/{return_request}', 'Api\ReturnRequestController@createReplacement');
Route::get('replacement-products/{return_request}', 'Api\ReturnRequestController@replacementProducts');

Route::get('product/{product}/related', 'Api\ProductController@relatedProducts');

Route::get('best-selling', 'Api\OrderController@bestSellingProducts');


Route::put('order/send-email-pending-status/{order}', 'Api\OrderController@sendEmailForPending');

Route::get('invoice/details/{invoice}', 'Api\InvoiceController@invoiceDetails');

Route::get('cancel-request/list', 'Api\CancelOrderRequestController@cancelRequestList');
Route::get('cancel-request/{cancel}', 'Api\CancelOrderRequestController@cancelRequestDetails');
Route::put('cancel-request/{cancel}', 'Api\CancelOrderRequestController@updateCancelStatus');
Route::get('cancel-request/{customer}/list', 'Api\CancelOrderRequestController@customerCancelRequestList');

Route::get('product-sales', 'Api\SalesController@salesReport');
Route::get('returned-product-report', 'Api\ReturnProductController@reportReturnProduct');
Route::get('best-selling-products', 'Api\InvoiceController@bestSellingProduct');

Route::post('search-sales', 'Api\SalesController@searchSales');

Route::post('save-replacement/{order}', 'Api\ReturnProductController@saveReplacementProduct');
Route::get('number-of-customers', 'Api\CustomerController@numberOfCustomers');


Route::post('bank-account', 'Api\BankAccountController@store');
Route::put('bank-account/{bank_account}', 'Api\BankAccountController@update');
Route::get('bank-accounts', 'Api\BankAccountController@get');
Route::delete('bank-account/{bank_account}/{admin}', 'Api\BankAccountController@deleteBankAccount');


Route::delete('product/delete/{product}', 'Api\ProductController@delete');
Route::post('replacement-request/{order}', 'Api\ReplacementRequestController@saveReplacementRequest');
Route::put('cancellation/withdrawal/{order}', 'Api\CancelOrderRequestController@withdrawCancellation');
Route::get('invoices', 'Api\InvoiceController@index');
Route::get('invoice/{invoice}', 'Api\InvoiceController@viewInvoice');


Route::get('requests/not-view', 'Api\RequestController@getRequestNotViewed');
Route::get('cancellation-request/not-view', 'Api\CancelOrderRequestController@requestNotViewed');

Route::get('cancellation-request/viewed/{cancel}', 'Api\CancelOrderRequestController@updateViewedStatus');

Route::get('return/request/not-view', 'Api\ReturnRequestController@returnNotViewed');
Route::put('return/request/viewed/{return}', 'Api\ReturnRequestController@updateReturnViewStatus');
Route::post('search-user-logs', 'Api\UserLogsController@searchUserLogs');

Route::get('total-summary/{customer}/{checkout_id}', 'Api\CheckoutDetailController@totalSummary');

Route::put('bank-account/default/{bankAccount}', 'Api\BankAccountController@setAsDefault');

Route::get('cancellation-notif', 'Api\CustomerNotificationController@cancellationNotif');

Route::post('confirm-bank-deposit-slip', 'Api\BankDepositSlipController@confirmBankDepositSlip');

Route::get('get-shipping-rate', 'Api\ShippingRateController@getShippingRate');
Route::post('set-shipping-rate', 'Api\ShippingRateController@setShippingRate');
Route::put('update-shipping-rate/{shippingRate}', 'Api\ShippingRateController@updateShippingRate');
Route::get('user/edit-information/{admin}', 'Api\AdminController@editInformation');
Route::put('update-user-info/{admin}', 'Api\AdminController@updateInformation');

Route::get('customer-report', 'Api\CustomerController@customerReport');
Route::post('return-request/order', 'Api\ReturnRequestApi@saveReturnRequest');

Route::post('get-best-selling-report', 'Api\BestSellingController@getBestSelling');

Route::post('address/save', 'Api\AddressController@saveAddress');
Route::put('address/update/{address}', 'Api\AddressController@updateAddress');
Route::delete('address/delete/{address}', 'Api\AddressController@deleteAddress');

// admin orders pages
Route::get('orders/for-pickup', 'Api\OrderController@forPickup');
Route::get('orders/pending-payment', 'Api\OrderController@pendingPayment');
Route::get('orders/processing', 'Api\OrderController@processing');
Route::get('orders/delivered', 'Api\OrderController@delivered');
Route::get('orders/completed', 'Api\OrderController@completed');

// orders details admin side
Route::put('order/mark-as-paid/{order}', 'Api\OrderController@markAsPaid');
Route::put('order/deliver-order/{order}', 'Api\OrderController@deliverOrder');
Route::put('order/mark-as-completed/{order}', 'Api\OrderController@markAsCompleted');
Route::put('order/picked-up/{order}', 'Api\OrderController@pickedUpOrder');

// customer order details
Route::put('order/receive-order/{order}', 'Api\OrderController@customerReceiveOrder');

//customer cancel order no payment
Route::put('order/cancel-order-by-customer/{order}', 'Api\OrderController@cancelOrderByCustomer');