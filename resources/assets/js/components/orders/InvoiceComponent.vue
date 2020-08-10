<template>
<div id="printInvoice" ref="printInvoice">
<!-- <div class="row mb-4">
	<div class="col-md-6">
		<img src="/images/logo.jpg" width="220" height="120">
	</div>
	<div class="col-md-6">
		<table class="table order-table">
			<tr>
				<td align="right"><b>INFINITY FIGHTGEAR</b></td>
			</tr>
			<tr>
				<td align="right">Bunlo, Mac Arthur Hi-way, 2500 Bocaue, Bulacan</td>
			</tr>
			<tr>
				<td align="right">0998-790-1118</td>
			</tr>
		</table>
	</div>
</div> -->
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
				<td>{{ customer.first_name+' '+customer.last_name }}</td>
			</tr>
			<tr>
				<td>{{ customer.street }}</td>
			</tr>
			<tr>
				<td>{{ customer.barangay+', '+customer.municipality+', '+customer.province+', '+customer.zip_code }}</td>
			</tr>
			<tr v-if="customer.company != null">
				<td>{{ customer.company }}</td>
			</tr>
			<tr>
				<td>{{ customer.phone_number }}</td>
			</tr>
		</table>
	</div>
	<div class="float-left" style="width:30%">
		<table class="table order-table">
			<tr>
				<th>Shipping Address</th>
			</tr>
			<tr>
				<td>{{ order.first_name+' '+order.last_name }}</td>
			</tr>
			<tr>
				<td>{{ order.street }}</td>
			</tr>
			<tr>
				<td>{{ order.barangay+', '+order.municipal+', '+order.province+', '+order.zip_code }}</td>
			</tr>
			<tr v-if="order.company != null">
				<td>{{ order.company }}</td>
			</tr>
			<tr>
				<td>{{ order.phone_no }}</td>
			</tr>
		</table>
	</div>
	<div class="float-right" style="width: 30%;">
		<table class="table order-table">
			<tr>
				<th width="40%">Invoice Number:</th>
				<td class="text-right font-weight-bold" width="">{{ invoice.number }}</td>
			</tr>
			<tr>
				<td>Invoice Date:</td>
				<td class="text-right">{{ invoice.created }}</td>
			</tr>
			<tr>
				<td>Order Number:</td>
				<td class="text-right">{{ order.number }}</td>
			</tr>
			<tr>
				<td>Shipping Method</td>
				<td class="text-right">{{ order.shipping_method }}</td>
			</tr>
			<tr>
				<td>Payment Method</td>
				<td class="text-right">{{ order.payment_method }}</td>
			</tr>
			<tr>
				<td>Order Total:</td>
				<td class="text-right">&#8369;{{ invoice.total }}</td>
			</tr>
			<tr v-if="invoice.status == 'Void'">
				<td>Invoice Status:</td>
				<td class="text-right">&#8369;{{ invoice.status }}</td>
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
			<tr v-for="(product, index) in invoiceProducts" :key="index">
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
			<td width="25%" class="text-right">&#8369;{{ invoice.subtotal }}</td>
		</tr>
		<tr>
			<th class="text-right">Shipping Fee:</th>
			<td class="text-right">&#8369;{{ invoice.shipping_fee }}</td>
		</tr>
		<tr>
			<th class="text-right">Total Amount:</th>
			<td class="text-right">&#8369;{{ invoice.total }}</td>
		</tr>
	</table>
</div>
</div><!-- #printInvoice -->
</template>
<script>
	export default {
		props: ['order', 'invoice', 'customer', 'invoiceProducts'],
		data() {
			return {
				
			}
		}
	}
</script>