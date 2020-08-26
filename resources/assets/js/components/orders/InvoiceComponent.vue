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
	<div class="float-left" style="width: 25%;">
		<table class="table order-table">
			<tr>
				<th>Customer</th>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td>{{ invoice.first_name+' '+invoice.last_name }}</td>
			</tr>
		</table>
	</div>
	<div class="float-left" style="width:30%" v-if="invoice.order.order_shipping_method === 'Shipping'">
		<table class="table order-table">
			<tr>
				<th>Shipping Address</th>
			</tr>
			<tr>
				<td>{{ invoice.order.shipping.shipping_firstname+' '+invoice.order.shipping.shipping_lastname }}</td>
			</tr>
			<tr>
				<td>{{ invoice.order.shipping.shipping_street }}</td>
			</tr>
			<tr>
				<td>{{ invoice.order.shipping.shipping_barangay+', '+invoice.order.shipping.shipping_municipal+', '+invoice.order.shipping.shipping_province+', '+invoice.order.shipping.shipping_zip_code }}</td>
			</tr>
			<tr>
				<td>{{ invoice.order.shipping.shipping_mobile_no }}</td>
			</tr>
		</table>
	</div>
	<div class="float-right" style="width: 37%;">
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
				<td class="text-right">{{ invoice.order_number }}</td>
			</tr>
			<tr>
				<td>Shipping Method:</td>
				<td class="text-right">{{ invoice.order.order_shipping_method }}</td>
			</tr>
			<tr>
				<td>Payment Method:</td>
				<td class="text-right">{{ invoice.order.order_payment_method }}</td>
			</tr>
			<tr>
				<td>Total:</td>
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
		<tr v-if="invoice.order.order_shipping_method === 'Shipping'">
			<th class="text-right">Shipping fee:</th>
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
		props: ['invoice', 'invoiceProducts'],
		data() {
			return {
				
			}
		}
	}
</script>