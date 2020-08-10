<template>
<div class="row justify-content-center">
	<div class="col-md-12">
		<div class="card mb-3">
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<h2 class="float-left">Best Selling</h2>
					</div>
					<div class="col-md-8">
						<form class="form-inline justify-content-end" @submit.prevent="submitSearch">
							<label class="mr-2" for="">From:</label>
							<date-picker v-model="from_date" :config="options" class="mb-2 mr-sm-2"></date-picker>
							<label class="mr-2" for="">To:</label>
							<date-picker v-model="to_date" :config="options" class="mb-2 mr-sm-2"></date-picker>
							<button type="submit" class="btn btn-primary mb-2 mr-1">Search</button>
							<!-- <button type="button" class="btn btn-danger mb-2 mr-1" @click="cancelSearch">Cancel</button> -->
					  	<template v-if="has_result">
					  		<button type="button" class="btn btn-dark float-right mb-2" v-if="best_selling.length > 0" v-print="'#printBestSelling'"><i class="fa fa-print"></i>&nbsp;Print</button>
					  	</template>
						</form>
					</div>
				</div><!-- /row -->
			</div>
		</div><!-- card -->
		<template v-if="submit_search">
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
				<template v-if="!best_selling.length">
					<center>
						<h3 class="text-danger">No best selling</h3>
					</center>
				</template>
				<template v-else>
					<table class="table table-bordered mb-0 bg-white">
						<thead>
							<tr>
								<th>Product SKU</th>
								<th>Product name</th>
								<th>Category</th>
								<th>Price</th>
								<th>Qty. sold</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(product, index) in best_selling" :key="index">
								<td>{{ product.inventory_sku}}</td>
								<td>{{ product.product_name }}</td>
								<td>{{ product.category }}</td>
								<td align="right">&#8369;{{ product.price }}</td>
								<td align="right">{{ product.quantity }}</td>
								<td align="right">&#8369;{{ product.total }}</td>
							</tr>
						</tbody>
					</table>
					<table class="table">
						<tr>
							<td width="75%" align="right"><b>TOTAL SALES:</b></td>
							<td align="right"><h4>&#8369;{{ totalSales }}</h4></td>
						</tr>
					</table>
					<div class="d-none d-print-block">
						<best-selling-component 
							id="printBestSelling"
							:bestSellingData="best_selling"
							:bestSellingSales="totalSales"
							:admin="admin"
							:dateNow="date_now"
							:dateFrom="has_result ? date_from : ''"
							:dateTo="has_result ? date_to : ''">
						</best-selling-component>
					</div>
				</template>
			</template>
		</template><!-- submit_search -->
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
				best_selling: [],
				date_now: '',
				from_date: '',
				to_date: '',
				options: {
					format: 'YYYY-MM-DD',
					useCurrent: false,
					viewMode: 'days'
				},
				has_result: false,
				search: false,
				date_from: '',
				date_to: '',
				submit_search: false,
				totalSales: 0,
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			searchDate() {
				this.search = true;
			},
			cancelSearch() {
				this.search = false;
				this.date_from = '';
				this.date_to = '';
			},
			submitSearch() {
				this.submit_search = true;
				this.loading = true;
				axios.post('/api/get-best-selling-report', {
					from_date: this.from_date,
					to_date: this.to_date,
				})
				.then((response) => {
					this.loading = false;
					this.best_selling = response.data.best_selling_result;

					this.totalSales = response.data.total_sales;
					if (this.best_selling.length) {
						this.has_result = true;
					}

				
					this.date_now = response.data.date_now;
					this.date_from = response.data.from_date;
					this.date_to = response.data.to_date;
				})
				.catch((error) => {
					this.loading = false;
					console.log(error.response);
				});
			}
		},
		created() {
			//this.getSalesProduct();
		}
	}
</script>