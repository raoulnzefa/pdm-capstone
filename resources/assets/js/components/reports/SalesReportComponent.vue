<template>
<div>
	<div>			
		<center>
			<div style="width:550px; margin-bottom: 40px;">
				<img src="/images/logo.jpg" width="220" height="120" style="margin-bottom: 10px;">
				<p class="text-center mb-0 p">Bunlo, Mac Arthur Hi-way, 2500 Bocaue, Bulacan</p>
				<p class="text-center mb-0">09987901118</p>
				<p class="text-center mb-0">infinityfightgear.site</p>
				<p class="text-center mb-0"><b>Sales Report</b></p>
				<p class="text-center" v-if="dateFrom != ''"><b>{{ dateFrom }}</b> to <b>{{ dateTo }}</b></p>
			</div>
		</center>
	</div>
	<div>
		<div>
			<table width="100%">
				<tr>
					<td class="p">Printed Date: <b>{{ dateNow }}</b></td>
					<td align="right">Printed by: <b>{{ admin.first_name+' '+admin.last_name }}</b></td>
				</tr>
			</table>
		</div>
		<table class="table table-striped bg-primary mt-4">
			<thead> 
				<div>
				<tr>
					<th>Product SKU</th>
					<th>Product name</th>
					<th>Category</th>
					<th>Price</th>
					<th>Quantity</th>
					<th>Total</th>
				</tr>
				</div>
			</thead>
			<tbody>
				<tr v-for="(sale, index) in salesData" :key="index">
					<td>{{ sale.inventory_sku}}</td>
					<td>{{ sale.product_name }}</td>
					<td>{{ sale.category }}</td>
					<td align="right">&#8369;{{ sale.price }}</td>
					<td align="right">{{ sale.quantity }}</td>
					<td align="right">&#8369;{{ sale.total }}</td>
				</tr>
			</tbody>
		</table>
		<table class="table">
			<tr>
				<td width="75%" align="right"><b>TOTAL SALES:</b></td>
				<td align="right"><h4>&#8369;{{ grossSales }}</h4></td>
			</tr>
		</table>
	</div>
</div>
</template>
<script>
	export default {
		props:['salesData', 'grossSales','discounts','salesReturn','netSales', 'admin', 'dateNow', 'dateFrom', 'dateTo'],
	}
</script>