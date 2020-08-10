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
						<h2 class="mb-4">Critical Levels</h2>
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
									<template v-if="!critical_levels.length">
										<tr>
											<td class="text-center" colspan="4">No critical levels</td>
										</tr>
									</template>
									<template v-else>
										<tr v-for="(prod, index) in critical_levels" :key="index">
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
				critical_levels: []
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
					this.critical_levels = response.data;
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