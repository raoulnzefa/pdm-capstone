/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

window.axios = require('axios');

window.Swal = require('sweetalert2');

window.VeeValidate = require('vee-validate');

window.VueBus = require('vue-bus');


import BootstrapVue from 'bootstrap-vue'


import Print from 'vue-print-nb';

import datePicker from 'vue-bootstrap-datetimepicker';

//import 'bootstrap/dist/css/bootstrap.css';
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
Vue.use(datePicker);
// import Editor from '@tinymce/tinymce-vue';

// Vue.use(Editor);
import wysiwyg from "vue-wysiwyg";
Vue.use(wysiwyg, {});

import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)
Vue.use(BootstrapVue);
Vue.use(VueBus);
Vue.use(VeeValidate, { fieldsBagName: 'veeFields' });
Vue.use(Print);

//import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//shipping rate
Vue.component('shipping-rate-index', require('./components/shipping_rate/ShippingRateIndex.vue'));

//Dashboard
Vue.component('dashboard-index', require('./components/dashboard/DashboardIndex.vue'));

//User
Vue.component('user-list', require('./components/user/UserList.vue'));
Vue.component('edit-account', require('./components/user/EditAccount.vue'));
Vue.component('user-name', require('./components/partial/AdminName.vue'));
//Category
Vue.component('category-index', require('./components/category/CategoryIndex.vue'));

//Product
Vue.component('product-list', require('./components/product/ProductIndex.vue'));

// Bank Account Maintenance
Vue.component('bank-account-index', require('./components/bank_account/Index.vue'));


//Customer List
Vue.component('customer-list', require('./components/customer/CustomerList.vue'));
Vue.component('customer-report', require('./components/reports/CustomerList.vue'));
Vue.component('customer-report-component', require('./components/reports/CustomerReportComponent.vue'));

//Best selling
Vue.component('best-selling-report', require('./components/reports/BestSelling.vue'));
Vue.component('best-selling-component', require('./components/reports/BestSellingComponent.vue'));

//Inventory
Vue.component('inventory-list', require('./components/inventory/Index.vue'));
Vue.component('inventory-critical-level', require('./components/inventory/CriticalLevel.vue'));
Vue.component('inventory-out-of-stock', require('./components/inventory/OutOfStock.vue'));

// Reason
Vue.component('reason-index', require('./components/reason/Index.vue'));

//Orders
//Admin
Vue.component('orders-index', require('./components/orders/OrdersIndex.vue'));
Vue.component('shipped-order', require('./components/orders/Shipped.vue'));
Vue.component('view-return-request', require('./components/orders/ReturnRequest.vue'));
Vue.component('view-cancellation-request', require('./components/orders/CancellationRequests.vue'));

// Vue.component('view-order', require('./components/orders/ViewOrder.vue'));
Vue.component('order-details', require('./components/orders/OrderDetails.vue'));
Vue.component('invoices', require('./components/invoice/Invoice.vue'));
Vue.component('view-invoice', require('./components/invoice/ViewInvoice.vue'));
Vue.component('ship-order-form', require('./components/orders/ShipOrderForm.vue'));
Vue.component('invoice-component', require('./components/orders/InvoiceComponent.vue'));

// User
Vue.component('edit-information', require('./components/user/EditInformation.vue'));
// Reports
Vue.component('inventory-report', require('./components/reports/InventoryReport.vue'));
Vue.component('products-report', require('./components/reports/ProductsReport.vue'));
Vue.component('inventory-report-component', require('./components/reports/InventoryReportComponent.vue'));
Vue.component('products-report-component', require('./components/reports/ProductsReportComponent.vue'));
Vue.component('sales-report-component', require('./components/reports/SalesReportComponent.vue'));
Vue.component('stocks-component', require('./components/reports/StocksComponent.vue'));
Vue.component('critical-level-component', require('./components/reports/CriticalLevelComponent.vue'));
Vue.component('out-of-stock-component', require('./components/reports/OutOfStockComponent.vue'));
Vue.component('sales-component', require('./components/reports/Sales.vue'));
// user logs
Vue.component('user-logs', require('./components/user_logs/UserLogsIndex.vue'));
// notification badges
Vue.component('order-badge', require('./components/notification_badges/OrderBadge.vue'));
Vue.component('request-badge', require('./components/notification_badges/RequestBadge.vue'));
Vue.component('inventory-badge', require('./components/notification_badges/InventoryBadge.vue'));
Vue.component('cancel-request-badge', require('./components/notification_badges/CancelRequestBadge.vue'));
Vue.component('return-request-badge', require('./components/notification_badges/ReturnRequestBadge.vue'));
//Frontend
//Customer
Vue.component('registration-form', require('./components/customer/RegistrationForm.vue'));
Vue.component('account-details-form', require('./components/customer/AccountDetailsForm.vue'));
Vue.component('order-status', require('./components/customer/OrderStatus.vue'));
Vue.component('order-history', require('./components/customer/OrderHistory.vue'));
Vue.component('completed-orders', require('./components/customer/CompletedOrders.vue'));
Vue.component('order-invoice', require('./components/customer/OrderInvoice.vue'));
Vue.component('change-pass', require('./components/customer/ChangePassword.vue'));
Vue.component('cancel-order', require('./components/customer/CancelOrderRequest.vue'));
Vue.component('customer-cancellation-list', require('./components/customer/CancellationList.vue'));
Vue.component('customer-cancelled-orders', require('./components/customer/CancelledOrders.vue'));
Vue.component('customer-invoice', require('./components/customer/Invoice.vue'));
Vue.component('upload-deposit-slip', require('./components/bank_deposit_slip/UploadBankDepositSlip.vue'));

