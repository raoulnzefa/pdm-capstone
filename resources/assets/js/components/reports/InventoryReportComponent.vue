<template>
<div>
	<div>			
		<center>
			<div style="width:550px; margin-bottom: 20px;">
				<img src="/images/logo.jpg" width="220" height="120" style="margin-bottom: 10px;">
				<p class="text-center mb-0">Bunlo, Mac Arthur Hi-way, 2500 Bocaue, Bulacan</p>
				<p class="text-center mb-0">09987901118</p>
				<p class="text-center mb-0">infinityfightgear.site</p>
				<p class="text-center mb-0"><b>Inventory Report</b></p>
				<p class="text-center mb-0"><b>{{ title }}</b></p>
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
		<table class="table mt-4">
				<thead class="">
					<tr>
						<th>SKU</th>
						<th>Product</th>
						<th>Critical Level</th>
						<th>Quantity</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(prod, index) in inventoryData" :key="index">
						<td>{{ prod.sku }}</td>
						<td>{{ prod.name }}</td>
						<td>{{ prod.critical_level }}</td>
						<td>{{ prod.quantity }}</td>
					</tr>
				</tbody>
			</table>
			<table class="table" v-if="title == 'Stocks'">
				<tr>
					<td width="90%" align="right"><b>Total Stocks:</b></td>
					<td align="right">{{ totalStocks }}</td>
				</tr>
			</table>
		</div><!-- div table -->
	</div>
</div>
</template>
<script>
	export default {
		props:['inventoryData', 'totalStocks', 'admin', 'dateNow', 'title'],
	}
</script>