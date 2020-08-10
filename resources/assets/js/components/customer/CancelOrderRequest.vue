<template>
	<div class="row mb-5">
		<div class="col-md-12">
			<form @submit.prevent="submitCancellation" method="post" ref="cancelRequest" action="/cancellation">
				<h2 class="mb-4">Order Number: {{ order.number }}</h2>
				<div v-if="orderProdQty > 1">
					<b-form-checkbox id="checkbox1"
                     	v-model="cancellation_type"
                    	value="Full"
                     	unchecked-value="Partial"
                     	class="mb-4">
				      I want to cancel my entire order
				    </b-form-checkbox>
				</div>
				<table class="table table-bordered table-striped mb-5">
					<thead>
						<tr>
							<th class="text-center">Ordered Products</th>
							<th class="text-center">Price</th>
							<th class="text-center" width="16%">Ordered Qty</th>
							<th class="text-center" width="16%">Qty. to Cancel</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(product, index) in cancel_products" :key="index">
							<td class="text-center align-middle">{{ product.product_name }}</td>
							<td class="text-center align-middle">&#8369;{{ product.price }}</td>
							<td class="text-center align-middle">{{ product.quantity }}</td>
							<td class="text-center align-middle">
								<template v-if="orderProdQty <= 1">
									{{ product.quantity }}
								</template>
								<template v-else>
									<template v-if="cancellation_type == 'Partial'">
										<b-form-select v-model="product.cancel_qty">
									      	<option value="0">0</option>
									      	<option v-for="index in product.quantity"
									      			:value="index"
									      			:key="index"
									      			>{{ index }}</option>

									    </b-form-select>
									</template>
									<template v-else>
										{{ product.quantity }}
									</template>	
								</template>
								
							</td>
						</tr>
					</tbody>
				</table>
				<p class="mb-5">Why are you requesting for cancellation?</p>
				<div>
					<input type="hidden" name="_token" :value="csrf">
					<input type="hidden" name="order_number" :value="order.number">
					<div class="form-group row">
		                <label for="" class="col-sm-2 col-form-label">Reason:</label>
		                <div class="col-sm-6">
		                    <select class="form-control" :class="{'is-invalid' : $v.cancel_reason.$error}" v-model="$v.cancel_reason.$model" name="reason">
		                    	<option :value="null" disabled>Please choose a reason</option>
		                    	<option v-for="(reason, index) in cancel_reasons"
		                    				:key="index"
		                    				:value="reason.id">{{ reason.title }}</option>
		                    </select>
		                    <div v-if="$v.cancel_reason.$error">
		                    	<span class="error-feedback" v-if="!$v.cancel_reason.required">Return reason is required</span>
		                    </div>
		                </div>
		            </div>
				    <div class="form-group row">
		                <label for="comment" class="col-sm-2 col-form-label">Comment:</label>
		                <div class="col-sm-8">
		                    <textarea class="form-control" rows="5" name="comment" id="comment" v-model.trim="$v.cancel_comment.$model" :class="{'is-invalid' : $v.cancel_comment.$error}"></textarea>
		                    <div v-if="$v.cancel_comment.$error">
		                    	<span class="error-feedback d-block" v-if="!$v.cancel_comment.maxLength">Comment must have at most {{ $v.cancel_comment.$params.maxLength.max }} letters</span>
		                    	<span class="error-feedback" v-if="!$v.cancel_comment.commentPattern">Please input a valid value</span>
		                    </div>
		                </div>
		            </div>
		            <div class="form-group row">
	                    <label for="" class="col-sm-2 col-form-label"></label>
	                    <div class="col-sm-8">
	                        <button type="submit" class="btn btn-dark ifg-btn" :disabled="cancel_submit"><span v-if="cancel_submit"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>Submit Cancellation </button>
	                    </div>
	                </div>
				</div>
			</form>
			<!-- Modal Component -->
			<b-modal
				title="Cancellation"
				ref="orderSubmittedModal"
				id="orderSubmittedModal"
				hide-header
				hide-footer
				centered
				no-fade
				no-close-on-esc
				no-close-on-backdrop>
			   	<h4 class="text-center text-uppercase mt-3 mb-4">Your cancellation request has been submitted.</h4>
			   	<p class="text-center mb-4">Your cancellation request has been submitted. The cancellation status will be updated once we handle your request.</p>
			   	<center class="d-block">
			   		<button class="btn btn-dark text-uppercase" @click="handleCancelSubmitted">Okay</button>
			   	</center>
			</b-modal>
		</div><!-- col-md-12 -->
	</div>
