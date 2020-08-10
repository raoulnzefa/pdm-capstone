<template>
<div>
	<div class="clearfix mb-3">
		<button type="button" class="btn btn-dark float-right" v-if="inventory_list.length > 0" v-print="'#printInventoryReport'"><i class="fa fa-print"></i>&nbsp;Print</button>
	</div>
	<table class="table table-condensed table-bordered mb-0">
		<thead>
			<tr>
				<th>SKU</th>
				<th>Product</th>
				<th width="12%">Critical Level</th>
				<th width="10%">Stock</th>
			</tr>
		</thead>
		<tbody>
			<template v-if="loading">
				<tr>
					<td colspan="4" align="center">
	                    <half-circle-spinner
	                        :animation-duration="1000"
	                        :size="30"
	                        color="#ff1d5e"
	                      />
	                  </td>
				</tr>
			</template>
			<template v-else>
				<template v-if="!inventory_list.length">
					<tr>
						<td colspan="4" align="center">No inventory</td>
					</tr>
				</template>
				<template v-else>
					<tr v-for="(prod, index) in inventory_list" :key="index">
						<td>{{ prod.sku }}</td>
						<td>{{ prod.name }}</td>
						<td>{{ prod.critical_level }}</td>
						<td>{{ prod.quantity }}</td>
					</tr>
				</template>
			</template>
		</tbody>
	</table>
	<template v-if="!loading">
		<table class="table" v-if="inventory_list.length">
			<tr>
				<td width="90%" align="right"><b>Total Stocks:</b></td>
				<td align="right">{{ stocks }}</td>
			</tr>
		</table>
	</template>
	<div class="d-none d-print-block">
		<inventory-report-component id="printInventoryReport" :inventoryData="inventory_list" :totalStocks="stocks" :admin="admin" :dateNow="bckend_date" title="Stocks"></inventory-report-component>
	</div>
</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		props:['admin'],
		data() {
			return {
				loading: false,
				inventory_list: [],
				stocks: 0,
				bckend_date: ''
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getInventoryList() {
				this.loading = true;
				axios.get('/api/inventory-report')
				.then((response) => {
					this.loading = false;
					this.inventory_list = response.data.inventory;
					this.stocks = response.data.stocks;
					this.bckend_date = response.data.date_now;
				})
				.catch((error) => {
					this.loading = false;
					console.log(error.response);
				});
			}
		},
		created() {
			this.getInventoryList();
		}
	}
</script>