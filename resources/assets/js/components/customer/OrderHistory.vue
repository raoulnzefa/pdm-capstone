<template>
<div class="mb-5">
	<h2 class="mb-4">Order History HEY</h2>
	<table class="table table-condensed table-striped">
		<thead>
			<tr>
				<th>Order No.</th>
				<th>Date Order</th>
				<th>Status</th>
				<th class="text-right">Total</th>
				<th class="text-center">Action</th>
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
				<template v-if="!orders_history.length">
					<tr>
						<td colspan="5" align="center">No orders history</td>
					</tr>
				</template>
				<template v-else>
					<tr v-for="(order, index) in orders_history" :key="index">
						<td>{{ order.number }}</td>
						<td>{{ order.date_order }}</td>
						<td>{{ order.status }}</td>
						<td align="right">&#8369;{{ order.total }}</td>
						<td align="center">
							<a :href="'/order/'+order.number+'/details'" class="btn btn-sm btn-dark">View Details</a>
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
				orders_history: [],
				loading: false
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getOrderHistory() {
				this.loading = true;

				axios.get('/api/customer/order/history/'+this.customer.id)
				.then(response => {
					this.loading = false;
					console.log(response.data);
					this.orders_history = response.data;
				})
				.catch(error => {
					this.loading = false;
					console.log(error.response);
				})
			}
		},
		created() {
			this.getOrderHistory()
		}
	}
</script>