<template>
<div>
<div class="row">
	<div class="col-md-12">
		<div class="">
			<h2>Hello, {{ admin.first_name }}</h2>
			<hr>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-3">
		<a href="/admin/orders">
		<div class="card">
			<div class="card-body">
				<template v-if="orders_today_loading">
					<center>
						 <half-circle-spinner
                            :animation-duration="1000"
                            :size="25"
                            color="#ff1d5e"
                          />
					</center>
				</template>
				<template v-else>
					<h2 class="text-center mb-3 text-success">Orders</h2>
					<h3 class="text-center text-success">
						<span class="badge badge-success">{{ orders_today }}</span>
					</h3>	
				</template>
			</div>
		</div><!-- card -->
		</a>
	</div>
	<div class="col-md-3">
		<a href="/admin/customers">
			<div class="card">
				<div class="card-body">
					<template v-if="number_of_customer_loading">
						<center>
							 <half-circle-spinner
	                            :animation-duration="1000"
	                            :size="25"
	                            color="#ff1d5e"
	                          />
						</center>
					</template>
					<template v-else>
						<h2 class="text-center mb-3 text-success">Customers</h2>
						<h3 class="text-center text-success">
							<span class="badge badge-success">{{ number_of_customers }}</span>
						</h3>	
					</template>
				</div>
			</div><!-- card -->
		</a>
	</div>
	<div class="col-md-3">
		<a href="/admin/inventory">
		<div class="card">
			<div class="card-body">
				<template v-if="total_stocks_loading">
					<center>
						 <half-circle-spinner
                            :animation-duration="1000"
                            :size="25"
                            color="#ff1d5e"
                          />
					</center>
				</template>
				<template v-else>
					<h2 class="text-center mb-3 text-success">Inventory</h2>
					<h3 class="text-center text-success">
						<span class="badge badge-success">{{ total_stocks }}</span>
					</h3>	
				</template>
			</div>
		</div><!-- card -->
		</a>
	</div>
	<div class="col-md-3">
		<div class="card">
			<div class="card-body">
				<template v-if="sales_weekly_loading">
					<center>
						 <half-circle-spinner
                            :animation-duration="1000"
                            :size="25"
                            color="#ff1d5e"
                          />
					</center>
				</template>
				<template v-else>
					<h2 class="text-center mb-3 text-success">Total Weekly Sales</h2>
					<h3 class="text-center text-success" v-if="sales_this_week">
						<span class="badge badge-success">&#8369;{{ sales_this_week }}</span>
					</h3>
					<h3 class="text-center text-success" v-if="!sales_this_week">
						<span class="badge badge-success">&#8369;0.00</span>
					</h3>	
				</template>
			</div>
		</div>
	</div>
</div>
</div><!-- row -->
</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		props: ['admin'],
		data() {
			return {
				total_stocks: 0,
				orders_today: 0,
				sales_this_week: '0.00',
				return_request: 0,
				total_stocks_loading: false,
				orders_today_loading: false,
				sales_weekly_loading: false,
				return_request_loading: false,
				critical_level_loading: false,
				out_of_stock_loading: false,
				best_selling_loading: false,
				critical_levels: [],
				out_of_stocks: [],
				best_selling_products: [],
				number_of_customer_loading: false,
				number_of_customers: 0,
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getOrderToday() {
				this.orders_today_loading = true;

				axios.get('/api/orders/not-completed')
				.then((response) => {
					//console.log(response.data);
					this.orders_today_loading = false;
					this.orders_today = response.data
				})
				.catch((error) => {
					this.orders_today_loading = false;
				});
			},
			getNumberOfCustomers() {
				this.number_of_customer_loading = true;
				axios.get('/api/number-of-customers')
				.then((response) => {
					this.number_of_customer_loading = false;
					this.number_of_customers = response.data; 
				})
				.catch((error) => {
					this.number_of_customer_loading = false;
					console.log(error.response);
				});
			},
			getTotalStocks() {
				this.total_stocks_loading = true;

				axios.get('/api/inventory/total-stocks')
				.then((response) => {
					this.total_stocks_loading = false;
					this.total_stocks = response.data;
				})
				.catch((error) => {
					this.total_stocks_loading = false;
					console.log(error.response);
				});
			},
			getSalesThisWeek() {
				this.sales_weekly_loading = true;

				axios.get('/api/sales/this-week')
				.then((response) => {
					this.sales_weekly_loading = false;
					this.sales_this_week = response.data;
				})
				.catch((error) => {
					this.sales_weekly_loading = false;
					console.log(error.response);
				});
			},
			getCriticalLevels() {
				this.critical_level_loading = true;
				axios.get('/api/inventory/critical-levels')
				.then((response) => {
					this.critical_level_loading = false;
					this.critical_levels = response.data;
				})
				.catch((error) => {
					this.critical_level_loading = false;
					console.log(error.response);
				});
			},
			getOutOfStocks() {
				this.out_of_stock_loading = true;
				axios.get('/api/inventory/out-of-stock')
				.then((response) => {
					this.out_of_stock_loading = false;
					this.out_of_stocks = response.data;
				})
				.catch((error) => {
					this.out_of_stock_loading = false;
					console.log(error.response);
				});		
			},
			getBestSelling() {
				this.best_selling_loading = true;
				axios.get('/api/best-selling-products')
				.then((response) => {
					this.best_selling_loading = false;
					console.log(response.data);
					this.best_selling_products = response.data;
				})
				.catch((error) => {
					this.best_selling_loading = false;
					console.log(error.response);
				});
			}
		},
		created() {
			this.getOrderToday();
			this.getNumberOfCustomers();
			this.getTotalStocks();
			this.getSalesThisWeek();
			this.getCriticalLevels();
			this.getOutOfStocks();
			this.getBestSelling();
		}
	}
</script>