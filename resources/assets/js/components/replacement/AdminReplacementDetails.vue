<template>
	<div class="row">
		<div class="col-md-12">
			<div class="card mt-4">
				<div class="card-header clearfix">
					<h2 class="float-left mb-0">Replacement Details</h2>
					<a href="/admin/replacements" class="float-right btn btn-outline-secondary">Back</a>
				</div>
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
						<div class="row" v-if="!details.length">
							<div class="col-md-12">
								<div class="form-group row mt-4">
			                  <label class="col-sm-3 col-form-label text-right">ID:</label>
			                  <div class="col-sm-6">
			                     <input type="text" class="form-control" :value="details.id"readonly>
			                  </div>
			              	</div>
			              	<div class="form-group row mt-4">
			                  <label class="col-sm-3 col-form-label text-right">Order number:</label>
			                  <div class="col-sm-6">
			                     <input type="text" class="form-control" :value="details.order_number" readonly>
			                  </div>
			              	</div>
			              	<div class="form-group row mt-4">
			                  <label class="col-sm-3 col-form-label text-right">Quantity to replaced:</label>
			                  <div class="col-sm-6">
			                     <input type="text" class="form-control" :value="details.quantity"readonly>
			                  </div>
			              	</div>
			              	<div class="form-group row mt-4">
			                  <label class="col-sm-3 col-form-label text-right">Status:</label>
			                  <div class="col-sm-6">
			                     <input type="text" class="form-control" :value="details.status"readonly>
			                  </div>
			              	</div>
			              	<div class="form-group row mt-4">
			                  <label class="col-sm-3 col-form-label text-right">Created:</label>
			                  <div class="col-sm-6">
			                     <input type="text" class="form-control" :value="details.request_created"readonly>
			                  </div>
			              	</div>
			              	<div class="form-group row mt-4">
			                  <label class="col-sm-3 col-form-label text-right">Reason:</label>
			                  <div class="col-sm-6">
			                     <textarea class="form-control text-justify" :value="details.reason" rows="6" readonly></textarea>
			                  </div>
			              	</div>
								<table class="table table-bordered mt-4">
									<thead>
										<tr>
											<th width="15%">Inventory No.</th>
											<th>Product</th>
											<th width="10%">Stock</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="align-middle">{{details.inventory_number}}</td>
											<td class="align-middle">
												<div class="media">
					                        <img :src="'/storage/products/'+details.product.product_image" class="media-object mr-2" width="12%" height="8%" alt="product image">
					                        <div class="media-body pt-4">
					                           <span class="d-block">{{ details.product_name }}</span>
					                        </div>
					                     </div>
											</td>
											<td class="align-middle">{{details.inventory.inventory_stock}}</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</template>
				</div>
				<div class="card-footer clearfix" v-if="!loading">
					<template v-if="details.status === 'Pending'">
						<button class="btn btn-primary float-right" @click="approveRequest">Approve</button>
						<button class="btn btn-danger float-right mr-2" @click="declineRequest">Decline</button>
					</template>
					<template v-if="details.status === 'Approved'">
						<button class="btn btn-primary float-right" @click="replaceProduct">Replace product</button>
					</template>
				</div>
			</div><!-- card -->
			<b-modal ref="requestResponseModal"
    		   	title="Sending Email"
	    		   no-close-on-backdrop
	    		   no-close-on-esc
	    		   hide-header-close
	    		   hide-footer
	    		   hide-header
	    		   no-fade
	    		   centered
	    		   size="sm">
			   <center>
				   <half-circle-spinner
			         :animation-duration="1000"
			         :size="50"
			         color="#ff1d5e"
			         class=""
			         />
			      <span class="mt-2 d-block">&nbsp;Sending email...</span>
			   </center>
			</b-modal>
		</div><!-- col-md-12 -->
	</div><!-- row -->
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		props: ['id', 'admin'],
		data() {
			return {
				details: [],
				loading: false,
				product_image: '',
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getDetails() {
				this.loading = true;
				axios.get('/api/replacement/'+this.id)
				.then(response => {
					this.loading = false;
					this.details = response.data;
					this.product_image = this.details.inventory.product.product_image;
				})
				.catch(error => {
					this.loading = false;
					console.log(error.response);
				});
			},
			approveRequest() {
				Swal({
					  title: 'You are about to approve this replacement request?',
					  text: '',
					  type: 'warning',
					  showCancelButton: true,
					  confirmButtonText: 'Yes',
					  cancelButtonText: 'No'
					}).then((result) => {
					  if (result.value) {

					  	this.$refs.requestResponseModal.show();

					  	axios.post('/api/replacement/approve', {
					  		replacement_id: this.details.id,
					  		admin_id: this.admin.id,
					  	})
						.then(response => {
							let res = response.data
							if (res.success) {
								this.$refs.requestResponseModal.hide();
								Swal('Replacement status has been updated','', 'success');
								this.getDetails();
							}
						})
						.catch(error => {
							this.$refs.requestResponseModal.hide();
							console.log(error.response)
						})
					  }
					});
			},
			declineRequest() {
				Swal({
				  title: 'You are about to decline this replacement request?',
				  text: '',
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Yes',
				  cancelButtonText: 'No'
				}).then((result) => {
				  if (result.value) {
				  	this.$refs.requestResponseModal.show();

				  	axios.post('/api/replacement/decline', {
				  		replacement_id: this.details.id,
				  		admin_id: this.admin.id,
				  	})
					.then(response => {
						let res = response.data

						if (res.success) {
							this.$refs.requestResponseModal.hide();
							Swal('Replacement status has been updated','', 'success');
							this.getDetails();
						}
					})
					.catch(error => {
						this.$refs.requestResponseModal.hide();
						console.log(error.response)
					})
				  }
				});
			},
			replaceProduct() {
				Swal({
				  title: 'You are about to replace the product?',
				  text: '',
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Yes',
				  cancelButtonText: 'No'
				}).then((result) => {
				  if (result.value) {
				  	this.$refs.requestResponseModal.show();

				  	axios.post('/api/replacement/replaced-product', {
				  		replacement_id: this.details.id,
				  		admin_id: this.admin.id,
				  	})
					.then(response => {
						let res = response.data

						if (res.success) {
							this.$refs.requestResponseModal.hide();
							Swal('Replacement status has been updated','', 'success');
							this.getDetails();
						}
					})
					.catch(error => {
						this.$refs.requestResponseModal.hide();
						console.log(error.response)
					})
				  }
				});
			}
		},
		mounted() {
			this.getDetails();
		}
	}
</script>