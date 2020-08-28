<template>
	<div>
		<h5 class="mb-4">Order No. {{order.number}}</h5>
		<template v-if="hasSelected">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0">Request Form</h5>
				</div>
				<form @submit.prevent="submitRequest" ref="replacementForm" method="post" action="/replacement/request/store">
				<div class="card-body">
					<input type="hidden" name="_token" :value="csrf">
					<input type="hidden" name="order_number" :value="order.number">
					<input type="hidden" name="customer" :value="customer.id">
					<input type="hidden" name="order_product_id" :value="orderProductId">
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
              	<div class="alert alert-warning mb-1 mt-4">
              		By submitting this request for replacement you are agreeing to our <a href="javascript:void(0);" @click="showPolicy">replacement policy.</a>
              	</div>
				</div>
				<div class="card-footer clearfix">
					<button class="btn btn-primary float-right" type="submit" :disable="submitted">Submit</button>
					<button class="btn btn-danger float-right mr-2" @click="cancelRequest">Cancel</button>
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
                     <img :src="'/storage/products/'+product.inventory.product.product_image" class="media-object mr-2" width="17%" height="10%" alt="product image">
                     <div class="media-body pt-4">
                        <span class="d-block">{{ product.product_name }}</span>
                     </div>
                  </div>
					</td>
					<td class="align-middle text-center">{{ product.quantity }}</td>
					<td class="align-middle text-center"><button class="btn btn-sm btn-primary" @click="selectProduct(product.id)" :disabled="disabledSelect">Select</button></td>
				</tr>
			</tbody>
		</table>
		</template>
		<!-- Modal Component -->
        <b-modal id="replacementRequestModal"
                 ref="replacementRequestModal"
                 title="Replacement Policy"
                 ok-only
                 no-close-on-backdrop
                 no-close-on-esc
                 size="lg">
            <div>
               <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
               tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
               quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
               consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
               cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
               proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
        </b-modal>
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
				reason: '',
				qty: 1,
			}
		},
		validations() {
			return {
				qty: {required},
				reason: { required }
			}
		},
		methods: {
			selectProduct(prodId) {
				this.orderProductId = prodId;
				this.hasSelected = true;
				this.disabledSelect = true;
				let prod = this.orderedProducts.find(x => x.id == prodId);
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
			},
			submitRequest() {
				this.$v.$touch();

				if (!this.$v.$invalid) {
					this.submitted = true;
					this.$refs.replacementForm.submit();
				}
			},
			showPolicy() {
				this.$refs.replacementRequestModal.show();
			}
		}
	}
</script>