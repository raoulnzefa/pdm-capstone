<template>
	<div class="row">
		<div class="col-md-12">
			<div class="card mt-4 mb-4">
				<div class="card-header">
					<h3>Discount</h3>
				</div>
				<form method="POST" @submit.prevent="saveDiscount">
				<div class="card-body pt-4">
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
						<div class="alert alert-danger" v-if="server_errors.length != 0">
							<ul class="mb-0 pl-3">
								<li v-for="(err,index) in server_errors" :key="index">{{ err[0] }}</li>
							</ul>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Order Quantity:</label>
									<input type="text" class="form-control" 
										placeholder="Enter order quantity"
										tabindex="1"
										v-model.trim="$v.quantity.$model"
										:class="{'is-invalid': $v.quantity.$error}">
									<div v-if="$v.quantity.$error">
                					<span class="error-feedback" v-if="!$v.quantity.required">Please enter a quantity</span>
					               <template v-if="$v.quantity.required">
					                  <span class="error-feedback" v-if="!$v.quantity.numbersOnly">Please enter a valid value</span>
					               </template>
					            </div>
								</div>
								<div class="form-group">
									<label>Discount Percent:</label>
									<select class="form-control" 
										tabindex="2" 
										v-model.trim="$v.discount_percent.$model"
										:class="{'is-invalid': $v.discount_percent.$error}">
										<option value="" disabled>Select discount percent</option>
										<option v-for="(disc, index) in discounts" :key="index" :value="disc">{{disc}}</option>
									</select>
									<div v-if="$v.discount_percent.$error">
				                	<span class="error-feedback" v-if="!$v.discount_percent.required">Please select a discount percent</span>
				              	</div>
								</div>
								<div class="form-group">
									<label>Status:</label>
									<select class="form-control" v-model="status">
										<option value="0" selected>Enable</option>
										<option value="1">Disable</option>
									</select>
								</div>
							</div>
						</div>
					</template>
				</div>
				<div class="card-footer" v-if="!loading">
					<button type="submit" class="btn btn-primary" :disabled="saving">Save discount</button>
				</div>
				</form>
			</div>

		</div>
	</div>
</template>
<script>
	import { required, minLength, maxLength, minValue, helpers,decimal } from 'vuelidate/lib/validators';
  	import { HalfCircleSpinner } from 'epic-spinners';

  	const numbersOnly = helpers.regex('numbersOnly', /^([1-9])[0-9]*$/);

	export default {
		props: ['admin'],
		data() {
			return {
				status: 0,
				saving: false,
				loading: false,
				discountID: '',
				quantity: '',
				discount_percent: '',
				server_errors: [],
				discounts: [5,10,15,20,25,30,35,40,45,50]
			}
		},
		components: {
			HalfCircleSpinner
		},
		validations() {
			return {
				quantity: {
					required,
					numbersOnly
				},
				discount_percent: {
					required
				}
			}
		},
		methods: {
			getDiscount()
			{
				this.loading = true;
				axios.get('/api/get-discount')
				.then(response => {
					this.loading = false;
					this.discountID = response.data.id;
					this.quantity = response.data.order_quantity;
					this.status = (response.data.is_disabled) ? response.data.is_disabled : 0;
					this.discount_percent = (response.data.discount_percent) ? response.data.discount_percent : '';
				})
				.catch(error => {
					this.loading = false;
					console.log(error.response);
				});
			},
			saveDiscount() {
				this.$v.$touch();

				if (!this.$v.$invalid)
				{
					this.saving = true;

					if (!this.discountID)
					{
						// set
						axios.post('/api/set-discount', {
							order_quantity: this.quantity,
							discount_percent: this.discount_percent,
							status: this.status,
							admin_id: this.admin.id,
						})
						.then(response => {
							this.saving = false;
							if (response.data.success) {
		                	Swal('Discount has been saved','', 'success');
		                	this.getDiscount();
		                	this.server_errors = [];
		              	}
						})
						.catch(error => {
							if(error.response.status == 422) {
								this.saving = false;
								this.server_errors = error.response.data.errors;
							}
						});
					}
					else
					{
						// update
						axios.put('/api/update-discount/'+this.discountID, {
							order_quantity: this.quantity,
							discount_percent: this.discount_percent,
							status: this.status,
							admin_id: this.admin.id,
						})
						.then(response => {
							this.saving = false;
							if (response.data.success) {
		                	Swal('Discount has been saved','', 'success');
		                	this.getDiscount();
		                	this.server_errors = [];
		              	}
						})
						.catch(error => {
							if(error.response.status == 422) {
								this.saving = false;
								this.server_errors = error.response.data.errors;
							}
						});
					}
				}
			}
		},
		mounted() {
			this.getDiscount();
		}
	}
</script>

