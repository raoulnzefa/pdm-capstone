 <template>
<div class="row justify-content-center">
	<div class="col-md-8">
		<a :href="previous_url" class="mb-2 text-secondary d-block"><i class="fa fa-chevron-left"></i>&nbsp;Back</a>
		<div class="card mb-5">
			<div class="card-body">
				<template v-if="order_loading">
					<center class="mt-4 mb-4">
						<half-circle-spinner
                            :animation-duration="1000"
                            :size="30"
                            color="#ff1d5e"
                          />
					</center>
				</template>
				<template v-else>
					<div class="clearfix">
						<h2 class="float-left mb-4">Order Details</h2>
						<!-- <template v-if="order.status == 'Pending'">
							<div class="dropdown">
							  	<button class="btn btn-dark mr-2 dropdown-toggle float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Select an action" :disabled="action_loading">Action
							  	</button>
							  	<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
							  		<a href="javascript:void(0);" class="dropdown-item" @click="sendEmailForPending">Send Email Payment</a>
								  	<a href="javascript:void(0);" class="dropdown-item" @click="cancelOrder">Cancel Order</a>
							  </div>
							</div>
						</template> -->
						<!-- <template v-if="order.status == 'On Hold'">
							<div class="dropdown">
							  	<button class="btn btn-dark mr-2 dropdown-toggle float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Select an action" :disabled="action_loading">Action
							  	</button>
							  	<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
							  		<a href="javascript:void(0);" class="dropdown-item" @click="markAsPaid">Mark as Paid</a>
								  	<a href="javascript:void(0);" class="dropdown-item" @click="cancelOrder">Cancel Order</a>
							  </div>
							</div>
						</template>
						<template v-if="order.status == 'Reserved'">
							<template v-if="order.payment_method == 'PayPal'">
								<button class="btn btn-dark float-right" :disabled="picked_up_btn" @click="orderPickedUp">Pick up</button>
							</template>
							<template v-else>
								<button class="btn btn-dark float-right" :disabled="mark_as_paid_cash" @click="cashMarkAsPaid">Mark as Paid</button>
							</template>
						</template> -->
						<template v-if="order.status == 'Reserved'">
							<template v-if="order.payment_method == 'PayPal'">
								<button class="btn btn-dark float-right" :disabled="picked_up_btn" @click="orderPickedUp">Pick up</button>
							</template>
							<template v-if="order.payment_method == 'Bank Deposit'">
								<div class="dropdown">
								  	<button class="btn btn-dark mr-2 dropdown-toggle float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Select an action" :disabled="action_loading">Action
								  	</button>
								  	<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
								  		<a href="javascript:void(0);" class="dropdown-item" @click="markAsPaid">Mark as Paid</a>
									  	<a href="javascript:void(0);" class="dropdown-item" @click="cancelOrder">Cancel Order</a>
								  </div>
								</div>
								<button class="btn btn-dark float-right mr-1" @click="viewDepositSlip" v-if="order.bank_deposit_slip">View Deposit Slip</button>
								<!-- <div class="form-check float-right mr-2 mt-2">
					          	<input class="form-check-input" type="checkbox" name="has_deposit_slip" id="has_deposit_slip" v-model="has_deposit_slip" value="true">
					          	<label class="form-check-label" for="has_deposit_slip">
					            Has bank deposit slip
					          	</label>
					        	</div> -->
							</template>
							<template v-if="order.payment_method == 'Cash'">
								<button class="btn btn-dark float-right" 
									:disabled="mark_as_paid_cash"
									@click="cashMarkAsPaid">Mark as Paid</button>
							</template>
						</template>
						<template v-if="order.status == 'Processing'">
							<button class="btn btn-dark float-right" @click="shipOrder">Ship Order</button>
							<template v-if="!order.remarks">
								<a :href="'/admin/invoice/'+order.invoice
								.number" class="btn btn-dark float-right mr-1">Invoice</a>
							</template>
							<button class="btn btn-dark float-right mr-1" @click="viewDepositSlip" v-if="order.payment_method == 'Bank Deposit'">View Deposit Slip</button>
						</template>
						<template v-if="order.status == 'Shipped'">
							<template v-if="!order.remarks">
								<a :href="'/admin/invoice/'+order.invoice
								.number" class="btn btn-dark float-right mr-1">Invoice</a>
							</template>
						</template>
						<template v-if="order.status == 'Picked up'">
							<a :href="'/admin/invoice/'+order.invoice.number" class="btn btn-dark float-right">Invoice</a>
							
						</template>
						<template v-if="order.status == 'Completed'">
							<div class="dropdown">
							  	<button class="btn btn-dark mr-2 dropdown-toggle float-right" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Select an action" :disabled="action_loading">Invoice
							  	</button>
							  	<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
								  	<a :href="'/admin/invoice/'+invoice_d.number" class="dropdown-item" v-for="(invoice_d,index) in order.invoices">Invoice {{index + 1}}</a> 	
							  </div>
							</div>
							
						</template>
					</div>
					<div class="row">
						<div class="col-md-6">
							<table class="table order-table">
								<tr>
									<td width="30%" class="font-weight-bold">Order Number:</td>
									<td class="font-weight-bold">{{ order.number }}</td>
								</tr>
								<tr>
									<td>Placed on:</td>
									<td>{{ order.date_created }}</td>
								</tr>
								<tr>
									<td>Total:</td>
									<td>&#8369;{{ order.total }}</td>
								</tr>
								<tr>
									<td>Shipping method:</td>
									<td>{{ order.shipping_method }}</td>
								</tr>
								<tr>
									<td>Payment method:</td>
									<td>{{ order.payment_method }}</td>
								</tr>
								<tr>
									<td>Payment status:</td>
									<td>{{ order.payment_status }}</td>
								</tr>
								<tr>
									<td>Status:</td>
									<td>{{ order.status }}</td>
								</tr>
								<tr v-if="order.status == 'Cancelled'">
									<td>Date Completed:</td>
									<td>{{ order.date_cancelled }}</td>
								</tr>
								<tr v-if="order.status == 'Completed'">
									<td>Date Completed:</td>
									<td>{{ order.date_completed }}</td>
								</tr>
								<tr v-if="order.remarks">
									<td>Remarks:</td>
									<td>{{ order.remarks }}</td>
								</tr>
							</table>
						</div>
						<div class="col-md-6">
							<table class="table order-table" v-if="order.shipping_method == 'Shipping'">
								<tr>
									<th class="text-right">Shipping Address</th>
								</tr>
								<tr>
									<td align="right">{{ order.first_name+' '+order.last_name }}</td>
								</tr>
								<tr>
									
									<td align="right">{{ order.street }}</td>
								</tr>
								<tr>
									
									<td align="right">{{ order.barangay+', '+order.municipal+', '+order.province+', '+order.zip_code }}</td>
								</tr>
								<tr v-if="order.company != ''">
									
									<td align="right">{{ order.company }}</td>
								</tr>
								<tr>
									
									<td align="right">{{ order.phone_no }}</td>
								</tr>
							</table>
						</div>
					</div>
					<div class="mt-4">
						<h4 class="mb-3">Ordered Products</h4>
						<table class="table table-striped mb-0">
							<thead>
								<tr>
									<th>SKU</th>
									<th width="45%">Product(s)</th>
									<th class="text-right">Price</th>
									<th class="text-right">Quantity</th>
									<th class="text-right">Amount</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(product, index) in order.order_products">
									<td class="align-middle">{{ product.inventory_sku }}</td>
									<td>
										<img :src="'/storage/products/'+product.inventory.product.image" class="img-fluid" width="25%" height="15%">
										<span class="align-middle">{{ product.product_name }}</span>
									</td>
									<td class="text-right align-middle">&#8369;{{ product.price }}</td>
									<td class="text-right align-middle">{{ product.quantity }}</td>
									<td class="text-right align-middle">&#8369;{{ product.total }}</td>
								</tr>
							</tbody>
						</table>
						<table class="table">
							<tr>
								<th class="text-right">Subtotal:</th>
								<td width="30%" class="text-right">&#8369;{{ order.subtotal }}</td>
							</tr>
							<tr>
								<th class="text-right">Shipping Fee:</th>
								<td class="text-right">&#8369;{{ order.shipping_cost }}</td>
							</tr>
							<tr>
								<th class="text-right">Total Amount:</th>
								<td class="text-right">&#8369;{{ order.total }}</td>
							</tr>
						</table>
						<template v-if="order.cancelled_order">
							<div class="mt-4 d-block">
								<h4 class="mb-3">Cancelled</h4>
								<table class="table table-striped mb-0">
									<thead>
										<tr>
											<th>SKU</th>
											<th width="45%">Product(s)</th>
											<th class="text-right">Price</th>
											<th class="text-right">Quantity</th>
											<th class="text-right">Amount</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(prod, index) in order.cancelled_order.cancelled_products">
											<td class="align-middle">{{ prod.order_product.inventory.sku }}</td>
											<td class="align-middle">
												<img :src="'/storage/products/'+prod.order_product.inventory.product.image" class="img-fluid" width="25%" height="15%">
												<span class="align-middle">{{ prod.order_product.product_name }}</span>
											</td>
											<td class="text-right align-middle">&#8369;{{ prod.order_product.price }}</td>
											<td class="text-right align-middle">{{ prod.quantity }}</td>
											<td class="text-right align-middle">&#8369;{{ prod.amount }}</td>
										</tr>
									</tbody>
								</table>
								<table class="table">
									<tr>
										<th class="text-right">Subtotal:</th>
										<td width="30%" class="text-right">&#8369;{{ order.cancelled_order.subtotal }}</td>
									</tr>
									<tr>
										<th class="text-right">Shipping Fee:</th>
										<td width="30%" class="text-right">&#8369;{{ order.cancelled_order.shipping_fee }}</td>
									</tr>
									<tr>
										<th class="text-right">Refunded:</th>
										<td width="30%" class="text-right">&#8369;{{ order.cancelled_order.total }}</td>
									</tr>
								</table>
							</div>
						</template>
						<template v-if="order.returned_order">
							<div>
								<h4 class="mb-3">Returned</h4>
								<table class="table table-striped mb-0">
									<thead>
										<tr>
											<th>SKU</th>
											<th width="45%">Product(s)</th>
											<th class="text-right">Price</th>
											<th class="text-right">Quantity</th>
											<th class="text-right">Amount</th>
										</tr>
									</thead>
									<tbody>
										<tr v-for="(prod, index) in order.returned_order.returned_products">
											<td class="align-middle">{{ prod.inventory.sku }}</td>
											<td class="align-middle">
												<img :src="'/storage/products/'+prod.inventory.product.image" class="img-fluid" width="25%" height="15%">
												<span class="align-middle">{{ prod.inventory.name }}</span>
											</td>
											<td class="text-right align-middle">&#8369;{{ prod.price }}</td>
											<td class="text-right align-middle">{{ prod.quantity }}</td>
											<td class="text-right align-middle">&#8369;{{ prod.amount }}</td>
										</tr>
									</tbody>
								</table>
								<table class="table" v-if="order.returned_order.type =='Refund'">
									<tr>
										<th class="text-right">Subtotal:</th>
										<td width="30%" class="text-right">&#8369;{{ order.returned_order.subtotal }}</td>
									</tr>
									<tr>
										<th class="text-right">Shipping Fee:</th>
										<td width="30%" class="text-right">&#8369;{{ order.returned_order.shipping_fee }}</td>
									</tr>
									<tr>
										<th class="text-right">Refunded:</th>
										<td width="30%" class="text-right">&#8369;{{ order.returned_order.total }}</td>
									</tr>
								</table>
							</div>
						</template>
					</div>
				</template>
			</div><!-- card-body -->
		</div><!-- card -->
		<!-- Ship Order Modal -->
        <b-modal id="shipOrderModal"
                 ref="shipOrderModal"
                 :title="ship_order_title"
                 no-close-on-backdrop
                 no-close-on-esc
                 ok-title="Save"
                 no-fade
                 hide-header-close
                 :ok-disabled="shipOrderSubmit"
                 :cancel-disabled="shipOrderSubmit"
                 @ok="submitShipOrder"
                 @cancel="cancelShipOrder"
                 @hidden="clearShipOrder">
            
              <div class="alert alert-danger" v-if="shipping_errors.length != 0">
                <ul class="mb-0">
                  <li v-for="(err,index) in shipping_errors" :key="index">{{ err[0] }}</li>
                </ul>
              </div>
            
            <form @submit.stop.prevent="saveShipOrder" v-if="ship_order">
            	<b-form-group id=""
                          label="Shipping Company:"
                          label-for="shippingCompany">
                <b-form-select v-model.trim="$v.shipping_company.$model" :class="{'is-invalid': $v.shipping_company.$error }">
                	<option v-for="item in shipping_companies" :value="item.id">{{ item.name }}</option>
            	</b-form-select>
            	<div v-if="$v.shipping_company.$error">
                  <span class="error-feedback" v-if="!$v.shipping_company.required">Shipping company is required</span>
                </div>
              </b-form-group>
              <b-form-group id=""
                          label="Tracking Number:"
                          label-for="TrackNum">
                <b-form-input id="TrackNum"
                              type="text"
                              v-model.trim="$v.tracking_no.$model"
                              :class="{'is-invalid': $v.tracking_no.$error}">
                </b-form-input>
                <div v-if="$v.tracking_no.$error">
                  <span class="error-feedback" v-if="!$v.tracking_no.required">Tracking number is required</span>
                  <span class="error-feedback" v-if="!$v.tracking_no.alphaNum">Tracking number can contains letters and numbers only</span>
                  <span class="error-feedback" v-if="!$v.tracking_no.maxLength">Tracking number must have at most {{ $v.tracking_no.$params.maxLength.max }} letters</span>
                </div>
              </b-form-group>
            </form>
            <div slot="modal-ok">
            	<span v-if="shipOrderSubmit"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>Save
            </div>
          </b-modal>

          <!-- Deliver Order Modal -->
          <b-modal ref="deliverOrderModal"
          		   title="Sending Email"
          		   no-close-on-backdrop
          		   no-close-on-esc
          		   hide-footer
          		   hide-header
          		   no-fade
          		   centered
          		   size="sm">
		   <center>
			    <half-circle-spinner
	                :animation-duration="1000"
	                :size="30"
	                color="#ff1d5e"
	                class=""
	              />
	              <span>&nbsp;Please wait...</span>
		   </center>
		  </b-modal>
	
        <!-- Not Confirmed Deposit Slip Modal -->
        <b-modal id="depositSlipModal"
                 ref="depositSlipModal"
                 title="Deposit Slip"
                 no-fade
                 ok-only
                 ok-title="Confirm"
                 size="lg"
                 @ok="confirmBankDepositSlip">
            	<img :src="deposit_slip" class="img-fluid">
        </b-modal>

        <!-- Confirmed Deposit Slip Modal -->
        <b-modal id="confirmedDepositSlipModal"
                 ref="confirmedDepositSlipModal"
                 title="Deposit Slip"
                 no-fade
                 ok-only
                 size="lg">
            	<img :src="deposit_slip" class="img-fluid">
        </b-modal>
	</div>
