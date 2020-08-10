<template>
<div>
	<div>			
		<center>
			<div style="width:550px; margin-bottom: 20px;">
				<img src="/images/logo.jpg" width="220" height="120" style="margin-bottom: 10px;">
				<p class="text-center mb-0">Bunlo, Mac Arthur Hi-way, 2500 Bocaue, Bulacan</p>
				<p class="text-center mb-0">09987901118</p>
				<p class="text-center mb-0">infinityfightgear.site</p>
				<p class="text-center mb-0"><b>Products Report</b></p>
			</div>
		</center>
	</div>
	<div>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>ID</th>
					<th>Category</th>
					<th>Product</th>
					<th>Price</th>
					<th>Discount %</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(prod, index) in  productsData" :key="index">
					<td>{{ prod.sku }}</td>
					<td>{{ prod.category.name }}</td>
					<td>
						<span v-if="prod.has_variant  == 'No'">
							{{ prod.name }}
						</span>
						<span v-else>
							{{ prod.name }}
							<ul v-for="item in prod.product_variants" class="mb-0">
								<li>{{ item.variant }}</li>
							</ul>
						</span>
					</td>
					<td>
						<span v-if="prod.has_variant == 'No'">PHP {{ prod.product_no_variant.price  }}
						</span>
						<span v-else>
							<ul v-for="item in prod.product_variants" class="mb-0">
								<li>{{ item.variant }} - PHP {{ item.price }}</li>
							</ul>
						</span>
					</td>
					<td><span v-if="prod.discount == null">0</span><span v-else>{{ prod.discount }}</span></td>
				</tr>
			</tbody>
		</table>
		<div style="margin-top:350px;">
			<span class="float-left">Printed Date: <b>{{ dateNow }}</b></span>
			<span class="float-right">Printed by: <b>{{ admin.first_name+' '+admin.last_name }}</b></span>
		</div>
	</div>
</div>
</template>
<script>
	export default {
		props:['productsData', 'admin', 'dateNow'],
	}
</script>