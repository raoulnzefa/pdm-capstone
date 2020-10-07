<template>
	<div>
		<table class="table table-bordered table-hover table-striped">
			<thead>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Order No.</th>
					<th width="45%">Product</th>
					<th>Status</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<template v-if="loading">
					<tr>
						<td colspan="6" class="text-center">
							<center>
								<half-circle-spinner
                         :animation-duration="1000"
                         :size="30"
                         color="#ff1d5e"
                       />
							</center>
						</td>
					</tr>
				</template>
				<template v-else>
					<template v-if="replacements.length">
						<tr v-for="(item, index) in replacements" :key="index">
							<td class="align-middle">
								<span style="font-size: 20px;" :class="item.status_update > 0 ? 'text-danger' : 'text-secondary'"><i class="fa fa-exclamation-circle"></i></span>
							</td>
							<td class="align-middle text-center">{{item.id}}</td>
							<td class="align-middle text-center">{{item.order_number}}</td>
							<td class="align-middle">
								<div class="media">
	                        <img :src="item.inventory.product.product_image_url" class="media-object mr-2" width="18%" height="12%" alt="product image">
	                        <div class="media-body pt-3">
	                           <span class="d-block">{{ item.product_name }}</span>
	                        </div>
	                     </div>
							</td>
							<td class="align-middle text-center">{{item.status}}</td>
							<td class="align-middle text-center"><button class="btn btn-sm btn-primary" @click="showDetails(item.id)">Details</button></td>
						</tr>
					</template>
					<template v-else>
						<tr>
							<td class="text-center" colspan="6">No replacements.</td>
						</tr>
					</template>
				</template>
			</tbody>
		</table>
		<!-- Modal Component -->
     <b-modal id="replacementDetailsModal"
              ref="replacementDetailsModal"
              title="Details"
              ok-only
              ok-title="Close"
              hide-header-close
              no-close-on-backdrop
              no-close-on-esc
              @ok="closeDetailsModal"
              size="lg">
         <div>
           	<div class="form-group row">
           		<label class="col-sm-3 col-form-label">Order Number:</label>
           		<div class="col-sm-7">
           			<input type="text" class="form-control" :value="orderNumber" readonly>
           		</div>
           	</div>
         	<div class="form-group row">
           		<label class="col-sm-3 col-form-label">Product:</label>
           		<div class="col-sm-7">
           			<input type="text" class="form-control" :value="productName" readonly>
           		</div>
           	</div>
           	<div class="form-group row">
           		<label class="col-sm-3 col-form-label">Quantity:</label>
           		<div class="col-sm-7">
           			<input type="text" class="form-control" :value="quantity" readonly>	
           		</div>
           	</div>
         	<div class="form-group row">
           		<label class="col-sm-3 col-form-label">Status:</label>
           		<div class="col-sm-7">
           			<input type="text" class="form-control" :value="status" readonly>
           		</div>
           	</div>
           	<div class="form-group row">
           		<label class="col-sm-3 col-form-label">Date Created:</label>
           		<div class="col-sm-7">
           			<input type="text" class="form-control" :value="dateCreated" readonly>
           		</div>
           	</div>
         	<div class="form-group row">
           		<label class="col-sm-3 col-form-label">Reason:</label>
           		<div class="col-sm-7">
           			<textarea class="form-control" rows="5" :value="reason" readonly></textarea>	
           		</div>
           	</div>
         </div>
     </b-modal>
	</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		props: ['customer'],
		data() {
			return {
				replacements: [],
				loading: false,
				orderNumber: '',
				productName: '',
				quantity: '',
				status: '',
				dateCreated: '',
				reason: '',
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getCustomerReplacements() {
				this.loading =  true;
				axios.get('/api/replacements/customer/'+this.customer.id)
				.then(response => {
					this.loading = false;
					this.replacements = response.data;
				})
				.catch(error => {
					this.loading = false;
				});
			},
			showDetails(id) {
				let item = this.replacements.find(x => x.id === id);
				this.orderNumber = item.order_number;
				this.productName = item.product_name;
				this.quantity = item.quantity;
				this.status = item.status;
				this.dateCreated = item.request_created;
				this.reason = item.reason;
				this.$refs.replacementDetailsModal.show();
				axios.put('/api/request-update-status/'+id)
				.then(response => {
					if (response.data.success)
					{
						this.$bus.$emit('replacement-status-badge', true);
					}
				})
				.catch(error => {
					console.log(error.response);
				});
			},
			closeDetailsModal() {
				this.orderNumber = "";
				this.productName = "";
				this.quantity = "";
				this.status = "";
				this.dateCreated = "";
				this.reason = "";
				this.$refs.replacementDetailsModal.hide();
			}
		},
		mounted() {
			this.getCustomerReplacements();
		}
	}
</script>