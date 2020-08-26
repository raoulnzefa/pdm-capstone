<template>
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<h2 class="card-header-text">Inventory</h2>
				</div>
				<div class="card-body">
					<div class="row mb-4">
						<div class="col-md-4">
							<div class="input-group">
							  	<input type="text" class="form-control" placeholder="Search product" v-model="search_val">
							  	<div class="input-group-append">
							    	<button class="btn btn-secondary" type="button" @click="searchProduct"><i class="fa fa-search"></i> Search</button>
							    	<div class="input-group-append" v-if="on_search">
			                        	<button class="btn btn-outline-danger" type="button" @click="clearSearch">Clear</button>
			                        </div>
							  	</div>
							</div>
						</div>
					</div>
					<div>
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>SKU</th>
									<th>Product</th>
									<th width="15%">Stocks</th>
									<th width="15%">Critical Level</th>
									<th class="text-center">Action</th>
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
									<template v-if="!inventory_list.length">
										<tr>
											<td colspan="5" align="center">No inventory.</td>
										</tr>
									</template>
									<template v-else>
										<tr v-for="(product, index) in inventory_list" :class="[product.quantity <= product.critical_level ? 'bg-danger text-light' : '']">
											<td class="align-middle">{{ product.sku }}</td>
											<td>
												<div>
													<img :src="'/storage/products/'+product.product.image" width="90">
													<span>{{ product.name }}</span>
												</div>
											</td>
											<td class="align-middle"><b class="text-success">{{product.quantity}}</b></td>
											<td class="align-middle"><b class="text-danger" :class="[product.quantity <= product.critical_level ? 'text-light' : '']">{{product.critical_level}}</b></td>
											<td class="align-middle" align="center"><button class="btn btn-dark btn-sm" :disabled="addStockBtn" @click="showStockModal(product)"><i class="fa fa-plus"></i> Add</button>&nbsp;<button class="btn btn-dark btn-sm" :disabled="adjustStockBtn" @click="showAdjustModal(product)"><i class="fa fa-edit"></i> Adjust</button></td>
										</tr>
									</template>
								</template>
							</tbody>
						</table>
					</div>
					<template v-if="!loading">
					<div class="row" v-if="inventory_list.length > 0">
						<div class="col-md-8">
							<nav>
								<ul class="pagination">
									<li :class="{disabled:!pagination.first_link}" class="page-item"><a href="javascript:void(0);" @click="getInventory(pagination.first_link)" class="page-link">&laquo;</a></li>
									<li :class="{disabled:!pagination.prev_link}" class="page-item"><a href="javascript:void(0);" @click="getInventory(pagination.prev_link)" class="page-link">Prev</a></li>
									<li v-for="n in pagination.last_page" v-bind:key="n" :class="{active: pagination.current_page == n}" class="page-item"><a href="javascript:void(0);" @click="getInventory(pagination.path_page + n)" class="page-link">{{ n }}</a></li>
									<li :class="{disabled:!pagination.next_link}" class="page-item"><a href="javascript:void(0);" @click="getInventory(pagination.next_link)" class="page-link">Next</a></li>
									<li :class="{disabled:!pagination.last_link}" class="page-item"><a href="javascript:void(0);" @click="getInventory(pagination.last_link)" class="page-link">&raquo;</a></li>
								</ul>
							</nav>
						</div>
						<div class="col-md-4">
							<p class="text-right">Page: {{ pagination.from_page }} - {{ pagination.to_page }}
							Total: {{ pagination.total_page }}</p>
						</div>
					</div>
					</template>
				</div>
			</div><!-- card -->
			<!-- Modal Component -->
		    <b-modal id="stockModal"
		             ref="stockModal"
		             :title="modal_title"
		             no-close-on-backdrop
		             no-close-on-esc
		             centered
		             no-fade
		             ok-title="Save"
		             :ok-disabled="addStockBtn"
		             :cancel-disabled="addStockBtn"
		             @ok="submitAddStock"
		             @cancel="cancelAddStock"
		             @hidden="cancelAddStock"
		             @shown="focusOnQuantity"
		             >
		      <form @submit.prevent="saveAddStock" v-if="!adjust_stock">
		        <b-form-group id=""
                          label="Quantity to Add:"
                          label-for="qtyToAdd">
	                <b-form-input id="qtyToAdd"
	                              type="number"
	                              ref="addQty"
	                              v-model.trim="$v.quantity.$model"
	                              :class="{'is-invalid':$v.quantity.$error}">
	                </b-form-input>
	                <div v-if="$v.quantity.$error">
	                	<span class="error-feedback" v-if="!$v.quantity.required">Please input a quantity</span>
	                	<span class="error-feedback" v-if="!$v.quantity.quantityPattern">Please input a valid quantity</span>
	                	<!-- <span class="error-feedback d-block" v-if="!$v.quantity.maxLength">Quantity may not be greater than {{ $v.quantity.$params.maxLength.max }} characters.</span> -->
	                	<span class="error-feedback d-block" v-if="!$v.quantity.maxValue">Please input a value not greater than {{ $v.quantity.$params.maxValue.max }}.</span>
	                </div>
                </b-form-group>
		      </form>
		      <div slot="modal-ok">
		      	<span v-if="addStockBtn"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>Save
		      </div>
		    </b-modal>

		    <!-- Adjust Modal Component -->
		    <b-modal id="adjustStockModal"
		             ref="adjustStockModal"
		             :title="modal_adjust_title"
		             no-close-on-backdrop
		             no-close-on-esc
		             centered
		             no-fade
		             ok-title="Adjust"
		             :ok-disabled="adjustStockBtn"
		             :cancel-disabled="adjustStockBtn"
		             @ok="submitAdjustStock"
		             @cancel="cancelAdjustStock"
		             @hidden="cancelAdjustStock"
		             @shown="focusOnQuantity"
		             >
		      <form @submit.prevent="saveAdjustStock" v-if="adjust_stock">
		      	<b-form-group id=""
                          label="Current Quantity:"
                          label-for="curQty">
	                <b-form-input id="curQty"
	                              type="number"
	                              v-model="current_qty"
	                              readonly>
	                </b-form-input>
                </b-form-group>
		        <b-form-group id=""
                          label="Quantity:"
                          label-for="qtyToAdjust">
	                <b-form-input id="qtyToAdjust"
	                              type="number"
	                              ref="adjustQty"
	                              v-model.trim="$v.adjust_qty.$model"
	                              :class="{'is-invalid':$v.adjust_qty.$error}">
	                </b-form-input>
	                <div v-if="$v.adjust_qty.$error">
	                	<span class="error-feedback" v-if="!$v.adjust_qty.required">Please input a quantity</span>
	                	<span class="error-feedback d-block" v-if="!$v.adjust_qty.maxValue">Please input a value not greater than {{ $v.adjust_qty.$params.maxValue.max }}.</span>
	                	<!-- <template v-if="$v.adjust_qty.maxValue">
	                		<span class="error-feedback d-block" v-if="!$v.adjust_qty.maxLength">Quantity may not be greater than {{ $v.adjust_qty.$params.maxLength.max }} characters.</span>	
	                	</template>
	                	
	                	 -->
	                </div>
                </b-form-group>
		      </form>
		      <div slot="modal-ok">
		      	<span v-if="adjustStockBtn"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>Adjust
		      </div>
		    </b-modal>
		</div>
	</div>
