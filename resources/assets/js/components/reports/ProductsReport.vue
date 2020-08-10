<template>
<div class="row justify-content-center">
	<div class="col-md-10">
		<div class="card mb-5">
			<div class="card-body">
				<div class="clearfix mb-4">
					<h2 class="float-left">Products Report</h2>
					<button type="button" class="btn btn-dark float-right" v-if="products.length > 0" v-print="'#printProductReports'">Print</button>
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
							<template v-if="loading">
								<tr>
									<td colspan="5" align="center">
				                        <half-circle-spinner
				                            :animation-duration="1000"
				                            :size="30"
				                            color="#ff1d5e"
				                          />
				                      </td>
								</tr>
							</template>
							<template v-else>
								<template v-if="!products.length">
									<tr>
										<td colspan="5" class="text-center">No products</td>
									</tr>
								</template>
								<template v-else>
									<tr v-for="(prod, index) in  products" :key="index">
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
								</template>
							</template>
						</tbody>
					</table>
				</div><!-- div table -->
			</div>
		</div><!-- card -->
		<div class="d-none d-print-block">
			<products-report-component id="printProductReports" :productsData="products" :admin="admin" :dateNow="bckend_date_now"></products-report-component>
		</div>
	</div>
</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		props: ['admin'],
		data() {
			return {
				loading: false,
				products: [],
				bckend_date_now: ''
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getProducts() {
				this.loading = true;
				axios.get('/api/products-report')
				.then((response) => {
					this.loading = false;
					this.products = response.data.products;
					this.bckend_date_now = response.data.date_now;
				})
				.catch((error) => {
					this.loading = false;
					console.log(error.response);
				})
			}
		},
		created() {
			this.getProducts();
		}
	}
</script>