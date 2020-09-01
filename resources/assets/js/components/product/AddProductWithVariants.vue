<template>
	<div>
		<button class="btn btn-primary" @click="addProductWithVariants"><i class="fas fa-plus"></i> Add product with variants</button>
		<b-modal 
			title="Add product with variants"
			ref="refsAddWithVariantModal"
			size="lg"
			no-close-on-esc
			no-close-on-backdrop
			hide-header-close
			ok-title="Create"
			:ok-disabled="isBtnClicked"
			@ok="submitProductVariant"
			@cancel="cancelProductWithVariant"
			@shown="focusOnProdName"
			@hidden="cancelProductWithVariant">
				<div class="alert alert-danger" v-if="server_errors.length != 0">
					<ul class="mb-0">
						<li v-for="(err,index) in server_errors" :key="index">{{ err[0] }}</li>
					</ul>
				</div>
				<div class="form-group row">
			   	<label for="colAddWithVariant1" class="col-sm-3 col-form-label">Product name:</label>
			   	<div class="col-sm-9">
			   		<input type="text" class="form-control" id="colAddWithVariant1" 
			   			placeholder="Enter product name"
			   			tabindex="1"
			   			v-model.trim="$v.forms.product_name.$model"
			   			:class="{'is-invalid': $v.forms.product_name.$error}"
			   			ref="addProductVariantInput">
			   		<div v-if="$v.forms.product_name.$error">
	                	<span class="error-feedback" v-if="!$v.forms.product_name.required">Please enter product name</span>
	                </div>
			   	</div>
			  	</div>
			  	<div class="form-group row">
			   	<label for="colAddWithVariant2" class="col-sm-3 col-form-label">Category:</label>
			   	<div class="col-sm-9">
			   		<select class="form-control" id="colAddWithVariant2"
			   			tabindex="2"
			   			v-model.trim="$v.forms.category.$model"
			   			:class="{'is-invalid': $v.forms.category.$error }">
			   			<option value="" selected disabled>Select category</option>
			   			<option v-for="(category, index) in categories" :value="category.id">{{ category.name }}</option>
			   		</select>
			   		<div v-if="$v.forms.category.$error">
							<span class="error-feedback" v-if="!$v.forms.category.required">Please select a category</span>	
						</div>
			   	</div>
			  	</div>
			  	<div class="form-group row">
			   	<label for="colAddWithVariant3" class="col-sm-3 col-form-label">Description:</label>
			   	<div class="col-sm-9">
			   		<textarea class="form-control" id="colAddWithVariant3" rows="4"
			   			placeholder="Enter description"
			   			tabindex="3"
			   			v-model.trim="$v.forms.description.$model"
			   			:class="{'is-invalid': $v.forms.description.$error }"
			   			></textarea>
			   		<div v-if="$v.forms.description.$error">
							<span class="error-feedback" v-if="!$v.forms.description.required">Please enter a description</span>	
						</div>
			   	</div>
			  	</div>
			  	<div class="card mt-3" v-for="(v, index) in $v.forms.variants.$each.$iter" :key="index">
			  		<div class="card-header clearfix">
			  			Variant {{ parseInt(index) + 1 }}
			  			<button class="btn btn-sm btn-danger float-right" @click="removeVariantForm(index)"><i class="fa fa-times"></i> Remove</button>

			  		</div>
			  		<div class="card-body">
		  				<div class="form-group row">
					   	<label for="" class="col-sm-3 col-form-label text-right">Value:</label>
					   	<div class="col-sm-9">
					   		<input type="text" class="form-control"
					   			v-model.trim="v.variant_value.$model"
					   			:class="{'is-invalid': v.variant_value.$error }"
					   			placeholder="Enter a variant value">
					   		<div v-if="v.variant_value.$error">
									<span class="error-feedback" v-if="!v.variant_value.required">Please enter a variant value</span>	
								</div>
					   	</div>
					  	</div>
					  	<div class="form-group row">
					   	<label for="" class="col-sm-3 col-form-label text-right">Price:</label>
					   	<div class="col-sm-9">
					   			<money 
						   			class="form-control"
						   			v-model.trim="v.variant_price.$model"
						   			:class="{'is-invalid': v.variant_price.$error }"
						   			:bind="money"></money>
					   			<div v-if="v.variant_price.$error">
										<span class="error-feedback" v-if="!v.variant_price.required">Please enter a variant price</span>
									</div>
					   	</div>
					  	</div>
					  	<div class="form-group row">
					   	<label for="" class="col-sm-3 col-form-label text-right">Stock:</label>
					   	<div class="col-sm-9">
					   		<input type="text" class="form-control"
					   			v-model.trim="v.inventory_stock.$model"
					   			:class="{'is-invalid': v.inventory_stock.$error }"
					   			placeholder="Enter a variant stock">
					   			<div v-if="v.inventory_stock.$error">
										<span class="error-feedback" v-if="!v.inventory_stock.required">Please enter a variant stock</span>
										<template v-if="v.inventory_stock.required">
											<span class="error-feedback" v-if="!v.inventory_stock.numbersOnly">Please enter a valid value</span>
										</template>	
									</div>
					   	</div>
					  	</div>
					  	<div class="form-group row">
					   	<label for="" class="col-sm-3 col-form-label text-right">Critical level:</label>
					   	<div class="col-sm-9">
					   		<input type="text" class="form-control"
					   			v-model.trim="v.inventory_crit_level.$model"
					   			:class="{'is-invalid': v.inventory_crit_level.$error }"
					   			placeholder="Enter a variant critical level">
					   		<div v-if="v.inventory_crit_level.$error">
									<span class="error-feedback" v-if="!v.inventory_crit_level.required">Please enter a variant critical level</span>
									<template v-if="v.inventory_crit_level.required">
										<span class="error-feedback" v-if="!v.inventory_crit_level.numbersOnly">Please enter a valid value</span>
									</template>	
								</div>
					   	</div>
					  	</div>
					  	<div class="form-group row">
					   	<label for="" class="col-sm-3 col-form-label text-right">Status:</label>
					   	<div class="col-sm-9">
					   		<select class="form-control" v-model="v.variant_status.$model">
					   			<option value="1">Enable</option>
					   			<option value="0">Disable</option>
					   		</select>
					   	</div>
					  	</div>
			  		</div>
			  	</div>
			  	<button class="btn btn-sm btn-primary mt-3 mb-3" @click="addVariantForm"><i class="fas fa-plus"></i> Add variant</button>
			  	<div class="form-group row">
					<label for="c_image" class="col-sm-3 col-form-label">Product image:</label>
					<div class="col-sm-4">
						<input type="file" name="product_image" id="c_image" ref="image" @change="onProductImageChange" hidden tabindex="8">
						<a href="javascript:void(0)" @click="openDialog">
							<img :src="avatar" class="img-fluid img-thumbnail">
						</a>
						<div v-if="$v.forms.image.$error">
							<span class="error-feedback" v-if="!$v.forms.image.required">Please select a product image</span>	
						</div>
					</div>
				</div>
			
	  	</b-modal>
	</div>
