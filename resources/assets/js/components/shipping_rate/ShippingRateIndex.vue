<template>
	<div class="row">
		<div class="col-md-12">
			<div class="card mt-4 mb-4">
				<div class="card-header">
					<h3>Shipping Rate</h3>
				</div>
				<form @submit.prevent="saveShippingRate">
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
							<ul class="mb-0 pl-3 rm-bullets">
								<li v-for="(err,index) in server_errors" :key="index">{{ err[0] }}</li>
							</ul>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Flat Rate:</label>
									<money 
						   			class="form-control"
						   			v-model.trim="$v.flat_rate.$model"
						   			:class="{'is-invalid': $v.flat_rate.$error }"
						   			:bind="money"></money>
						   		<div v-if="$v.flat_rate.$error">
				               	<span class="error-feedback" v-if="!$v.flat_rate.required">Please enter a flat rate</span>
				              	</div>
								</div>

							</div>
						</div>
					</template>
				</div>
				<div class="card-footer" v-if="!loading">
					<button type="submit" class="btn btn-primary" :disabled="saving">Save shipping rate</button>
				</div>
				</form>
			</div>

		</div>
	</div>
</template>

<script>
	import { required, minLength, maxLength, minValue, helpers,decimal } from 'vuelidate/lib/validators';
  	import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		props: ['admin'],
		data() {
			return {
				shippingRateID: '',
				flat_rate: '',
				loading: false,
				server_errors: [],
				saving: false,
				money: {
	         	decimal: '.',
	         	thousands: ',',
	         	prefix: '',
	         	suffix: '',
	         	precision: 2,
	         	masked: true,
	        	},
			}
		},
		components: {
			HalfCircleSpinner
		},
		validations() {
			return {
				flat_rate: {
					required
				}
			}
		},
		methods: {
			getShippingRate() {
				this.loading = true;
	        	axios.get('/api/get-shipping-rate')
	        	.then(response => {
	          	this.loading = false;
	          	this.shippingRateID = response.data.id;
	          	this.flat_rate = response.data.flat_rate;
	        	})
	        	.catch(error => {
	          	this.loading = false;
	          	console.log(error.response);
	        	});
			},
			saveShippingRate() {
				this.$v.$touch();

				if (!this.$v.$invalid)
				{
					this.saving = true;
					
					if (!this.shippingRateID)
					{
						axios.post('/api/set-shipping-rate', {
							flat_rate: this.flat_rate,
							admin_id: this.admin.id,
						})
						.then(response => {
							this.saving = false;
							if (response.data.success) {
		                	Swal('Shipping rate has been saved','', 'success');
		                	this.getShippingRate();
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
						axios.put('/api/update-shipping-rate/'+this.shippingRateID, {
							flat_rate: this.flat_rate,
							admin_id: this.admin.id,
						})
						.then(response => {
							this.saving = false;
							if (response.data.success) {
		                	Swal('Shipping rate has been saved','', 'success');
		                	this.getShippingRate();
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
			this.getShippingRate();
		}
	}
</script>
