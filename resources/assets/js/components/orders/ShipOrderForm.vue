<template>
<div class="row justify-content-center">
	<div class="col-md-6">
		<div class="clearfix mb-2">
			<a :href="'/admin/order/'+id+'/details'" class="float-right"><i class="fa fa-chevron-left"></i>&nbsp;Back to Order Details</a>
		</div>
		<div class="card">
			<div class="card-body">
				<h2>Ship Order</h2>
				<div class="row mt-4">
					<div class="col-md-12">
						<form @submit.prevent="saveShipOrder">
							<div class="form-group row">
								<label for="track_number" class="col-sm-3 col-form-label">Order Number:</label>
								<div class="col-sm-8">
									<input type="text" name="" class="form-control" readonly v-model="order_id">
								</div>
							</div>
							<div class="form-group row">
								<label for="shipping_name" class="col-sm-3 col-form-label">Shipping Company:</label>
								<div class="col-sm-8">
									<select v-validate="'required'" class="form-control" v-model="shipping_company" :class="{'is-invalid': errors.has('shipping_name')}" name="shipping_name">
										<option value="LBC Express">LBC Express</option>
									</select>
									<span class="invalid-feedback">{{ errors.first('shipping_name') }}</span>
								</div>
							</div>
							<div class="form-group row">
								<label for="track_number" class="col-sm-3 col-form-label">Tracking Number:</label>
								<div class="col-sm-8">
									<input v-validate="'required'" type="text" name="track_numbera" class="form-control" :class="{'is-invalid': errors.has('track_number')}" v-model="tracking_number">
									<span class="invalid-feedback">{{ errors.first('track_number') }}</span>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 col-form-label"></label>
								<div class="col-sm-8">
									<button class="btn btn-primary" :disabled="saving"><i class="fa fa-check-circle"></i>&nbsp;<span v-if="!saving">Save & Send Shipment Email</span><span v-else>Saving & Sending...</span></button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	
</template>
<script>
	
export default {
	props:['id'],
	data() {
		return {
			order_id: this.id,
			shipping_company: '',
			tracking_number: '',
			saving: false,
			server_error: []
		}
	},
	methods: {
		saveShipOrder() {
			this.$validator.validate()
			.then((result) => {
				if (result)
				{
					this.saving = true;
					axios.post('/api/ship-order', {
						order_id: this.order_id,
						shipping_company: this.shipping_company,
						tracking_number: this.tracking_number
					})
					.then((response) => {
						this.saving = false;
						if (response.data.success)
						{
							Swal('Email Sent', 'Shipment details has been saved and sent to customer', 'success')
							.then((okay) => {
								if (okay)
								{
									this.$validator.reset();
									this.shipping_company = '';
									this.tracking_number = '';
									window.location.href = '/admin/order/'+this.order_id+'/details';
								}
							});
						}
					})
					.catch((error) => {
						this.saving = false;
						if (error.response.status == 422)
						{
							this.server_error = error.response.date.errors;
						}
					});		
				}
			});
				
		}
	}
}	
</script>