// my account sidebar notification
Vue.component('cancel-request-notif', require('./components/notification_badges/CustCancelRequestBell.vue'));

// customer auth
Vue.component('customer-login', require('./components/auth/CustomerLogin.vue'));
Vue.component('customer-forgot-password', require('./components/auth/CustomerForgotPassword.vue'));
Vue.component('customer-reset-password', require('./components/auth/CustomerResetPassword.vue'));

//Partial Shop
Vue.component('add-cart-page', require('./components/partial_shop/AddCartButtonPage.vue'));
Vue.component('add-cart-view', require('./components/partial_shop/AddCartButtonView.vue'));
Vue.component('cart-quantity', require('./components/partial_shop/CartQuantity.vue'));
Vue.component('customer-name', require('./components/partial_shop/CustomerName.vue'));
Vue.component('cart', require('./components/partial_shop/Cart.vue'));
Vue.component('checkout-page', require('./components/partial_shop/CheckoutPage.vue'));
Vue.component('payment-method', require('./components/partial_shop/PaymentMethod.vue'));
Vue.component('customer-address', require('./components/partial_shop/CustomerAddress.vue'));
Vue.component('featured-product', require('./components/partial_shop/FeaturedProduct.vue'));
Vue.component('product-page', require('./components/partial_shop/ProductPage.vue'));
Vue.component('product-by-category', require('./components/partial_shop/ProductByCategory.vue'));
Vue.component('related-products', require('./components/partial_shop/RelatedProducts.vue'));
Vue.component('view-product', require('./components/partial_shop/ViewProduct.vue'));

// Return
Vue.component('return-request-form', require('./components/return_request/ReturnRequestForm.vue'));
//Sales
Vue.component('sales-index', require('./components/sales/Index.vue'));

// customer address
Vue.component('create-customer-address', require('./components/address/CreateAddress.vue'));
Vue.component('index-customer-address', require('./components/address/AddressIndex.vue'));
Vue.component('edit-customer-address', require('./components/address/EditAddress.vue'));

// Orders admin page
Vue.component('orders-for-pickup', require('./components/orders/OrdersForPickup.vue'));
Vue.component('orders-pending-payment', require('./components/orders/OrdersPendingPayment.vue'));
Vue.component('orders-processing', require('./components/orders/OrdersProcessing.vue'));
Vue.component('orders-delivered', require('./components/orders/OrdersDelivered.vue'));
Vue.component('orders-completed', require('./components/orders/OrdersCompleted.vue'));

//Order details
Vue.component('view-bank-deposit-slip', require('./components/orders/ViewBankDepositSlip.vue'));
Vue.component('deliver-order', require('./components/orders/DeliverOrder.vue'));
Vue.component('mark-as-completed', require('./components/orders/MarkAsCompleted.vue'));
Vue.component('picked-up-order', require('./components/orders/PickedupOrder.vue'));

// Order for Delivery
Vue.component('order-delivery-index', require('./components/orders_delivery/DeliveryIndex.vue'));

// customer order details
Vue.component('customer-receive-order', require('./components/orders/CustomerReceiveOrder.vue'));

// cancelorder by customer
Vue.component('cancel-order-by-customer', require('./components/orders/CancelOrderByCustomer.vue'));

// product add stock
Vue.component('add-stock', require('./components/product/AddStock.vue'));

Vue.component('add-product-with-variants', require('./components/product/AddProductWithVariants.vue'));
Vue.component('add-product-no-variant', require('./components/product/AddProductNoVariants.vue'));

Vue.component('product-with-variants', require('./components/product/ProductWithVariants.vue'));


Vue.component('edit-catalog-with-variants', require('./components/product/EditProductWithVariant.vue'));
Vue.component('edit-catalog-no-variants', require('./components/product/EditProductNoVariant.vue'));

// voucher code
Vue.component('voucher-code-index', require('./components/voucher_code/VoucherCodeIndex.vue'));
//Setting

const app = new Vue({
    el: '#app'
});
