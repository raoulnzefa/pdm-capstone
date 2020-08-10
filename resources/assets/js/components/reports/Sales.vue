<template>
<div class="row justify-content-center mb-5">
	<div class="col-md-12">
		<div class="card mb-3">
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<h2 class="float-left">Sales</h2>
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
					  		<button type="button" class="btn btn-dark float-right mb-2" v-if="sales.length > 0" v-print="'#printSalesReport'"><i class="fa fa-print"></i>&nbsp;Print</button>
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
				<template v-if="!sales.length">
					<center>
						<h3 class="text-danger">No sales</h3>
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
								<th>Quantity</th>
								<th>Total</th>
							</tr>
						</thead>
						<tbody>
							<tr v-for="(sale, index) in sales" :key="index">
								<td>{{ sale.inventory_sku}}</td>
								<td>{{ sale.product_name }}</td>
								<td>{{ sale.category }}</td>
								<td align="right">&#8369;{{ sale.price }}</td>
								<td align="right">{{ sale.quantity }}</td>
								<td align="right">&#8369;{{ sale.total }}</td>
							</tr>
						</tbody>
					</table>
					<table class="table bg-white" v-if="sales.length">
						<!-- <tr>
							<td width="75%" align="right"><b>Gross Sales:</b></td>
							<td align="right">&#8369;{{ gross_sales }}</td>
						</tr> -->
						<!-- <tr>
							<td align="right"><b>Cancelled:</b></td>
							<td align="right">PHP {{ sales_return }}</td>
						</tr> -->
						<!-- <tr>
							<td align="right"><b>Dicounts:</b></td>
							<td align="right">&#8369;{{ discounts }}</td>
						</tr> -->
						<tr>
							<td width="75%" align="right"><b>TOTAL SALES:</b></td>
							<td align="right"><h4>&#8369;{{ gross_sales }}</h4></td>
						</tr>
					</table>
					<div class="d-none d-print-block">
						<sales-report-component
							id="printSalesReport"
							:salesData="sales"
							:grossSales="gross_sales"
							:discounts="discounts" 
							:salesReturn="sales_return"
							:admin="admin"
							:dateNow="date_now"
							:netSales="net_sales"
							:dateFrom="has_result ? date_from : ''"
							:dateTo="has_result ? date_to : ''">
						</sales-report-component>
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
				sales: [],
				gross_sales: '0.00',
				sales_return: '0.00',
				net_sales: '0.00',
				discounts: '0.00',
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
				submit_search: false
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getSalesProduct()
			{
				this.loading = true;
				axios.get('/api/product-sales')
				.then((response) => {
					this.loading = false;
					this.sales = response.data.sales;
					this.gross_sales = response.data.gross_sales;
					//this.discounts = response.data.discounts
					this.sales_return = response.data.sales_return;
					this.net_sales = response.data.net_sales;
					this.date_now = response.data.dateNow;

				})
				.catch((error) => {
					this.loading = false;
					console.log(error.response);
				})
			},
			showInvoice(invoice) {

			},
			searchDate() {
				this.search = true;
			},
			cancelSearch() {
				this.search = false;
				this.date_from = '';
				this.date_to = '';
				this.getSalesProduct();
			},
			submitSearch() {
				this.submit_search = true;
				this.loading = true;
				axios.post('/api/search-sales', {
					from_date: this.from_date,
					to_date: this.to_date,
				})
				.then((response) => {
					this.loading = false;
					this.sales = response.data.sales;

					if (this.sales.length) {
						this.has_result = true;
					}

					this.gross_sales = response.data.gross_sales;
					this.discounts = response.data.discount;
					this.sales_return = response.data.sales_return;
					this.net_sales = response.data.net_sales;
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