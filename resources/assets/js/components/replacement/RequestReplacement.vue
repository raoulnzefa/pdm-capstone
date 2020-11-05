<template>
	<div>
		<h5 class="mb-4">Order No. {{order.number}}</h5>
		<template v-if="hasSelected">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0">Request Form</h5>
				</div>
				<form @submit.prevent="submitRequest" ref="replacementForm" method="post" enctype="multipart/form-data">
				<div class="card-body">
					<template v-if="server_errors.length != 0">
	              <div class="alert alert-danger">
	                <ul class="mb-0">
	                  <li v-for="(err,index) in server_errors" :key="index">{{ err[0] }}</li>
	                </ul>
	              </div>
	            </template>
					<div class="form-group row mt-4">
                  <label class="col-sm-3 col-form-label text-right">Product:</label>
                  <div class="col-sm-8">
                     <input type="text" class="form-control" :value="productName" readonly>
                  </div>
              	</div>
              	<div class="form-group row">
                  <label class="col-sm-3 col-form-label text-right">Qty:</label>
                  <div class="col-sm-3">
                     <select class="form-control" name="quantity" v-model.trim="$v.qty.$model">
                     	<option v-for="(x,index) in productQty" :key="index">{{x}}</option>
                     </select>
                     <div v-if="$v.qty.$error">
                     	<span class="error-feedback" v-if="!$v.qty.required">Please select a quantity</span>
                     </div>
                  </div>
              	</div>
              	<div class="form-group row">
                  <label class="col-sm-3 col-form-label text-right">Reason:</label>
                  <div class="col-sm-8">
                  	<textarea class="form-control" rows="5" 
                  		name="reason" 
                  		v-model.trim="$v.reason.$model"
                  		:class="{'is-invalid': $v.reason.$error}" 
                  		placeholder="Enter your reason"></textarea>
                  	<div v-if="$v.reason.$error">
                     	<span class="error-feedback" v-if="!$v.reason.required">Please enter your reason</span>
                     </div>
                  </div>
              	</div>
              	<div class="form-group row">
                  <label class="col-sm-3 col-form-label text-right">Upload photos:</label>
                  <div class="col-sm-8">
                  	<input type="file" name="defective_product"
                  	tabindex="3"
                  	class="form-control"
                  	:class="{'is-invalid': $v.defectivePhotos.$error}"
                  	@change="selectPhotos"
                  	multiple>
                  	<div v-if="$v.reason.$error">
                     	<span class="error-feedback" v-if="!$v.defectivePhotos.required">Photos are required</span>
                     </div>
                  </div>
              	</div>
              	<div class="alert alert-warning mb-1 mt-4">
              		By submitting this request for replacement you are agreeing to our <a href="/return-policy" target="_blank">return policy.</a>
              	</div>
				</div>
				<div class="card-footer clearfix">
					<button class="btn btn-primary float-right" type="submit" :disabled="submitted"><i class="fa fa-refresh fa-spin" v-if="submitted"></i> Submit</button>
					<button class="btn btn-danger float-right mr-2" @click="cancelRequest" :disabled="submitted">Cancel</button>
				</div>
				</form>
			</div>
		</template>
		<template v-else>
		<p class="mb-4">Which product do you want to request for replacement?</p>
		<table class="table table-bordered mt-3">
			<thead>
				<tr>
					<th width="70%">Ordered Products</th>
					<th class="text-center">Qty.</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(product, index) in orderedProducts" :key="index">
					<td class="align-middle">
						<div class="media">
                     <img :src="product.inventory.product.product_image_url" class="media-object mr-2" width="17%" height="10%" alt="product image">
                     <div class="media-body pt-4">
                        <span class="d-block">{{ product.product_name }}</span>
                     </div>
                  </div>
					</td>
					<td class="align-middle text-center">{{ product.quantity }}</td>
					<td class="align-middle text-center"><button class="btn btn-sm btn-primary" @click="selectProduct(product)" :disabled="disabledSelect">Select</button></td>
				</tr>
			</tbody>
		</table>
		</template>
	</div>
</template>

<script>
	import { required } from 'vuelidate/lib/validators';

	export default {
		props: ['order', 'customer'],
		data() {
			return {
				orderedProducts: this.order.order_products,
				csrf: document.head.querySelector('meta[name="csrf-token"]').content,
				submitted: false,
				hasSelected: false,
				disabledSelect: false,
				orderProductId: '',
				productName: '',
				productQty: '',
				inventoryNumber: '',
				reason: '',
				defectivePhotos: [],
				qty: 1,
				server_errors: [],
			}
		},
		validations() {
			return {
				qty: {required},
				reason: { required },
				defectivePhotos: { required }
			}
		},
		methods: {
			selectPhotos(e) {
				const file = e.target.files;

				if (!file.length)
					return;

				this.defectivePhotos = file;
			},
			selectProduct(product) {
				this.orderProductId = product.id;
				this.inventoryNumber = product.inventory_number;
				this.hasSelected = true;
				this.disabledSelect = true;
				let prod = this.orderedProducts.find(x => x.id == product.id);
				this.productName = prod.product_name;
				this.productQty = prod.quantity;
			},
			cancelRequest() {
				this.hasSelected = false;
				this.disabledSelect = false;
				this.productName = "";
				this.productQty = "";
				this.reason = "";
				this.qty = 1;
				this.orderProductId = "";
				this.defectivePhotos = "";
				this.$nextTick(() => {this.$v.$reset()});

			},
			submitRequest() {
				this.$v.$touch();

				if (!this.$v.$invalid) {
					this.submitted = true;

					const form = new FormData();
					form.append('order_product_id', this.orderProductId);
					form.append('customer', this.customer.id);
					form.append('order_number', this.order.number);
					form.append('product_name', this.productName);
					form.append('inventory_number', this.inventoryNumber);
					form.append('reason', this.reason);
					form.append('quantity', this.qty);

					for (let i = 0; i < this.defectivePhotos.length; i++) {
						form.append(`photos[${i}]`, this.defectivePhotos[i]);
					}
				

					axios.post('/api/replacement/submit', form)
					.then(response => {
						this.submitted = false;
						if (response.data.success) {
							Swal('Request submitted.', '','success')
							.then(okay => {
								if (okay) {
									window.location.href='/my-account/replacements';
								}
							});
						}
					})
					.catch(error => {
						this.submitted = false;
						if (error.response.status === 422) {
							this.server_errors = error.response.data.errors;
						}
					});

					
				}
			},
			showPolicy() {
				this.$refs.replacementRequestModal.show();
			}
		}
	}
</script>