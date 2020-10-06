<template>
	<div class="row">
		<div class="col-md-12">
			<div class="clearfix mb-4 mt-4">
				<h2 class="float-left">Customer Details</h2>
				<a href="/admin/customers" class="btn btn-outline-secondary float-right">Back</a>
			</div>
			<div class="card mb-4">
				<div class="card-header">
					<h4 class="mb-0">Basic Information</h4>
				</div>
				<div class="card-body pt-5">
					<template v-if="loading">
						<center>
							<half-circle-spinner
                         :animation-duration="1000"
                         :size="30"
                         color="#ff1d5e"
                       />
						</center>
					</template>
					<template v-else>
						<!-- basic info here -->
						<div class="form-group row">
							<label class="col-form-label col-sm-3">Firstname:</label>
							<div class="col-md-9">
								<input type="text" class="form-control" :value="basicInfo.first_name" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-3">Lastname:</label>
							<div class="col-md-9">
								<input type="text" class="form-control" :value="basicInfo.last_name" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-3">Email:</label>
							<div class="col-md-9">
								<input type="text" class="form-control" :value="basicInfo.email" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-3">Status:</label>
							<div class="col-md-9">
								<input type="text" class="form-control" :value="basicInfo.status" readonly>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-3">Date Registered:</label>
							<div class="col-md-9">
								<input type="text" class="form-control" :value="basicInfo.registered" readonly>
							</div>
						</div>
					</template>
				</div>
			</div><!-- card basic info -->
			<div class="card mb-4">
				<div class="card-header">
					<h4 class="mb-0">Addresses</h4>
				</div>
				<div class="card-body pt-5">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Address</th>
							</tr>
						</thead>
						<tbody>
							<template v-if="loading">
							<tr>
								<td colspan="3">
									<center>
										<half-circle-spinner
			                         :animation-duration="1000"
			                         :size="30"
			                         color="#ff1d5e"
			                       />
									</center>
								</td>
							</tr>
							</template>
							<template v-else>
								<template v-if="addresses.length">
									<tr v-for="(address, index) in addresses" :key="index">
										<td>{{ address.id }}</td>
										<td>{{ address.address_name}}</td>
										<td>{{ address.street+', '+address.barangay+', '+address.municipality+', '+address.province+', '+address.zip_code }}</td>
									</tr>
								</template>
								<template v-else>
									<tr>
										<td colspan="3" align="center">
											No address.
										</td>
									</tr>
								</template>
							</template>
						</tbody>
					</table>
				</div>
			</div><!-- card addresses -->
			<div class="card mb-4">
				<div class="card-header">
					<h4 class="mb-0">Orders</h4>
				</div>
				<div class="card-body pt-5">
					<table class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>Number</th>
								<th>Date</th>
								<th>Status</th>
								<th>Qty.</th>
								<th>Total</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<template v-if="loading">
							<tr>
								<td colspan="6">
									<center>
										<half-circle-spinner
			                         :animation-duration="1000"
			                         :size="30"
			                         color="#ff1d5e"
			                       />
									</center>
								</td>
							</tr>
							</template>
							<template v-else>
								<template v-if="orders.length">
									<tr v-for="(order, index) in orders" :key="index">
										<td>{{ order.number }}</td>
										<td>{{ order.order_created }}</td>
										<td>
											<span class="badge badge-info" style="font-size: 14px;" v-if="order.order_status == 'For pickup'">{{ order.order_status }}</span>
				                    <span class="badge badge-warning" style="font-size: 14px;" v-if="order.order_status == 'Pending payment'">{{ order.order_status }}</span>
				                    <span class="badge badge-primary" style="font-size: 14px;" v-if="order.order_status == 'Processing'">{{ order.order_status }}</span>
				                    <span class="badge badge-for-shipping" style="font-size: 14px;" v-if="order.order_status == 'For shipping'">{{ order.order_status }}</span>
				                    <span class="badge badge-secondary" style="font-size: 14px;" v-if="order.order_status == 'Shipped'">{{ order.order_status }}</span>
				                    <span class="badge badge-success" style="font-size: 14px;" v-if="order.order_status == 'Completed'">{{ order.order_status }}</span>
				                    <span class="badge badge-danger" style="font-size: 14px;" v-if="order.order_status == 'Cancelled'">{{ order.order_status }}</span>
				                    <span class="badge badge-danger" style="font-size: 14px;" v-if="order.order_status == 'Overdue'">{{ order.order_status }}</span>
										</td>
										<td>{{ order.order_quantity }}</td>
										<td>{{ order.order_total }}</td>
										<td align="center">
											<button class="btn btn-sm btn-primary" @click="viewOrderDetails(order)">Details</button>
										</td>
									</tr>
								</template>
								<template v-else>
									<tr>
										<td colspan="6" align="center">
											No orders.
										</td>
									</tr>
								</template>
							</template>
						</tbody>
					</table>
				</div>
			</div><!-- card orders -->

			<!-- Modal Component -->
        <b-modal id="customerOrderModal"
                 ref="customerOrderModal"
                 title="Order Details"
                 no-close-on-backdrop
                 no-close-on-esc
                 hide-header-close
                 ok-only
                 ok-title="Close"
                 ok-variant="danger"
                 size="lg"
                 @ok="closeModal">
           	<div>
           		<h5>Order Number: {{ orderNumber }}</h5>
           		<div class="row mt-3">
						<div class="col-md-6">
							<table class="table order-table">
								<tr>
									<td width="40%">Placed on:</td>
									<td>{{ orderCreated }}</td>
								</tr>
								<tr>
									<td>Total:</td>
									<td>&#8369;{{ orderTotal }}</td>
								</tr>
								<tr>
									<td width="50%">Shipping Method:</td>
									<td>{{ orderShippingMethod }}</td>
								</tr>
								<tr>
									<td>Payment Method:</td>
									<td>{{ orderPaymentMethod }}</td>
								</tr>
								<tr>
									<td>Payment Status:</td>
									<td>{{ orderPaymentStatus }}</td>
								</tr>
								<tr>
									<td>Status:</td>
									<td>{{ orderStatus }}</td>
								</tr>
								<tr v-if="orderStatus === 'Shipped' || orderStatus === 'Completed'">
									<td>Warranty date:</td>
									<td>{{ orderWarranty }}</td>
								</tr>
							</table>
						</div>
						<div class="col-md-6">
							<table class="table order-table" v-if="orderShippingMethod === 'Shipping'">
								<tr>
									<th class="text-right">Shipping Address</th>
								</tr>
								<tr>
									<td align="right">{{ shippingCustomerName }}</td>
								</tr>
								<tr>
									
									<td align="right">{{ shippingStreet }}</td>
								</tr>
								<tr>
									
									<td align="right">{{ shippingAddress }}</td>
								</tr>
								<tr>
									<td align="right">{{ shippingMobileNo }}</td>
								</tr>
							</table>
						</div>
					</div><!-- row -->
					<div class="mt-4">
						<h5 class="mb-3">Ordered Products</h5>
						<table class="table table-striped mb-0">
							<thead>
								<tr>
									<th width="50%">Product(s)</th>
									<th>Price</th>
									<th>Quantity</th>
									<th>Amount</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(orderProd,index) in orderProducts" :key="index">
									<td>
										<img :src="'/storage/products/'+orderProd.inventory.product.product_image" class="img-fluid" width="20%" height="13%">
										<span class="align-middle">{{ orderProd.product_name }}</span>
									</td>
									<td class="align-middle">&#8369;{{ orderProd.price }}</td>
									<td class="align-middle">{{ orderProd.quantity }}</td>
									<td class="align-middle">&#8369;{{ orderProd.total }}</td>
								</tr>
							</tbody>
						</table>
						<table class="table mb-4">
							<tr>
								<th class="text-right">Subtotal:</th>
								<td width="30%" class="text-right">&#8369;{{ orderSubtotal }}</td>
							</tr>
							<tr v-if="orderShippingMethod === 'Shipping'">
								<th class="text-right">Shipping fee:</th>
								<td class="text-right">&#8369;{{ orderShippingFee }}</td>
							</tr>
							<tr>
								<th class="text-right">Total:</th>
								<td class="text-right">&#8369;{{ orderTotal }}</td>
							</tr>
						</table>
					</div>
           	</div>
          </b-modal>
		</div>
	</div>
