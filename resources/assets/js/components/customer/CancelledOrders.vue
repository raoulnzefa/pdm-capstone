<template>
<div>
	<table class="table table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="text-center">Order No.</th>
				<th class="text-center">Date Ordered</th>
				<th class="text-center">Cancel No.</th>
				<th class="text-center">Date Cancelled</th>
				<th class="text-center">Total</th>
				<th class="text-center">Action</th>
			</tr>
		</thead>
		<tbody>
			<template v-if="loading">
				<tr>
					<td colspan="6" align="center">
						<half-circle-spinner
		                    :animation-duration="1000"
		                    :size="30"
		                    color="#ff1d5e"
		                  />
					</td>
				</tr>
			</template>
			<template v-else>
				<template v-if="!cancelled_orders.length">
					<tr>
						<td colspan="6" align="center">No cancelled orders</td>
					</tr>
				</template>
				<template v-else>
					<tr v-for="(order, index) in cancelled_orders" :key="index">
						<td class="text-center">{{ order.number }}</td>
						<td class="text-center">{{ order.date_order }}</td>
						<td class="text-center">{{ order.cancellation.number }}</td>
						<td class="text-center">{{ order.date_cancelled }}</td>
						<td class="text-center">PHP {{ order.total }}</td>
						<td class="text-center">
							<a :href="'/order/'+order.number+'/details'" class="btn btn-dark btn-sm">View Details</a>
						</td>
					</tr>
				</template>
			</template>
		</tbody>
	</table>
</div>
</template>
<script>
import { HalfCircleSpinner } from 'epic-spinners'

	export default {
		props: ['customer'],
		data() {
			return {
				cancelled_orders: [],
				loading: false
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getCancelledOrders() {
				this.loading = true;
				axios.get('/api/customer/cancelled-orders/'+this.customer.number)
				.then((response) => {
					//console.log(response.data);
					this.loading = false;
					this.cancelled_orders = response.data;
				})
				.catch((error) => {
					this.loading = false;
					console.log(error.response);
				});
			}
		},
		created() {
			this.getCancelledOrders();	
		}
	}
</script>