</template>

<script>
	import { required, minValue, minLength, maxLength, helpers, decimal, between } from 'vuelidate/lib/validators';

	const numbersOnly = helpers.regex('numbersOnly', /^([1-9])[0-9]*$/);
	const moneyRegex = helpers.regex('moneyRegex', /^[1-9][0-9.]*$/);

	export default {
		props: ['admin'],
		data() {
			return {
				forms: {
					product_name: '',
					category: '',
					description: '',
					status: 1,
					image: '',
					variants: [{
						variant_value: '',
						variant_price: '',
						variant_status: 1,
						inventory_stock: '',
						inventory_crit_level: ''
					}]
				},
				isBtnClicked: false,
				server_errors: [],
				categories: [],
				server_errors: [],
				avatar: '/images/default-thumbnail.jpg',
				readyToSubmit: false,
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
		validations() {
			return {
				forms: {
					product_name: { required },
					description: { required },
					category: { required },
					image: { required },
					variants: {
						$each: {
							variant_value: { required },
							variant_price: { 
								required
							},
							inventory_stock: { 
								required,
								numbersOnly
							},
							inventory_crit_level: {
								required,
								numbersOnly
							},
							variant_status: {}
						}
					}
				}
			}
		},
		methods: {
			openDialog() {
				this.$refs.image.click()
			},
			onProductImageChange(e) {
				let files = e.target.files || e.dataTransfer.files
				if (!files.length)
					return;
				// this.creteImage(files[0])
				this.forms.image = files[0]
				this.createImage(this.forms.image)
				e.target.value = '';
			},
			createImage(file) {
				let reader = new FileReader()
	
				let vm = this
				reader.onload = (e)	=> {
					vm.avatar = e.target.result
				}
				reader.readAsDataURL(file)
			},
			addProductWithVariants() {
				this.getCategory();
				this.$refs.refsAddWithVariantModal.show();
			},
			addVariantForm() {
				this.forms.variants.push({
					variant_value: '',
					variant_price: '',
					variant_status: 1,
					inventory_stock: '',
					inventory_crit_level: ''
				});
			},
			removeVariantForm(index) {
				this.forms.variants.splice(index,1);
			},
			submitProductVariant(e) {
				e.preventDefault();

				this.$v.forms.$touch();

				if (!this.$v.forms.$invalid) {
					this.readyToSubmit = true;
					this.isBtnClicked = true;

					let form = new FormData();

					form.append('admin_id', this.admin.id);
					form.append('product_name', this.forms.product_name);
					form.append('product_description', this.forms.description);
					form.append('category', this.forms.category);
					form.append('product_image', this.forms.image);
					form.append('product_status', this.forms.status);
					form.append('variants', JSON.stringify(this.forms.variants));
	       	
	       			axios.post('/api/product/create-with-variant', form)
		       		.then(response => {
		       			this.isBtnClicked = false;
		       			this.readyToSubmit = false;
		       			if (response.data.success) {
		       				Swal('Product has been created', '', 'success')
								.then((okay) => {
									if (okay) {
										this.$refs.refsAddWithVariantModal.hide();
										this.$v.forms.$reset();
										this.$bus.$emit('refreshTable', true);
									}
								})
		       			}
		       			
		       		})
		       		.catch(error => {
		       			this.isBtnClicked = false;
							if(error.response.status == 422) {
								this.server_errors = error.response.data.errors;
							}
		       		});

				}

			},
			getCategory() {
				axios.get('/api/category/with-variants')
				.then((response) => {
					this.categories = response.data;
				})
				.catch((error) => {
					console.log(error.response);
				});
			},
			cancelProductWithVariant() {
				this.forms.product_name = '';
				this.forms.description = '';
				this.forms.category = '';
				this.forms.status = 1;
				this.forms.image = '';
				this.forms.variants = [{
					variant_value: '',
					variant_price: '',
					variant_status: 1,
					inventory_stock: '',
					inventory_crit_level: ''
				}];
				this.server_errors = [];
				this.categories = [];
				this.avatar = '/images/default-thumbnail.jpg';
				this.$nextTick(() => { this.$v.forms.$reset() });
			},
			focusOnProdName() {
				this.$refs.addProductVariantInput.focus();
			}
		}
	}
</script>