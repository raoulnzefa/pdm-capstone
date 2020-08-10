<template>
<div class="row justify-content-center">
	<div class="col-md-8">
		<div class="clearfix mb-2">
			<a :href="previous_url" class="d-block mb-2 float-left text-secondary"><i class="fa fa-chevron-left"></i>&nbsp;Back</a>
			<button class="btn btn-dark float-right" v-print="'#printInvoice'"><i class="fa fa-print"></i>&nbsp;Print</button>
		</div>
		<div class="card mb-4">
			<div class="card-body">
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
					<div class="clearfix" style="margin-bottom: 50px;">	
						<img src="/images/logo.jpg" class="float-left" width="220" height="120">
						
						<div style="width: 600px;" class="float-right">
							<table class="table order-table">
								<tr>
									<td align="right"><b>INFINITY FIGHTGEAR</b></td>
								</tr>
								<tr>
									<td align="right">Bunlo, Mac Arthur Hi-way, 2500 Bocaue, Bulacan</td>
								</tr>
								<tr>
									<td align="right">09987901118</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="clearfix mb-5">
						<div class="float-left" style="width: 35%;">
							<table class="table order-table">
								<tr>
									<th>Customer</th>
								</tr>
								<tr>
									<td></td>
								</tr>
								<tr>
									<td>{{ invoice_data.order.customer.first_name+' '+invoice_data.order.customer.last_name }}</td>
								</tr>
								<tr>
									<td>{{ invoice_data.order.customer.street }}</td>
								</tr>
								<tr>
									<td>{{ invoice_data.order.customer.barangay+', '+invoice_data.order.customer.municipality+', '+invoice_data.order.customer.province+', '+invoice_data.order.customer.zip_code }}</td>
								</tr>
								<tr v-if="invoice_data.order.customer.company != null">
									<td>{{ invoice_data.order.customer.company }}</td>
								</tr>
								<tr>
									<td>{{ invoice_data.order.customer.phone_number }}</td>
								</tr>
							</table>
						</div>
						<div class="float-left" style="width:30%">
							<table class="table order-table">
								<tr>
									<th>Shipping Address</th>
								</tr>
								<tr>
									<td>{{ invoice_data.order.first_name+' '+invoice_data.order.last_name }}</td>
								</tr>
								<tr>
									<td>{{ invoice_data.order.street }}</td>
								</tr>
								<tr>
									<td>{{ invoice_data.order.barangay+', '+invoice_data.order.municipal+', '+invoice_data.order.province+', '+invoice_data.order.zip_code }}</td>
								</tr>
								<tr v-if="invoice_data.order.company != null">
									<td>{{ invoice_data.order.company }}</td>
								</tr>
								<tr>
									<td>{{ invoice_data.order.phone_no }}</td>
								</tr>
							</table>
						</div>
						<div class="float-right" style="width: 30%;">
							<table class="table order-table">
								<tr>
									<th width="50%">Invoice Number:</th>
									<td class="text-right font-weight-bold" width="">{{ invoice_data.number }}</td>
								</tr>
								<tr>
									<td>Invoice Date:</td>
									<td class="text-right">{{ invoice_data.created }}</td>
								</tr>
								<tr>
									<td>Order Number:</td>
									<td class="text-right">{{ invoice_data.order_number }}</td>
								</tr>
								<tr>
									<td>Shipping Method</td>
									<td class="text-right">{{ invoice_data.order.shipping_method }}</td>
								</tr>
								<tr>
									<td>Payment Method</td>
									<td class="text-right">{{ invoice_data.order.payment_method }}</td>
								</tr>
								<tr>
									<td>Order Total:</td>
									<td class="text-right">&#8369;{{ invoice_data.total }}</td>
								</tr>
								<tr v-if="invoice_data.status =='Void'">
									<td>Invoice Status:</td>
									<td class="text-right">{{ invoice_data.status }}</td>
								</tr>
							</table>

						</div>
					</div><!-- row -->
					<div class="mb-5 mt-4">
						<h4 class="mb-3">Ordered Products</h4>
						<table class="table table-striped mb-0">
							<thead>
								<tr>
									<th>Product(s)</th>
									<th>Price</th>
									<th class="text-right">Quantity</th>
									<th class="text-right">Amount</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(product, index) in invoice_data.invoice_products" :key="index">
									<td>{{ product.product_name }}</td>
									<td>&#8369;{{ product.price }}</td>
									<td align="right">{{ product.quantity }}</td>
									<td align="right">&#8369;{{ product.total }}</td>
								</tr>
							</tbody>
						</table>
						<table class="table">
							<tr>
								<th class="text-right">Subtotal:</th>
								<td width="25%" class="text-right">&#8369;{{ invoice_data.subtotal }}</td>
							</tr>
							<tr>
								<th class="text-right">Shipping Fee:</th>
								<td class="text-right">&#8369;{{ invoice_data.shipping_fee }}</td>
							</tr>
							<tr>
								<th class="text-right">Total Amount:</th>
								<td class="text-right">&#8369;{{ invoice_data.total }}</td>
							</tr>
						</table>
					</div>
				</template>
			</div><!-- /card-body -->
		</div><!-- /card -->
		<div class="d-none d-print-block">
	  		<invoice-component ref="invoiceDiv" id="printInvoice" :order="order" :invoice="invoice_data" :invoiceProducts="invoice_products" :customer="customer_data"></invoice-component>
	  	</div>
	</div>
</div>	
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';
	
	export default {
		props:['invoice', 'previous_url'],
		data() {
			return {
				loading: false,
				invoice_data: [],
				invoice_products: [],
				customer_data: [],
				order: []
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getInvoice() {
				this.loading = true;
				axios.get('/api/invoice/'+this.invoice)
				.then((response) => {
					this.loading = false;
					this.invoice_data = response.data;
					this.invoice_products = this.invoice_data.invoice_products;
					this.customer_data = this.invoice_data.order.customer;
					this.order = this.invoice_data.order;
				})
				.catch((error) => {
					this.loading = false;
					console.log(error.response);
				});
			}
		},
		created() {
			this.getInvoice();
		}
	}
</script>