<template>
	<div class="row justify-content-center">
		<div class="col-md-10">
			<div class="card">
				<div class="card-body">
					<div class="mb-3 clearfix">
						<h2 class="float-left">Sales</h2>	
						<!-- <h2 class="float-right" v-if="!sales_loading">Total Sales: PHP {{ total_sales }}</h2> -->
					</div>
					<div>
						
					</div>
					<div>
						<table class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th>Date</th>
									<th class="text-center">Invoice No.</th>
									<th class="text-center">Order No.</th>
									<th class="text-center">Customer</th>
									<th class="text-center">Quantity</th>
									<th class="text-center">Amount</th>
									<th class="text-center">Details</th>
								</tr>
							</thead>
							<tbody>
								<template v-if="sales_loading">
									<tr>
										<td colspan="8" align="center">
											<half-circle-spinner
					                            :animation-duration="1000"
					                            :size="30"
					                            color="#ff1d5e"
					                          />
										</td>
									</tr>
								</template>
								<template v-else>
									<template v-if="!sales.length">
										<tr>
											<td align="center" colspan="8">No Sales Data.</td>
										</tr>
									</template>
									<template v-else>
										<tr v-for="(sale, index) in sales" :key="index">
											<td>{{ sale.created_at }}</td>
											<td align="center"><a href="">{{ sale.number }}</a></td>
											<td align="center"><a :href="'/admin/order/'+sale.order_number+'/details'">{{ sale.order_number }}</a></td>
											<td align="center">{{ sale.first_name+' '+sale.last_name }}</td>
											<td align="center">{{ sale.order.quantity }}</td>
											<td align="center">PHP&nbsp;<span v-if="sale.status == 'Refunded'">-{{ sale.total }}</span><span v-else>{{ sale.total }}</span></td>
											<td align="center"><button class="btn btn-sm btn-primary">View</button></td>
										</tr>
									</template>
								</template>
							</tbody>
						</table>
					</div>
				</div>
			</div><!-- card -->
		</div>
	</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		data() {
			return {
				sales: [],
				sales_loading: false,
				total_sales: 0
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getSales() {
				this.sales_loading = true;
				axios.get('/api/sales')
				.then((reponse) => {
					this.sales_loading = false;
					this.sales = reponse.data.sales;
					this.total_sales = reponse.data.total_sales;
				})
				.catch((error) => {
					this.sales_loading = false;
					console.log(error.reponse);
				});
			}
		},
		created() {
			this.getSales();
		}
	}
</script>