</template>
<script>
	import { required, minLength, maxLength, helpers } from 'vuelidate/lib/validators';

	const commentPattern = helpers.regex('companyPattern', /([A-Za-z])[A-Za-z0-9\s.]*$/);

	export default {
		props: ['order'],
		data() {
			return {
				server_errors: [],
				cancel_products: this.order.order_products,
				cancel_reason: null,
				cancel_comment: '',
				cancel_reasons: [],
				cancellation_type: 'Partial',
				csrf: document.head.querySelector('meta[name="csrf-token"]').content,
				cancel_submit: false
			}
		},
		validations() {
			return {
				cancel_reason: {
					required
				},
				cancel_comment: {
					maxLength: maxLength(200),
					commentPattern
				},
				cancel_qty: {
					$each: {
						required
					}
				}
			}
		},
		methods: {
			getCancelReasons() {
				axios.get('/api/reason/cancellation')
				.then((response) => {
					this.cancel_reasons = response.data;
				})
				.catch((error) => {
					console.log(error.response);
				});
			},
			submitCancellation() {
				if (this.orderProdQty == 1)
				{	
					this.cancellation_type = 'Full';
					this.createCancellation();
					
				}
				else if (this.orderProdQty > 1)
				{
					if (this.cancellation_type == 'Partial') {
						if (this.cancelTotalQty <= 0) {
							Swal('Cancellation Invalid', 'The total of product quantity you want to cancel is cannot be 0.', 'error');
						} else {
							this.createCancellation();
						}
					} else {
						this.createCancellation();
					}
				}
				
				
       			
			},
			createCancellation() {
				this.$v.$touch();

				if (!this.$v.$invalid) {
					this.cancel_submit = true;

					axios.post('/api/cancel-request', {
						reason: this.cancel_reason,
						comment: this.cancel_comment,
						cancel_request_type: this.cancellation_type,
						cancel_products: this.cancel_products,
						order_number: this.order.number,
						customer_id: this.order.customer_id,
						order_qty: this.orderProdQty,
						cancel_qty: this.cancelTotalQty
					})
					.then((response) => {
						this.cancel_submit = false;
						console.log(response.data);
						if (response.data.success) {
							//this.$refs.orderSubmittedModal.show();
							Swal('Request Submitted', 'Your cancellation request has been submitted. The cancellation status will be updated once we handle your request.', 'success')
							.then((okay) => {
								if (okay) {
									window.location.href = '/my-account/cancellation';
								}
							});
						}
						else
						{
							Swal('Error', 'Failed to cancel this order. Error encountered.', 'error')
							.then((okay) => {
								if (okay)
								{
									window.location.href = "/my-account/cancellation";
								}
							});
						}
					})
					.catch((error) => {
						this.cancel_submit = false;
						if (error.response.status == 422) {
							this.server_errors = error.response.data.errors;
						}
					});
				}
			},
			handleCancelSubmitted(evt) {
				evt.preventDefault();
				window.location.href = "/my-account/order-status";
			}
		},
		created() {
			this.getCancelReasons();
		},
		computed: {
			cancelTotalQty() {
				var qty = this.cancel_products.reduce(
					(a, b) => a + b.cancel_qty,
					0
				);

				return qty;
			},
			orderProdQty() {
				var qty = this.cancel_products.reduce(
					(a, b) => a + b.quantity,
					0
				);

				return qty;
			}
		}
	}
</script>