</template>
<script>
	import { required, minLength, maxLength, maxValue, helpers, numeric } from 'vuelidate/lib/validators';
	import { HalfCircleSpinner } from 'epic-spinners'

	const quantityPattern = helpers.regex('quantityPattern', /^([1-9])[0-9]*$/);
	export default {
		props: ['admin'],
		data() {
			return {
				inventory_list: [],
				loading: false,
				addStockBtn: false,
				modal_title: '',
				invent_sku: '',
				server_error: [],
				quantity: 1,
				pagination: {},
				search_val: '',
				on_search: false,
				modal_adjust_title: '',
				adjustStockBtn: false,
				adjust_stock: false,
				adjust_qty: '',
				current_qty: '',
				adjust_sku: ''
			}
		},
		components: {
			HalfCircleSpinner
		},
		validations() {
			if (!this.adjust_stock)
			{
				return {
					quantity: {
						required,
						maxValue: maxValue(1000),
						maxLength: maxLength(4),
						quantityPattern
					}
				}
			}
			else
			{
				return {
					adjust_qty: {
						required,
						maxValue: maxValue(1000),
						maxLength: maxLength(4)
					}
				}
			}
		},
		methods: {
			getInventory(pagi) {
				this.loading = true;

				if (!this.search_val)
				{
					pagi = pagi || '/api/inventory';
				}
				else
				{
					pagi = '/api/inventory?search='+this.search_val;
				}

				axios.get(pagi)
	       		.then((response) => {
	       			this.loading = false;
	       			console.log(response.data);
	       			this.inventory_list = response.data.data;
	       			this.pagination = {
	       				current_page: response.data.current_page,
	       				last_page: response.data.last_page,
	       				from_page: response.data.from,
	       				to_page: response.data.to,
	       				total_page: response.data.total,
	       				path_page: response.data.path+'?page=',
	       				first_link: response.data.first_page_url,
	       				last_link: response.data.last_page_url,
	       				prev_link: response.data.prev_page_url,
	       				next_link: response.data.next_page_url
	       			};
	       			
	       		})
	       		.catch((error) => {
	       			this.loading = false;
	       			console.log(error.response);
	       		});
			},
			showStockModal(invent) {
				this.modal_title = invent.name;
				this.invent_sku = invent.sku;
				this.$refs.stockModal.show();
				this.adjust_stock = false;
			},
			cancelAddStock() {
				this.adjust_stock = false
				this.addStockBtn = false;
				this.modal_title = '';
				this.quantity = 1;
				this.server_error = [];
				this.$nextTick(() => { this.$v.$reset() });
			},
			saveAddStock() {
				this.$v.$touch();

				if (!this.$v.$invalid)
				{
					this.addStockBtn = true;
					axios.put('/api/inventory/add-stock/'+this.invent_sku, {
						quantity: this.quantity,
						admin_id: this.admin.id,
						role: this.admin.role
					})
					.then((response) => {
						this.addStockBtn = false;
						if (response.data.success)
						{
							Swal('New stock has been added.','', 'success')
							.then((okay) => {
								if (okay)
								{
									this.$refs.stockModal.hide();
									this.getInventory();
								}
							});
						}
					})
					.catch((error) => {
						this.addStockBtn = false;
						if (error.response.status == 422)
						{
							this.server_error = error.response.data.errors;
						}
					});
				}
			},
			submitAddStock(evt)
			{
				evt.preventDefault();
				this.saveAddStock();
			},
			focusOnQuantity(e)
			{
				if (!this.adjust_stock)
				{
					this.$refs.addQty.focus();
				}
				else
				{
					this.$refs.adjustQty.focus();
				}
				
			},
			searchProduct()
			{
				this.on_search = true;

				if (!this.search_val)
				{
					this.on_search = false;
					return null;
				}

				this.getInventory();
			},
			clearSearch()
			{
				this.search_val = '';
				this.on_search = false;
				this.getInventory();
			},
			showAdjustModal(product) {
				this.adjust_stock = true;
				this.current_qty = product.quantity;
				this.adjust_sku = product.sku;
				this.modal_adjust_title = "Adjust quantity for "+product.sku;
				this.$refs.adjustStockModal.show();
			},
			saveAdjustStock() {
				this.adjustStockBtn = true;
					axios.put('/api/inventory/adjust/'+this.adjust_sku, {
						quantity: this.adjust_qty,
						admin_id: this.admin.id,
					})
					.then((response) => {
						this.adjustStockBtn = false;
						if (response.data.success)
						{
							Swal('Quantity has been adjusted','', 'success')
							.then((okay) => {
								if (okay)
								{
									this.$refs.adjustStockModal.hide();
									this.getInventory();
								}
							});
							
						}
					})
					.catch((error) => {
						this.adjustStockBtn = false;
						console.log(error.response);
						if (error.response.status == 422)
						{
							this.server_error = error.response.data.errors;
						}
					});
			},
			cancelAdjustStock() {
				this.adjustStockBtn = false;
				this.adjust_stock = false;
				this.modal_adjust_title = '';
				this.adjust_qty = '';
				this.server_error = [];
				this.$nextTick(() => { this.$v.$reset() });
			},
			submitAdjustStock(evt) {
				evt.preventDefault();
				this.$v.$touch();
				if (!this.$v.$invalid)
				{
					this.saveAdjustStock();
				}
			},
		},
		created() {
			this.getInventory();
		}
	}
</script>