</div><!-- row -->
</template>
<script>
import { required, minLength, maxLength, alphaNum } from 'vuelidate/lib/validators';
import { HalfCircleSpinner } from 'epic-spinners'

export default {
	props: ['id', 'previous_url', 'order_num', 'admin'],
	data() {
		return {
			shipping_company_options: [
				{ value: 'LBC Express', text: 'LBC Express' }
			],
			ship_loading: false,
			order_loading: false,
			order: [],
			status_complete: false,
			sending_shipment_email: false,
			shipment_email_sent: false,
			shipment_email: '',
			ship_order: false,
			action_loading: false,
			order_completed: false,
			shipping_company: '',
			tracking_no: '',
			server_errors: [],
			shipOrderSubmit: false,
			markAsPaidLoading: false,
			date_now: '',
			shipping_companies: [],
			shipping_errors: [],
			ship_order_title: '',
			invoice_data: [],
			customer_data: [],
			invoice_products: [],
			products_for_refund: [],
			products_for_replacement: [],
			order_products: [],
			refund_submit: false,
			refund_subtotal: '',
			refund_discount: '',
			refund_shipping_fee: '',
			refund_total: '',
			replacement_submit: false,
			replacement_subtotal: '',
			replacement_discount: '',
			replacement_shipping_fee: '',
			replacement_total: '',
			return_restock: 'no',
			picked_up_btn: false,
			mark_as_paid_cash: false,
			deposit_slip: '',
			has_deposit_slip: false
		}
	},
	components: {
		HalfCircleSpinner
	},
	validations() {
		if (this.ship_order) {
			return {
				shipping_company: {
					required
				},
				tracking_no: {
					required,
					maxLength: maxLength(40),
					alphaNum
				}
			}
		}
	},
	methods: {
		getOrderDetails() {
			this.order_loading = true;
			axios.get('/api/order/details/'+this.id)
			.then((response) => {
				this.order_loading = false;
				//console.log(response.data);
				this.order = response.data.order;
				this.total_prod_qty = response.data.total_qty;
				this.date_now = response.data.date_now;
				this.invoice_data = response.data.order.invoice;
				this.invoice_products = response.data.order.invoice.invoice_products;
				this.customer_data = response.data.order.customer;
				//this.products_for_refund = response.data.order.order_products;
				//this.products_for_replacement = response.data.order.order_products;
				this.order_products = response.data.order.order_products;

			})
			.catch((error) => {
				console.log(error.response);
				this.order_loading = false;
				//console.log(error.response);
			});
		},
		sendEmailForPending() {
			this.$refs.deliverOrderModal.show();
			axios.put('/api/order/send-email-pending-status/'+this.order.number)
			.then((response) => {
				this.$refs.deliverOrderModal.hide();
				if (response.data.success)
				{
					Swal('Email Sent', 'Email has been sent successfully.', 'success');
				}
			})
			.catch((error) => {
				this.$refs.deliverOrderModal.hide();
				console.log(error.response);
			});
		},
		cancelOrder() {
			Swal({
			  title: 'Cancel Order',
			  text: "Are you sure you want to cancel this order?",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Yes'
			}).then((result) => {
			  if (result.value) {
			  	this.$refs.deliverOrderModal.show();

			   	axios.put('/api/order/cancel/'+this.order.number)
			   	.then((response) => {
			   		this.$refs.deliverOrderModal.hide();
			   		if (response.data.success)
			   		{
			   			this.getOrderDetails();
			   		}

			   	})
			   	.catch((error) => {
			   		this.$refs.deliverOrderModal.hide();
			   		console.log(error.response);
			   	});
			  }
			});
		},
		receivedPayment() {
			this.action_loading = true;
			axios.put('/api/order/'+this.order.id+'/received-payment/')
			.then((response) => {
				this.action_loading = false;
				if (response.data.success)
				{
					Swal('Order Details Updated', 'Order details has been updated', 'success')
					this.getOrderDetails();
				}
				else
				{
					console.log(response.data.error);
				}
			})
			.catch((error) => {
				this.action_loading = false;
				console.log(error.response);
			});
		},
		shipOrderModal() {
			this.getShippingCompanies()
			this.ship_order = true;
			this.ship_order_title = 'Ship order '+this.order.number;
			this.$refs.shipOrderModal.show();
		},
		shipOrder() {
			this.$refs.deliverOrderModal.show();
			axios.post('/api/ship-order', {
				order_number: this.order.number,
				customer_id: this.order.customer.id,
				admin_id: this.admin.id,
			})
			.then((response) => {
				if (response.data.success)
				{
					this.$refs.deliverOrderModal.hide();
					this.getOrderDetails();
					Swal('Ship Order', 'Order status has been updated','success');
				}
				else
				{
					this.$refs.deliverOrderModal.hide();	
					this.getOrderDetails();
				}
				
			})
			.catch((error) => {
				this.$refs.deliverOrderModal.hide();
				console.log(error.response);
			});
		},
		submitShipOrder(evt) {
			evt.preventDefault();
			this.saveShipOrder();
		},
		cancelShipOrder() {
			this.clearShipOrder();
		},
		saveShipOrder() {
			
		},
		clearShipOrder() {
			// this.$nextTick(() => { this.$v.$reset() });
			this.shipping_errors = [];
			this.shipping_company = '';
			this.tracking_no = '';
			this.ship_order_title = '';
			this.$nextTick(() => { this.$v.$reset() });
		},
		openDeliverModal() {
			//this.$refs.deliverOrderModal.show();
			Swal({
			  title: 'Deliver Order?',
			  text: "Confirm deliver order",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Yes'
			}).then((result) => {
			  if (result.value) {
			   	 this.$refs.deliverOrderModal.show();
			   	 axios.post('/api/order/deliver', {
			   	 	order_id: this.order.id
			   	 })
			   	 .then(response => {
			   	 	this.$refs.deliverOrderModal.hide();

			   	 	if (response.data.success) {

			   	 		Swal('Email Sent','Email has been sent', 'success');
			   	 		this.getOrderDetails();
			   	 		
			   	 	} else {

			   	 		console.log(response.data.error);

			   	 	}
			   	 })
			   	 .catch(error => {
			   	 	this.$refs.deliverOrderModal.hide();
			   	 	console.log(error.response);
			   	 });
			  }
			});
		},
		markAsPaid() {
			//confirmation
			Swal({
			  title: 'Confirmation?',
			  text: "Mark as Paid",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Yes'
			}).then((result) => {
			  if (result.value) {
			   	 this.$refs.deliverOrderModal.show();
			   	 axios.post('/api/order/payment-received', {
			   	 	customer_id: this.order.customer.id,
			   	 	order_id: this.order.number,
			   	 	admin_id: this.admin.id,
			   	 	has_deposit_slip: this.has_deposit_slip
			   	 })
			   	 .then(response => {
			   	 	this.$refs.deliverOrderModal.hide();

			   	 	if (response.data.success == true) {
			   	 		Swal('Status Updated','Order status has been updated', 'success');
			   	 		this.getOrderDetails();
			   	 		
			   	 	} else if (response.data.success == false) {

			   	 		Swal('Failed',response.data.message, 'error');

			   	 	} else {

			   	 		console.log(response.data.error);

			   	 	}
			   	 })
			   	 .catch(error => {
			   	 	this.$refs.deliverOrderModal.hide();
			   	 	console.log(error.response);
			   	 });
			  }
			});
		},
		completeOrder() {
			//confirmation
			Swal({
			  title: 'Confirmation',
			  text: "Complete this order?",
			  type: 'warning',
			  showCancelButton: true,
			  confirmButtonColor: '#3085d6',
			  cancelButtonColor: '#d33',
			  confirmButtonText: 'Yes'
			}).then((result) => {
			  if (result.value) {
			   	 this.$refs.deliverOrderModal.show();
			   	 axios.post('/api/order/complete-order', {
			   	 	order_id: this.order.number
			   	 })
			   	 .then(response => {
			   	 	this.$refs.deliverOrderModal.hide();

			   	 	if (response.data.success) {

			   	 		Swal('Completed','Order has been completed', 'success');

			   	 		this.getOrderDetails();
			   	 		
			   	 	} else {

			   	 		console.log(response.data.error);

			   	 	}
			   	 })
			   	 .catch(error => {
			   	 	this.$refs.deliverOrderModal.hide();
			   	 	console.log(error.response);
			   	 });
			  }
			});
		},
		getShippingCompanies() {
			axios.get('/api/shipping-company')
			.then((response) => {
				this.shipping_companies = response.data;
			})
			.catch((error) => {
				console.log(error.response);
			});
		},
		showRefundModal() {
			this.products_for_refund = this.order.order_products;
			this.$refs.refundModal.show();
		},
		submitRefund(evt) {
			evt.preventDefault();
			this.saveRefund();
		},
		cancelRefund() {
			for (var i = 0; i < this.products_for_refund.length; i++)
			{
				//console.log(this.products_for_refund[i].refund_qty);
				this.products_for_refund[i].return_qty = 0;
			}

			this.return_restock = 'no';
		},
		showReplacementModal() {
			this.products_for_replacement = this.order.order_products;
			this.$refs.replacementModal.show();
		},
		submitReplacement(evt) {
			evt.preventDefault();
			this.saveReplacement();
		},
		cancelReplacement() {
			for (var i = 0; i < this.products_for_replacement.length; i++)
			{
				//console.log(this.products_for_refund[i].refund_qty);
				this.products_for_replacement[i].return_qty = 0;
			}

			this.return_restock = 'no';
		},
		saveRefund() {
			this.refund_submit = true;
			axios.post('/api/save-refund/'+this.order.number, {
				subtotal: this.refund_subtotal,
				discount: this.refund_discount,
				shipping_fee: this.refund_shipping_fee,
				total: this.refund_total,
				refund_products: this.products_for_refund,
				restock: this.return_restock,
				admin_id: this.admin.id
			})
			.then((response) => {
				this.refund_submit = false;
				console.log(response.data);
				if (response.data.success) {
					this.$refs.refundModal.hide();
					Swal('Success', 'Refund has been created', 'success');
					this.getOrderDetails();
				}
			})
			.catch((error) => {
				this.refund_submit = false;
				console.log(error.response);
			});
		},
		saveReplacement() {
			this.replacement_submit = true;
			axios.post('/api/save-replacement/'+this.order.number, {
				subtotal: this.replacement_subtotal,
				discount: this.replacement_discount,
				shipping_fee: this.replacement_shipping_fee,
				total: this.replacement_total,
				replacement_products: this.products_for_replacement,
				admin_id: this.admin.id
			})
			.then((response) => {
				//console.log(response.data);
				this.replacement_submit = false;
				if (response.data.success)
				{
					this.$refs.replacementModal.hide();
					Swal('Success', 'Replacement has been created', 'success');
					this.getOrderDetails();
				}
				else
				{
					this.$refs.replacementModal.hide();
					Swal('Warning', 'Insufficient stock', 'warning');
				}
			})
			.catch((error) => {
				this.replacement_submit = false;
				console.log(error.response);
			});
		},
		orderPickedUp() {
			this.picked_up_btn = true;
			axios.put('/api/order/picked-up/'+this.order.number, {
				admin_id: this.admin.id
			})
			.then((response) => {
				this.picked_up_btn = false;
				if (response.data.success)
				{
					Swal('Updated', 'Order status updated', 'success');
					this.getOrderDetails();
				}
				else
				{
					Swal('Error', 'There was an error encountered.', 'warning');
				}
			})
			.catch((error) => {
				this.picked_up_btn = false;
				console.log(error.response);
			});
		},
		cashMarkAsPaid() {
			this.mark_as_paid_cash = true;
			axios.put('/api/order/mark-as-paid/'+this.order.number, {
				admin_id: this.admin.id
			})
			.then((response) => {
				this.mark_as_paid_cash = false;
				if (response.data.success)
				{
					Swal('Updated', 'Order status updated', 'success');
					this.getOrderDetails();
				}
				else
				{
					Swal('Error', 'There was an error encountered.', 'warning');
				}
			})
			.catch((error) => {
				this.mark_as_paid_cash = false;
				console.log(error.response);
			});
		},
		updateOrderViewed() {
			axios.put('/api/order/viewed/'+this.order_num)
			.then((response) => {
				if (response.data.success)
				{
					this.$bus.$emit('update-badge', true);
				}
			})
			.catch((error) => {
				console.log(error.response);
			});
		},
		viewDepositSlip(e) {
			this.deposit_slip = `/storage/deposit_slip/${this.order.bank_deposit_slip.image}`;
			
			if (this.order.bank_deposit_slip.is_confirmed == 1)
			{
				this.$refs.confirmedDepositSlipModal.show();
			} else {
				this.$refs.depositSlipModal.show();
			}
		},
		confirmBankDepositSlip(e) {
			axios.post('/api/confirm-bank-deposit-slip', {
				order_number: this.order_num
			})
			.then((response) => {
				if (response.data.success)
				{
					this.getOrderDetails();
				}
			})
			.catch((error) => {
				console.log(error.response);
			});
		}
	},
	created() {
		this.getOrderDetails();
		this.updateOrderViewed();
		// console.log(2+2);
		// console.log(new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(this.amount));
	},
	computed: {
		refundBtnDisableToggle() {
			var bool;
			if (this.returnQty > 0)
			{
				bool = false;

				if (this.refund_submit)
				{
					bool = true;
				}
			}
			else
			{
				bool = true;
			}

			return bool;
		},
		returnQty() {
			var qty = this.products_for_refund.reduce(
					(a, b) => a + b.return_qty,
					0
				);
			

			return parseInt(qty);
		},
		totalQty() {
			var qty;
			
			qty = this.products_for_refund.reduce(
				(a, b) => a + b.quantity,
				0
			);
			
			return parseInt(qty);

		},
		discountPerProduct() {
			var discount_per_product;
			var discount;

			if (this.order.quantity >= 12)
			{
				discount_per_product = this.order.discount.replace(/,/g,'') / this.totalQty;
				discount = this.returnQty * parseFloat(discount_per_product) 
			}
			else
			{
				discount_per_product = 0;
				discount = 0;
			}
			
			

			this.refund_discount = discount;

			return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(discount);
		},
		refundSubtotal() {
			var amount = this.products_for_refund.reduce(
					(a, b) => a + b.price.replace(/,/g,'') * b.return_qty,
					0
				);
			
			this.refund_subtotal = amount;

			return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(amount)
		},
		refundDiscountedSubtotal() {
			var discounted_subtotal;

			return discounted_subtotal = this.refund_subtotal - this.refund_discount;
		},	
		shippingRatePerProduct() {
			var shipping_rate;

			

			if (this.order.shipping_method == 'Shipping')
			{
				shipping_rate = this.products_for_refund.reduce(
					(a, b) => a + b.shipping_rate.replace(/,/g,'') * b.return_qty,
					0
				);
				this.refund_shipping_fee = shipping_rate;
			}
			else
			{
				shipping_rate = 0;
				this.refund_shipping_fee = 0;
			}

			return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(shipping_rate);
			
		},
		refundNewSubtotal() {
			var new_subtotal;

			return new_subtotal = this.refund_subtotal + this.refund_shipping_fee;
		},
		refundTotal() {
			var total;

			total = this.refundNewSubtotal;

			this.refund_total = total;
			
			return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(total);
		},
		replacementTotalQty() {
			var qty;

		
			qty = this.products_for_replacement.reduce(
				(a, b) => a + b.quantity,
				0
			);
			

			return parseInt(qty);	
		},
		replacementQty() {
			var qty = this.products_for_replacement.reduce(
					(a, b) => a + b.return_qty,
					0
				);
			
			return parseInt(qty);
		},
		replacementSubtotal() {
			var amount = this.products_for_replacement.reduce(
					(a, b) => a + b.price.replace(/,/g,'') * b.return_qty,
					0
				);

			this.replacement_subtotal = amount;

			return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(amount);
		},
		replacementDiscount() {
			var discount_per_product;
			var discount;

			if (this.order.quantity >= 12)
			{
				discount_per_product = this.order.discount.replace(/,/g,'') / this.replacementTotalQty;
				discount = this.replacementQty * discount_per_product;
			}
			else
			{
				discount_per_product = 0;
				discount = 0;
			}

			this.replacement_discount = discount;

			return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(discount);
		},
		replacementDiscountedSubtotal() {
			var discounted_subtotal;

			return discounted_subtotal = this.replacement_subtotal - this.replacement_discount;
		},
		replacementShippingRatePerProduct() {
			var shipping_rate;

			if (this.order.shipping_method == 'Shipping')
			{
				shipping_rate = this.products_for_replacement.reduce(
					(a, b) => a + b.shipping_rate.replace(/,/g,'') * b.return_qty,
					0
				);

				this.replacement_shipping_fee = shipping_rate;
			}
			else
			{
				shipping_rate = 0;
				this.replacement_shipping_fee = 0;
			}

			return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(shipping_rate);
		},
		replacementNewSubtotal() {
			var new_subtotal;

			return new_subtotal = this.replacement_subtotal + this.replacement_shipping_fee;
		},
		replacementTotal() {
			var total;

			total = this.replacementNewSubtotal;

			this.replacement_total = total;

			return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(total);
		},
		replacementBtnDisableToggle() {
			var bool;
			if (this.replacementQty > 0)
			{
				bool = false;

				if (this.replacement_submit)
				{
					bool = true;
				}
			}
			else
			{
				bool = true;
			}

			return bool;
		}
	}
}
</script>