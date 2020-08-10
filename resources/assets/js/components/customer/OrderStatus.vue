<template>
	<div class="col-md-9 mb-5">
		<!-- <div class="mb-3">
			'Return' and 'Cancel' explanation
		</div> -->
		<h2 class="mb-4">Order Status</h2>
		<div>
			<table class="table table-condensed table-striped table-hover">
				<thead>
					<tr>
						<th>Order No.</th>
						<th>Date Order</th>
						<th>Status</th>
						<th>Quantity</th>
						<th>Total Amount</th>
						<th>Action</th>
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
						<template v-if="!orders.length">
							<tr>
								<td colspan="6" align="center">
									No recent order.
								</td>
							</tr>
						</template>
						<template v-else>
							<tr v-for="(order, index) in orders">
								<td class="align-middle">{{ order.number }}</td>
								<td class="align-middle">{{ order.date_order }}</td>
								<td class="align-middle">{{ order.status }}</td>
								<td class="align-middle">{{ order.quantity }}</td>
								<td class="align-middle">&#8369;{{ order.total }}</td>
								<td class="align-middle">
									<a :href="'/order/'+order.number+'/details'" class="btn btn-sm btn-dark">View Details</a>
								</td>
							</tr>
						</template>
					</template>
					
				</tbody>
			</table>
		</div>
		<!-- <div class="alert alert-warning" v-if="Object.keys(pending_orders).length === 0">
			<p class="mb-0">You haven't placed any orders with us. When you do, their status will appear on this page.</p>
		</div> -->
		    <b-modal id="orderReceivedModal"
                 ref="orderReceivedModal"
                 title="Order Received"
                 no-close-on-backdrop
                 no-close-on-esc
                 ok-title="Confirm"
                 :ok-disabled="submit_order_received"
                 @ok="submitOrderReceived"
                 @cancel="cancelOrderReceived"
                 @hidden="cancelOrderReceived">
            	<div>
            		<p class="pt-3">I confirm I have received the products and accept their condition.</p>
            	</div>
          </b-modal>
	</div>
</template>
<script>
import { HalfCircleSpinner } from 'epic-spinners'
	export default {
		props: ['customer'],
		data() {
			return {
				orders: [],
				submit_order_received: false,
				loading: false,
				order_received: 'not_accepted',
				order_number: ''
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			pendingOrder() {
				this.loading = true;
				axios.get('/api/order-status/'+this.customer.id)
				.then(response => {
					this.loading = false;
					this.orders = response.data;
				})
				.catch(error => {
					this.loading = sfalse;
					console.log(error.response);
				});
			},
			openOrderReceivedModal(order) {
				this.order_number = order.number;
				this.$refs.orderReceivedModal.show();
			},
			submitOrderReceived(evt) {
				evt.preventDefault();
				this.submit_order_received = true;
				axios.post('/api/order/received/'+this.order_number)
				.then((response) => {
					this.submit_order_received = false;

					if (response.data.success) {
						this.order_number = '';
						this.$refs.orderReceivedModal.hide();
						this.pendingOrder();
						
					}
				})
				.catch((error) => {
					this.submit_order_received = false;
					console.log(error.response);
				});
			},
			cancelOrderReceived() {
				this.order_number = '';
				this.$refs.orderReceivedModal.hide();
			},

		},
		created() {
			this.pendingOrder();
		}
	}
</script>