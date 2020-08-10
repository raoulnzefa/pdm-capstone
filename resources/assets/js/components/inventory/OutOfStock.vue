<template>
	<div class="row justify-content-center">
		<div class="col-md-10">
			<div class="card">
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
						<h2 class="mb-4">Out of Stock</h2>
						<div>
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th class="text-center">SKU</th>
										<th class="text-center">Product</th>
										<th class="text-center">Stock</th>
										<th class="text-center">Crit. Level</th>
									</tr>
								</thead>
								<tbody>
									<template v-if="!out_of_stock.length">
										<tr>
											<td class="text-center" colspan="4">No out of stock</td>
										</tr>
									</template>
									<template v-else>
										<tr v-for="(prod, index) in out_of_stock" :key="index">
											<td class="text-center">{{ prod.sku }}</td>
											<td class="text-center">{{ prod.name }}</td>
											<td class="text-center">{{ prod.quantity }}</td>
											<td class="text-center">{{ prod.critical_level }}</td>
										</tr>
									</template>
								</tbody>
							</table>
						</div>
					</template>
				</div><!-- card-body -->
			</div><!-- card -->
		</div>
	</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		data() {
			return {
				loading: false,
				out_of_stock: []
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getOutOfStocks() {
				this.loading = true;
				axios.get('/api/inventory/out-of-stock')
				.then((response) => {
					this.loading = false;
					this.out_of_stock = this.response.data;
				})
				.catch((error) => {
					this.loading = false;
					console.log(error.response);
				});
			}
		},
		created() {
			this.getOutOfStocks();
		}
	}
</script>