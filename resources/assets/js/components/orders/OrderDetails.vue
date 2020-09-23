<template>
<div class="card mb-5">
	<div class="card-header clearfix">
		<h3 class="mb-0 float-left">Order Details</h3>
		<a href="/admin/orders" class="float-right btn btn-outline-secondary">Back</a>
	</div>
	<div class="card-body pt-4">
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
			<template v-if="order.order_shipping_method === 'Shipping'">
				<template v-if="order.order_payment_method === 'Bank Deposit'">
					<div class="clearfix" v-if="order.order_status == 'Pending payment'">
						<h4 class="float-left mb-0 mt-1">Order No. {{order.number}}</h4>
						<view-bank-deposit-slip 
							:order="order"
							:admin="admin"></view-bank-deposit-slip>
					</div>
					<div class="clearfix" v-if="order.order_status === 'Processing'">
						<h4 class="float-left mb-0 mt-1">Order No. {{order.number}}</h4>
						<mark-as-for-shipping
							:order="order"
							:admin="admin">
						</mark-as-for-shipping>
					</div>
				</template>
				<template v-if="order.order_payment_method === 'PayPal'">
					<div class="clearfix" v-if="order.order_status === 'Processing'">
						<h4 class="float-left mb-0 mt-1">Order No. {{order.number}}</h4>
						<mark-as-for-shipping
							:order="order"
							:admin="admin">
						</mark-as-for-shipping>
					</div>
				</template>
				<template v-if="order.order_status === 'For shipping'">
					<div class="clearfix">
						<h4 class="float-left mb-0 mt-1">Order No. {{order.number}}</h4>
						<deliver-order
							:order="order"
							:admin="admin">
						</deliver-order>
					</div>
				</template>
				<template v-if="order.order_status === 'Shipped'">
					<div class="clearfix">
						<h4 class="float-left mb-0 mt-1">Order No. {{order.number}}</h4>
					</div>
				</template>
		</template>
		<template v-else><!-- if store pickup -->
			<template v-if="order.order_status === 'For pickup'">
				<div class="clearfix">
					<h4 class="float-left mb-0 mt-1">Order No. {{order.number}}</h4>
					<picked-up-order
						:order="order"
						:admin="admin"></picked-up-order>
				</div>
			</template>
		</template>
		<template v-if="order.order_status === 'Overdue'">
			<div class="clearfix">
				<h4 class="float-left mb-0 mt-1">Order No. {{order.number}}</h4>
			</div>
		</template>
		<template v-if="order.order_status === 'Cancelled'">
			<div class="clearfix">
				<h4 class="float-left mb-0">Order No. {{order.number}}</h4>
				<!-- Invoice -->
			</div>
		</template>
		<template v-if="order.order_status === 'Completed'">
			<div class="clearfix">
				<h4 class="float-left mb-0 mt-1">Order No. {{order.number}}</h4>
				<!-- Invoice -->
				<form @submit.prevent="generateInvoice" method="POST" target="_blank" ref="invoiceOrderDetails" action="/admin/order/invoice">
					<input type="hidden" name="_token" :value="csrf">
					<input type="hidden" name="order_number" :value="order.number">
					<input type="hidden" name="admin_id" :value="admin.id">
					<button type="submit" class="btn btn-primary float-right">Invoice</button>
				</form>
			</div>
		</template>
			<!-- /header card --> 
			<div class="row mt-3">
				<div class="col-md-6">
					<table class="table order-table">
						<tr>
							<td width="40%">Placed on:</td>
							<td>{{ order.order_created }}</td>
						</tr>
						<tr>
							<td>Total:</td>
							<td>&#8369;{{ order.order_total }}</td>
						</tr>
						<tr>
							<td>Shipping Method:</td>
							<td>{{ order.order_shipping_method }}</td>
						</tr>
						<tr>
							<td>Payment Method:</td>
							<td>{{ order.order_payment_method }}</td>
						</tr>
						<tr>
							<td>Payment Status:</td>
							<td>{{ order.order_payment_status }}</td>
						</tr>
						<tr>
							<td>Status:</td>
							<td>
								 <span class="badge badge-info" style="font-size: 16px;" v-if="order.order_status == 'For pickup'">{{ order.order_status }}</span>
		                    <span class="badge badge-warning" style="font-size: 16px;" v-if="order.order_status == 'Pending payment'">{{ order.order_status }}</span>
		                    <span class="badge badge-primary" style="font-size: 16px;" v-if="order.order_status == 'Processing'">{{ order.order_status }}</span>
		                     <span class="badge badge-primary" style="font-size: 16px;" v-if="order.order_status == 'For shipping'">{{ order.order_status }}</span>
		                    <span class="badge badge-secondary" style="font-size: 16px;" v-if="order.order_status == 'Shipped'">{{ order.order_status }}</span>
		                    <span class="badge badge-success" style="font-size: 16px;" v-if="order.order_status == 'Completed'">{{ order.order_status }}</span>
		                    <span class="badge badge-danger" style="font-size: 16px;" v-if="order.order_status == 'Cancelled'">{{ order.order_status }}</span>
		                    <span class="badge badge-danger" style="font-size: 16px;" v-if="order.order_status == 'Overdue'">{{ order.order_status }}</span>
							</td>
						</tr>
						<tr v-if="order.order_status === 'Pending payment'">
							<td>Due payment date:</td>
							<td>{{ order.order_due_payment }}</td>
						</tr>
						<tr v-if="order.order_status === 'Overdue'">
							<td>Remarks:</td>
							<td>{{ order.order_remarks }}</td>
						</tr>
						<tr v-if="order.order_status === 'For pickup'">
							<td>Pickup date until:</td>
							<td>{{ order.order_for_pickup }}</td>
						</tr>
						<tr v-if="order.order_status === 'Shipped' || order.order_status === 'Completed'">
							<td>Warranty date:</td>
							<td>{{ order.order_warranty }}</td>
						</tr>
					</table>
				</div>
				<div class="col-md-6">
					<table class="table order-table" v-if="order.order_shipping_method === 'Shipping'">
						<tr>
							<th class="text-right">Shipping Address</th>
						</tr>
						<tr>
							<td align="right">{{ order.shipping.shipping_firstname+' '+order.shipping.shipping_lastname }}</td>
						</tr>
						<tr>
							
							<td align="right">{{ order.shipping.shipping_street }}</td>
						</tr>
						<tr>
							
							<td align="right">{{ order.shipping.shipping_barangay+', '+order.shipping.shipping_municipality+', '+order.shipping.shipping_province+', '+order.shipping.shipping_zip_code }}</td>
						</tr>
						<tr>
							<td align="right">{{ order.shipping.shipping_mobile_no }}</td>
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
							<th>Total Price</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(orderProd,index) in order.order_products" :key="index">
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
						<td width="30%" class="text-right">&#8369;{{ order.order_subtotal }}</td>
					</tr>
					<tr>
						<th class="text-right">Discount:</th>
						<td width="30%" class="text-right">&#8369;{{ order.order_discount }}</td>
					</tr>
					<tr v-if="order.order_shipping_method === 'Shipping'">
						<th class="text-right">Shipping fee:</th>
						<td class="text-right">&#8369;{{ order.order_shipping_fee }}</td>
					</tr>
					<tr>
						<th class="text-right">Total:</th>
						<td class="text-right">&#8369;{{ order.order_total }}</td>
					</tr>
				</table>
			</div>
		</template>
	</div>
</div><!-- card -->
</template>
<script>
import { HalfCircleSpinner } from 'epic-spinners'

export default {
	props: ['order_num', 'admin'],
	data() {
		return {
			order: [],
			loading: false,
			csrf: document.head.querySelector('meta[name="csrf-token"]').content,
		}
	},
	components:{
		HalfCircleSpinner
	},
	methods: {
		getOrder() {
			this.loading = true;
			axios.get('/api/order/'+this.order_num)
			.then(response => {
				this.loading = false;
				this.order = response.data;
			})
			.catch(error => {
				this.loading = false;
				console.log(error.response);
			})
		},
		generateInvoice() {
			this.$refs.invoiceOrderDetails.submit();
		}
	},
	mounted() {
		this.getOrder();
		this.$bus.$on('refreshOrderDetails', data => {
			if (data == true)
			{
				this.getOrder();
			}
		})
	}

}
</script>
