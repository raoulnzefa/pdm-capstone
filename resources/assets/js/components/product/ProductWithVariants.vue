<template>
	<div>
		<button class="btn btn-primary btn-sm" @click="showVariants"><i class="fas fa-list-ul"></i> Variants</button>
		<b-modal 
			:title="modal_title"
			ref="refsVariantsModal"
			size="lg"
			no-close-on-esc
			no-close-on-backdrop
			hide-header-close
			ok-only
			ok-title="Close"
			ok-variant="danger"
			@ok="closeVariantModal"
			:ok-disabled="disabledFormBtn"
			>
			<div>
				<button class="btn btn-primary" :disabled="disabledAdd" @click="addVariant" v-if="!hideAddBtn"><i class="fa fa-plus"></i> Add variant</button>
				<template v-if="isEdit">
					<div class="card mt-3 mb-3">
						<div class="card-header">
							Edit variant
						</div>
						<div class="card-body">
							<div class="form-group row">
						   	<label for="variantAdd1" class="col-sm-3 col-form-label text-right">Variant:</label>
						   	<div class="col-sm-6">
						   		<input type="text" class="form-control" id="variantAdd1" 
						   			placeholder="Enter variant value"
						   			tabindex="1"
						   			v-model.trim="$v.variantsEdit.value.$model"
						   			:class="{'is-invalid': $v.variantsEdit.value.$error}"
						   			ref="refEditValue">
						   		<div v-if="$v.variantsEdit.value.$error">
				                	<span class="error-feedback" v-if="!$v.variantsEdit.value.required">Please enter a variant value</span>
				                </div>
						   	</div>
						  	</div>
						  	<div class="form-group row">
						   	<label for="variantAdd2" class="col-sm-3 col-form-label text-right">Price:</label>
						   	<div class="col-sm-6">
						   		<money 
						   			class="form-control"
						   			id="variantAdd2" 
						   			v-model.trim="$v.variantsEdit.price.$model"
						   			:class="{'is-invalid': $v.variantsEdit.price.$error}"
						   			tabindex="2"
						   			:bind="money"></money>
						   		<div v-if="$v.variantsEdit.price.$error">
				                	<span class="error-feedback" v-if="!$v.variantsEdit.price.required">Please enter a variant price</span>
				                </div>
						   	</div>
						  	</div>
						  	<div class="form-group row">
						   	<label for="variantAdd3" class="col-sm-3 col-form-label text-right">Status:</label>
						   	<div class="col-sm-6">
						   		<select class="form-control" tabindex="3" v-model="variantsEdit.status">
						   			<option value="1">Enable</option>
						   			<option value="0">Disable</option>
						   		</select>
						   	</div>
						  	</div>
						</div><!--  card-body -->
						<div class="card-footer clearfix">
							<button class="btn btn-danger float-right" :disabled="disabledFormBtn" @click="cancelVariantOps">Cancel</button>
							<button class="btn btn-primary float-right mr-2" :disabled="disabledFormBtn" @click="updateVariant">Update</button>
						</div>
					</div>
				</template>
				<template v-if="isAdd">
					<div class="card mt-3 mb-3">
						<div class="card-header">
							Add variant
						</div>
						<div class="card-body">
							<div class="form-group row">
						   	<label for="addVar1" class="col-sm-3 col-form-label text-right">Variant:</label>
						   	<div class="col-sm-6">
						   		<input type="text" class="form-control" id="addVar1" 
						   			placeholder="Enter variant value"
						   			tabindex="1"
						   			v-model.trim="$v.variantsAdd.value.$model"
						   			:class="{'is-invalid': $v.variantsAdd.value.$error}"
						   			ref="refAddValue">
						   		<div v-if="$v.variantsAdd.value.$error">
				                	<span class="error-feedback" v-if="!$v.variantsAdd.value.required">Please enter a variant value</span>
				                </div>
						   	</div>
						  	</div>
						  	<div class="form-group row">
						   	<label for="addVar2" class="col-sm-3 col-form-label text-right">Price:</label>
						   	<div class="col-sm-6">
						   		<money 
						   			class="form-control"
						   			id="addVar2" 
						   			v-model.trim="$v.variantsAdd.price.$model"
						   			:class="{'is-invalid': $v.variantsAdd.price.$error}"
						   			tabindex="2"
						   			:bind="money"></money>
						   		<div v-if="$v.variantsAdd.price.$error">
				                	<span class="error-feedback" v-if="!$v.variantsAdd.price.required">Please enter a variant price</span>
				                </div>
						   	</div>
						  	</div>
						  	<div class="form-group row">
						   	<label for="addVar3" class="col-sm-3 col-form-label text-right">Quantity:</label>
						   	<div class="col-sm-6">
						   		<input type="text" class="form-control" id="addVar3" 
						   			placeholder="Enter variant quantity"
						   			tabindex="3"
						   			v-model.trim="$v.variantsAdd.stock.$model"
						   			:class="{'is-invalid': $v.variantsAdd.stock.$error}">
						   		<div v-if="$v.variantsAdd.stock.$error">
				                	<span class="error-feedback" v-if="!$v.variantsAdd.stock.required">Please enter a variant quantity</span>
				                	<template v-if="$v.variantsAdd.stock.required">
				                		<span class="error-feedback" v-if="!$v.variantsAdd.stock.numbersOnly">Please enter a valid value</span>
				                	</template>
				                </div>
						   	</div>
						  	</div>
						  	<div class="form-group row">
						   	<label for="addVar4" class="col-sm-3 col-form-label text-right">Critical level:</label>
						   	<div class="col-sm-6">
						   		<input type="text" class="form-control" id="addVar4" 
						   			placeholder="Enter variant critical level"
						   			tabindex="4"
						   			v-model.trim="$v.variantsAdd.critical_level.$model"
						   			:class="{'is-invalid': $v.variantsAdd.critical_level.$error}">
						   		<div v-if="$v.variantsAdd.critical_level.$error">
				                	<span class="error-feedback" v-if="!$v.variantsAdd.critical_level.required">Please enter a variant critical level</span>
				                	<template v-if="$v.variantsAdd.critical_level.required">
				                		<span class="error-feedback" v-if="!$v.variantsAdd.critical_level.numbersOnly">Please enter a valid value</span>
				                	</template>
				                </div>
						   	</div>
						  	</div>
						  	<div class="form-group row">
						   	<label for="variantAdd3" class="col-sm-3 col-form-label text-right">Status:</label>
						   	<div class="col-sm-6">
						   		<select class="form-control" tabindex="5" v-model="variantsAdd.status">
						   			<option value="1">Enable</option>
						   			<option value="0">Disable</option>
						   		</select>
						   	</div>
						  	</div>
						</div><!--  card-body -->
						<div class="card-footer clearfix">
							<button class="btn btn-danger float-right" :disabled="disabledFormBtn" @click="cancelVariantOps">Cancel</button>
							<button class="btn btn-primary float-right mr-2" :disabled="disabledFormBtn" @click="createVariant">Create</button>
						</div>
					</div>
				</template>
				<div>
					<table class="table mt-3">
						<thead>
							<tr class="bg-light">
								<th>ID</th>
								<th>Variant</th>
								<th>Price</th>
								<th>Status</th>
								<th>Action</th>
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
								<template v-if="!product_with_variants.length">
									<tr>
										<td colspan="5" class="text-center">
											No product variants.
										</td>
									</tr>
								</template>
								<template v-else>
									<tr v-for="(variant,index) in product_with_variants" :key="index">
										<td>{{ variant.inventory_number }}</td>
										<td>{{ variant.variant_value }}</td>
										<td>{{ formatMoney(variant.variant_price) }}</td>
										<td>{{ (variant.variant_status > 0) ? 'Enabled' : 'Disabled' }}</td>
										<td>
											<button class="btn btn-sm btn-primary" :disabled="disabledEdit" @click="editVariant(variant)"><i class="fa fa-edit"></i> Edit</button>
										</td>
									</tr>
								</template>
							</template>
						</tbody>
					</table>
				</div>
			</div>
	  	</b-modal>
	</div>