</template>

<script>
	import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		props: ['admin', 'customer_id'],
		data() {
			return {
				basicInfo: [],
				addresses: [],
				orders: [],
				loading: false,
				orderNumber: '',
				orderCreated: '',
				orderSubtotal: '',
				orderShippingFee: '',
				orderTotal: '',
				orderShippingMethod: '',
				orderPaymentStatus: '',
				orderStatus: '',
				orderPaymentMethod: '',
				orderWarranty: '',
				shippingCustomerName: '',
				shippingStreet: '',
				shippingAddress: '',
				shippingMobileNo: '',
				orderProducts: [],
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getCustomerDetails() {
				this.loading = true;
				axios.get('/api/customer/'+this.customer_id)
				.then(response => {
					this.loading = false;
					this.basicInfo = response.data.basic_info;
					this.addresses = response.data.addresses;
					this.orders = response.data.orders;
				})
				.catch(error => {
					this.loading = false;
					console.log(error.response);
				})
			},
			viewOrderDetails(order) {
				this.orderNumber = order.number;
				this.orderCreated = order.order_created;
				this.orderTotal = order.order_total;
				this.orderShippingMethod = order.order_shipping_method;
				this.orderPaymentStatus = order.order_payment_method;
				this.orderStatus = order.order_status;
				this.orderPaymentMethod = order.order_payment_method;
				this.orderWarranty = order.order_warranty;
				this.shippingCustomerName = order.shipping.shipping_firstname+' '+order.shipping.shipping_lastname;
				this.shippingStreet = order.shipping.shipping_street; 
				this.shippingAddress = order.shipping.shipping_barangay+', '+order.shipping.shipping_municipality+', '+order.shipping.shipping_province+', '+order.shipping.shipping_zip_code
				this.shippingMobileNo = order.shipping.shipping_mobile_no;
				this.orderSubtotal = order.order_subtotal;
				this.orderShippingFee = order.order_shipping_fee;
				this.orderProducts = order.order_products;
				this.$refs.customerOrderModal.show();
			},
			closeModal() {
				this.orderNumber = '';
				this.orderCreated = '';
				this.orderTotal = '';
				this.orderShippingMethod = '';
				this.orderPaymentStatus = '';
				this.orderStatus = '';
				this.orderPaymentMethod = '';
				this.orderWarranty = '';
				this.shippingCustomerName = '';
				this.shippingStreet = ''; 
				this.shippingAddress = '';
				this.shippingMobileNo = '';
				this.orderSubtotal = '';
				this.orderShippingFee = '';
				this.orderProducts = '';
				this.$refs.customerOrderModal.show();
			}
		},
		mounted() {
			this.getCustomerDetails();
		}
	}
</script>