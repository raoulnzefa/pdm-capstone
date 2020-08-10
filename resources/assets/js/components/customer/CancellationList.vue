<template>
<div class="mb-5">
	<h2 class="mb-4">Cancellation</h2>
	<table class="table table-condensed table-striped">
		<thead>
			<tr>
				<th>Requested</th>
				<th>Order No.</th>
				<th>Status</th>
				<th>Action</th>
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
				<template v-if="!cancellations.length">
					<tr>
						<td colspan="5" align="center">No cancellations</td>
					</tr>
				</template>
				<template v-else>
					<tr v-for="(cancellation, index) in cancellations" :key="index">
						<td class="align-middle">{{ cancellation.date_request }}</td>
						<td class="align-middle">{{ cancellation.order_number }}</td>
						<td class="align-middle">{{ cancellation.status }}</td>
						<td class="align-middle"><button class="btn btn-dark btn-sm" @click="showCancellationDetails(cancellation)">View Details</button></td>
					</tr>
				</template>
			</template>
		</tbody>
	</table>
	<!-- Modal Component -->
  	<b-modal
  		id="cancellationDetailsModal"
  		ref="cancellationDetailsModal"
  		title="Cancellation Details"
  		no-close-on-esc
  		no-close-on-backdrop
  		no-fade
  		size="lg"
  		hide-footer>
	    <div>
	    	<template v-if="cancel_details_loading">
	    		<center>
	    			<half-circle-spinner
	                    :animation-duration="1000"
	                    :size="30"
	                    color="#ff1d5e"
	                  />
	    		</center>
	    	</template>
	    	<template v-else>
	    		<b-row>
		    		<b-col cols="12">
		    			<div class="mb-5">
		    				<div class="clearfix mb-5">
		    					<h4 class="float-left">Order Number: {{cancellation_details.order_number}}</h4>
		    					<template v-if="cancellation_details.status == 'Pending'">
		    						<template v-if="!withdrawal_expired">
										<button class="btn btn-danger float-right" @click="withdrawCancellation">Withdraw Cancellation</button>
									</template>
		    					</template>
								<!-- <template v-if="cancellation_details.status == 'Awaiting Refund'">
		    						<template v-if="!withdrawal_expired">
										<button class="btn btn-danger float-right" @click="withdrawCancellation">Withdraw Cancellation</button>
									</template>
		    					</template> -->
		    				</div>
		    				<!-- <div class="form-group row">
			                    <label for="reason" class="col-sm-3 col-form-label">Order Number:</label>
			                     <div class="col-sm-5">
			                        <input type="text" class="form-control" id="orderNum" v-model="cancellation_details.order_number" readonly>
			                    </div>
			                </div> -->
		    				<div class="form-group row">
			                    <label for="reason" class="col-sm-3 col-form-label">Reason for Cancellation:</label>
			                     <div class="col-sm-5">
			                        <input type="text" class="form-control" id="reason" v-model="reason" readonly>
			                    </div>
			                </div>
			                <div class="form-group row">
			                    <label for="reason" class="col-sm-3 col-form-label">Customer Comment:</label>
			                     <div class="col-sm-5">
			                        <textarea class="form-control" rows="4" v-model="cancellation_details.comment" readonly></textarea>
			                    </div>
			                </div>
			                <div class="form-group row">
			                    <label for="reason" class="col-sm-3 col-form-label">Cancellation Action:</label>
			                     <div class="col-sm-5">
			                       <input type="text" name="" class="form-control" v-model="cancellation_details.action" readonly>
			                    </div>
			                </div>
			                <div class="form-group row">
			                    <label for="reason" class="col-sm-3 col-form-label">Cancellation Status:</label>
			                     <div class="col-sm-5">
			                       <input type="text" name="" class="form-control" v-model="cancellation_details.status" readonly>
			                    </div>
			                </div>
			                <!-- <div class="form-group row" v-if="refundCancelOrder">
			                    <label for="reason" class="col-sm-3 col-form-label">Amount Refunded:</label>
			                     <div class="col-sm-5">
			                       <input type="text" name="" class="form-control" v-model="refundCancelOrder.total" readonly>
			                    </div>
			                </div> -->
		    			</div>
		                <div class="mb-5">
		                	<table class="table table-striped mb-0">
								<thead>
									<tr>
										<th width="40%">Product(s)</th>
										<th class="text-right">Price</th>
										<th class="text-right">Quantity</th>
										<th class="text-right">Amount</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="(prod, index) in cancellation_details.cancel_product_requests">
										<td>
											<img :src="'/storage/products/'+prod.order_product.inventory.product.image" class="img-fluid" width="30%" height="15%">
												<span class="align-middle">{{ prod.order_product.product_name }}</span>
										</td>
										<td class="text-right align-middle">&#8369;{{ prod.order_product.price }}</td>
										<td class="text-right align-middle">{{ prod.quantity}} of {{ prod.ordered_qty }}</td>
										<td class="text-right align-middle">&#8369;{{ prod.amount }}</td>
									</tr>
								</tbody>
							</table>
							<template v-if="order.cancelled_order">
								<table class="table">
									<tr>
										<th width="75%" class="text-right">Subtotal:</th>
										<td align="right">{{ subtotal }}</td>
									</tr>
									<tr>
										<th class="text-right">Discount Deduction:</th>
										<td align="right">{{ discount }}</td>
									</tr>
									<tr>
										<th class="text-right">Shipping Fee:</th>
										<td align="right">{{ shipping_fee }}</td>
									</tr>
									<tr>
										<th class="text-right">Refundable Amount:</th>
										<td align="right">{{ total }}</td>
									</tr>
								</table>
							</template>
							<template v-else>
								<table class="table" v-if="order.payment_status == 'Paid'">
									<tr>
										<th width="75%" class="text-right">Subtotal:</th>
										<td align="right">{{ subtotal }}</td>
									</tr>
									<tr>
										<th class="text-right">Discount Deduction:</th>
										<td align="right">{{ discount }}</td>
									</tr>
									<tr>
										<th class="text-right">Shipping Fee:</th>
										<td align="right">{{ shipping_fee }}</td>
									</tr>
									<tr>
										<th class="text-right">Refundable Amount:</th>
										<td align="right">{{ total }}</td>
									</tr>
								</table>
							</template>
		                </div>
		    		</b-col>
		    	</b-row>
	    	</template>
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
				cancellations: [],
				loading: false,
				order_no: '',
				reason: '',
				comment: '',
				cancel_action: '',
				status: '',
				cancel_products: [],
				refundCancelOrder: [],
				cancellation_details: [],
				cancel_details_loading: false,
				subtotal: '',
				discount: '',
				shipping_fee: '',
				total: '',
				order: [],
				withdrawal_expired: '',
				cancel_detail_id: ''
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			// orders/cancellation-requests
			getMyCancellations() {
				this.loading = true;
				axios.get('/api/cancel-request/'+this.customer.id+'/list')
				.then((response) => {
					this.loading = false;
					this.cancellations = response.data;
					console.log(response.data);
				})
				.catch((error) => {
					this.loading = false;
					console.log(error.response);
				});			
			},
			showCancellationDetails(cancel_detail) {
				this.cancel_detail_id = cancel_detail.id;
				this.$refs.cancellationDetailsModal.show();
				this.getCancellationDetails();
			},
			getCancellationDetails()
			{
				this.cancel_details_loading = true;
				axios.get('/api/cancel-request/'+this.cancel_detail_id)
				.then((response) => {
					//console.log(response.data);
					this.cancel_details_loading = false;
					//console.log(response.data.cancel_request_details);
					this.cancellation_details = response.data.cancel_request_details;
					this.order = this.cancellation_details.order;
					this.reason = response.data.cancel_request_details.reason.title;
					this.subtotal = this.convertCurrency(response.data.subtotal);
					this.discount = this.convertCurrency(response.data.discount);
					this.shipping_fee = this.convertCurrency(response.data.shipping_fee);
					this.total = this.convertCurrency(response.data.total);
					this.withdrawal_expired = response.data.withdrawal_expired;
				})
				.catch((error) => {
					this.cancel_details_loading = false;
					console.log(error.response);
				});
			},
			convertCurrency(value) {
				return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(value);
			},
			withdrawCancellation()
			{
				Swal({
				  title: 'Withdraw Cancellation',
				  text: 'Are you sure you want to withdraw your cancellation?',
				  type: 'question',
				  showCancelButton: true,
				  confirmButtonText: 'Yes',
				  cancelButtonText: 'No'
				}).then((result) => {
				  	if (result.value) {
				  		axios.put('/api/cancellation/withdrawal/'+this.order.number)
				  		.then((response) => {
				  			if (response.data.success)
				  			{
				  				this.getCancellationDetails();
				  				this.getMyCancellations();
				  			}
				  		})
				  		.catch((error) => {
				  			console.log(error.response);
				  		});
				  	}
				});	
			}
		},
		created() {
			this.getMyCancellations();
		}
	}
</script>