</template>

<script>
	import { required, minLength, maxLength, helpers, decimal } from 'vuelidate/lib/validators';
	import { HalfCircleSpinner } from 'epic-spinners';

	const moneyRegex = helpers.regex('moneyRegex', /^[1-9][0-9.]*$/);
	const numbersOnly = helpers.regex('numbersOnly', /^([1-9])[0-9]*$/);

	export default {
		props: ['admin', 'product'],
		data() {
			return {
				product_with_variants: [],
				loading: false,
				modal_title: this.product.product_name+' Variants',
				isEdit: false,
				loading: false,
				isAdd: false,
				variant_id: '',
				disabledEdit: false,
				disabledAdd: false,
				hideAddBtn: false,
				disabledFormBtn: false,
				hadDbOperations: false,
				variantsEdit: {
					value: '',
					price: '',
					status: 1
				},
				variantsAdd: {
					value: '',
					price: '',
					status: 1,
					stock: '',
					critical_level: ''
				},
				money: {
	         	decimal: '.',
	         	thousands: ',',
	         	prefix: '',
	         	suffix: '',
	         	precision: 2,
	         	masked: false
	        },
			}
		},
		components: {
			HalfCircleSpinner
		},
		validations() {
			if (this.isEdit) {
				return {
					variantsEdit: {
						value: { required },
						price: {
							required,
						}
					}
				}
			} else if (this.isAdd) {
				return {
					variantsAdd: {
						value: { required },
						price: {
							required,
						},
						stock: {
							required,
							numbersOnly
						},
						critical_level: {
							required,
							numbersOnly
						}
					}
				}
			}
		},
		methods: {
			showVariants() {
				this.getVariants();
				this.$refs.refsVariantsModal.show();
			},
			getVariants() {
				this.loading = true;
				axios.get('/api/product-with-variants/list/'+this.product.number)
				.then(res => {
					this.loading = false;
					this.product_with_variants = res.data;
				})
				.catch(err => {
					this.loading = false;
					console.log(err.response);
				});
			},
			addVariant() {
				this.isAdd = true;
				this.disabledEdit = true;
				this.isEdit = false;
				this.hideAddBtn = true;
			},
			editVariant(variant) {
				this.variant_id = variant.inventory_number;
				this.variantsEdit.value = variant.variant_value;
				this.variantsEdit.price = variant.variant_price;
				this.variantsEdit.status = variant.variant_status;
				this.isEdit = true;
				this.isAdd = false;
				this.hideAddBtn = true;
				this.disabledEdit = true;
			},
			cancelVariantOps() {
				this.resetThem();
			},
			resetThem() {
				if (this.isEdit) {
					this.$v.variantsEdit.$reset();
					this.isEdit = false;
				} else if (this.isAdd) {
					this.$v.variantsAdd.$reset();
					this.isAdd = false;
				}

				this.isAdd = false;
				this.hideAddBtn = false;
				this.disabledFormBtn = false;
				this.disabledEdit = false;
				this.variantsEdit = {
					value: '',
					price: '',
					status: 1
				};
				this.variantsAdd = {
					value: '',
					price: '',
					status: 1,
					stock: '',
					critical_level: ''
				};
				
			},
			createVariant() {
				this.$v.variantsAdd.$touch();

				if (!this.$v.variantsAdd.$invalid) {
					this.disabledFormBtn = true;

					axios.post('/api/product-with-variants/create', {
							admin_id: this.admin.id,
							variant_value: this.variantsAdd.value,
							variant_price: this.variantsAdd.price,
							variant_status: this.variantsAdd.status,
							inventory_stock: this.variantsAdd.stock,
							inventory_critical_level: this.variantsAdd.critical_level,
							product_number: this.product.number
						})
						.then(response => {
							this.disabledFormBtn = false;

							if (response.data.success) {
								Swal('Variant has been created', '', 'success')
								.then((okay) => {
									if (okay) {
										this.$v.variantsAdd.$reset();
										this.getVariants();
										this.hadDbOperations = true;
										this.resetThem();
									}
								})
							}

						})
						.catch(error => {
							this.disabledFormBtn = false;
							if(error.response.status == 422) {
								this.server_errors = error.response.data.errors;
							}
						});
				}
			},
			updateVariant() {
				this.$v.variantsEdit.$touch();

				if (!this.$v.variantsEdit.$invalid) {
					this.disabledFormBtn = true;

					axios.put('/api/product-with-variants/update/'+this.variant_id, {
							admin_id: this.admin.id,
							variant_value: this.variantsEdit.value,
							variant_price: this.variantsEdit.price,
							variant_status: this.variantsEdit.status
						})
						.then(response => {
							this.disabledFormBtn = false;

							if (response.data.success) {
								Swal('Variant has been updated', '', 'success')
								.then((okay) => {
									if (okay) {
										this.$v.variantsEdit.$reset();
										this.getVariants();
										this.hadDbOperations = true;
										this.resetThem();
									}
								})
							}

						})
						.catch(error => {
							this.disabledFormBtn = false;
							if(error.response.status == 422) {
								this.server_errors = error.response.data.errors;
							}
						});
				}
			},
			closeVariantModal() {
				this.$refs.refsVariantsModal.hide();
				if (this.hadDbOperations) {
					this.$bus.$emit('refreshTable', true);
					this.hadDbOperations = false;
				}
			},
			formatMoney(value) {
				 return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(value);
			}
			
		}
	}
</script>