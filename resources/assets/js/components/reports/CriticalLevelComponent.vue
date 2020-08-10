<template>
<div>
	<div class="clearfix mb-3">
		<button type="button" class="btn btn-dark float-right" v-if="critical_levels.length > 0" v-print="'#printCriticalLevel'"><i class="fa fa-print"></i>&nbsp;Print</button>
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
				<template v-if="!critical_levels.length">
					<tr>
						<td colspan="4" align="center">No critical level</td>
					</tr>
				</template>
				<template v-else>
					<tr v-for="(prod, index) in critical_levels" :key="index">
						<td>{{ prod.sku }}</td>
						<td>{{ prod.name }}</td>
						<td>{{ prod.critical_level }}</td>
						<td>{{ prod.quantity }}</td>
					</tr>
				</template>
			</template>
		</tbody>
	</table>
	<div class="d-none d-print-block">
		<inventory-report-component id="printCriticalLevel" :inventoryData="critical_levels" :totalStocks="stocks" :admin="admin" :dateNow="bckend_date" title="Critical Levels"></inventory-report-component>
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
				critical_levels: [],
				stocks: 0,
				bckend_date: ''
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getCriticalLevels() {
				this.loading = true;
				axios.get('/api/critical-level-report')
				.then((response) => {
					this.loading = false;
					this.critical_levels = response.data.critical_levels;
					// this.stocks = response.data.stocks;
					this.bckend_date = response.data.date_now;
				})
				.catch((error) => {
					this.loading = false;
					console.log(error.response);
				});
			}
		},
		created() {
			this.getCriticalLevels();
		}
	}
</script>