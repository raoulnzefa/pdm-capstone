<template>
<div>
	<table class="table table-bordered table-condensed table-striped">
		<thead>
			<tr>
				<th class="text-center">Order No.</th>
				<th class="text-center">Order Date</th>
				<th class="text-center">Status</th>
				<th class="text-center">Total</th>
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
				<template v-if="!completed.length">
					<tr>
						<td colspan="5" align="center">No completed orders</td>
					</tr>
				</template>
				<template v-else>
					<tr v-for="(order, index) in completed" :key="index">
						<td align="center">{{ order.number }}</td>
						<td align="center">{{ order.date_order }}</td>
						<td align="center"><h5><span class="badge badge-success">{{ order.status }}</span></h5></td>
						<td align="center">PHP {{ order.total }}</td>
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
				completed: [],
				loading: false
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getCompletedOrders() {
				this.loading = true;

				axios.get('/api/customer/order/completed/'+this.customer.id)
				.then(response => {
					this.loading = false;
					console.log(response.data);
					this.completed = response.data;
				})
				.catch(error => {
					this.loading = false;
					console.log(error.response);
				})
			}
		},
		created() {
			this.getCompletedOrders()
		}
	}
</script>