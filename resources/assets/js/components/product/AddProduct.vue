<template>
	<div class="row justify-content-center mb-5">
		<div class="col-md-10">
			<a href="/admin/products" class="mb-3 d-block text-secondary"><i class="fa fa-chevron-left"></i>&nbsp;Back to Products</a>
			<div class="card">
				<div class="card-body">
					<h2>Add Product</h2>
					<div class="alert alert-danger" v-if="server_errors.length != 0">
						<ul class="mb-0">
							<li v-for="(err,index) in server_errors" :key="index">{{ err[0] }}</li>
						</ul>
					</div>
					<form class="mt-4" @submit.prevent="saveProduct">
						<div class="form-group row">
							<label for="c_image" class="col-sm-3 col-form-label text-right">Product Image:</label>
							<div class="col-sm-4">
								<input type="file" name="image" id="c_image" ref="image" @change="onProductImageChange" hidden >
								<a href="javascript:void(0)" @click="openDialog">
									<img :src="avatar" class="img-fluid img-thumbnail">
								</a>
								<div v-if="$v.image.$error">
									<span class="error-feedback" v-if="!$v.image.required">Product image is required</span>	
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label for="c_sku" class="col-sm-3 col-form-label text-right">Product Name:</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" :class="{'is-invalid': $v.name.$error }" id="c_sku" placeholder="" v-model.trim="$v.name.$model">
								<div v-if="$v.name.$error">
									<span class="error-feedback" v-if="!$v.name.required">Product Name is required</span>
									<span class="error-feedback" v-if="!$v.name.productNameRegex">Please input a valid value</span>
									<span class="error-feedback d-block" v-if="!$v.name.maxLength">Product Name must have at most {{ $v.name.$params.maxLength.max }} letters</span>	
								</div>
							
							</div>
						</div>
						<div class="form-group row">
							<label for="c_sku" class="col-sm-3 col-form-label text-right">Category:</label>
							<div class="col-sm-4">
								<select class="form-control" v-model.trim="$v.category.$model" :class="{'is-invalid': $v.category.$error }">
									<option value=""></option>
									<option v-for="(category, index) in categories" :value="category.id">{{ category.name }}</option>
								</select>
								<div v-if="$v.category.$error">
									<span class="error-feedback" v-if="!$v.name.required">Category is required</span>	
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label for="c_sku" class="col-sm-3 col-form-label text-right">Description:</label>
							<div class="col-sm-7">
								<!-- <wysiwyg v-model.trim="$v.description.$model" style="height:400px;"/> -->
								<textarea rows="12" v-model.trim="$v.description.$model" class="form-control" :class="{'is-invalid': $v.description.$error}"></textarea>
								<div v-if="$v.description.$error">
									<span class="error-feedback" v-if="!$v.description.required">Description is required</span>
									<span class="error-feedback d-block" v-if="!$v.description.descRegex">Please input a valid value</span>	
								</div>
							</div>
						</div>
						
						<div class="form-group row">
							<label for="" class="col-sm-3 col-form-label text-right">Set as featured:</label>
							<div class="col-sm-2">
								<select class="form-control" v-model="is_featured">
									<option value="Yes">Yes</option>
									<option value="No">No</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label for="" class="col-sm-3 col-form-label text-right">Has variant:</label>
							<div class="col-sm-2">
								<select class="form-control" v-model="has_variant">
									<option value="Yes">Yes</option>
									<option value="No">No</option>
								</select>
							</div>
						</div>
						
						<template v-if="has_variant != 'Yes'">
							<div class="form-group row">
								<label for="" class="col-sm-3 col-form-label text-right">Status:</label>
								<div class="col-sm-2">
									<select class="form-control" v-model="status">
										<option value="Active">Active</option>
										<option value="Disable">Disable</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label for="price" class="col-sm-3 col-form-label text-right">Price:</label>
								<div class="col-sm-2">
									<b-form-input id="price"
						                type="number"
						                :class="{'is-invalid':$v.price.$error}"
						                v-model.trim="$v.price.$model"
						                placeholder="0.00">
						            </b-form-input>
								</div>
								<div class="col-md-4">
									<div v-if="$v.price.$error">
										<span class="error-feedback" v-if="!$v.price.required">Price is required</span>
										<span class="error-feedback d-block" v-if="!$v.price.moneyRegex">Price is invalid format</span>
										<span class="error-feedback d-block" v-if="!$v.price.decimal">Price is invalid decimal</span>
										<span class="error-feedback d-block" v-if="!$v.price.maxLength">Price may not be greater than {{ $v.price.$params.maxLength.max }} characters.</span>
										<span class="error-feedback d-block" v-if="!$v.price.maxValue">Price maximum value is {{ $v.price.$params.maxValue.max }}.</span>
									</div>
								</div>
							</div>
							
							<div class="form-group row">
								<label for="prod_qty" class="col-sm-3 col-form-label text-right">Initial Stock:</label>
								<div class="col-sm-2">
									<!-- <input type="number" name="quantity" id="prod_qty" class="form-control" v-model.trim="$v.quantity.$model" :class="{'is-invalid': $v.quantity.$error}"> -->
									<b-form-input id="prod_qty"
						                type="number"
						                :class="{'is-invalid':$v.quantity.$error}"
						                v-model.trim="$v.quantity.$model" placeholder="0">
						            </b-form-input>
		
								</div>
								<div class="col-md-4">
									<div v-if="$v.quantity.$error">
										<span class="error-feedback" v-if="!$v.quantity.required">Initial stock is required</span>
										<span class="error-feedback d-block" v-if="!$v.quantity.noStartZero">Initial stock is invalid.</span>
										<span class="error-feedback d-block" v-if="!$v.quantity.maxLength">Initial stock may not be greater than {{ $v.quantity.$params.maxLength.max }} characters.</span>	
									</div>
								</div>
							</div>
							<div class="form-group row">
								<label for="prod_crit_level" class="col-sm-3 col-form-label text-right">Critical Level:</label>
								<div class="col-sm-2">
									<!-- <input type="number" name="critical_level" id="prod_crit_level" class="form-control" v-model.trim="$v.critical_level.$model" :class="{'is-invalid': $v.critical_level.$error}"> -->
									<b-form-input id="prod_crit_level"
						                type="number"
						                :class="{'is-invalid':$v.critical_level.$error}"
						                v-model.trim="$v.critical_level.$model"
						                placeholder="0">
						            </b-form-input>
								</div>
								<div class="col-md-4">
									<div v-if="$v.critical_level.$error">
										<span class="error-feedback" v-if="!$v.critical_level.required">Critical level is required</span>
										<span class="error-feedback d-block" v-if="!$v.critical_level.noStartZero">Critical level is invalid</span>
										<span class="error-feedback d-block" v-if="!$v.critical_level.maxValue">Critical level cannot be greater than the Initial stock.</span>
										<span class="error-feedback d-block" v-if="!$v.critical_level.maxLength">Critical level may not be greater than {{ $v.critical_level.$params.maxLength.max }} characters.</span>	
									</div>
								</div>
							</div>
						</template>
						
						<div class="mt-5 float-right">
							<button type="submit" class="btn btn-primary" :disabled="submit">Save product</button>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import { required, minLength, helpers, maxLength, decimal, between, maxValue } from 'vuelidate/lib/validators';

	const noStartZero = helpers.regex('noStartZero', /^[1-9][0-9]*$/);
	const moneyRegex = helpers.regex('moneyRegex', /^[1-9][0-9.]*$/);
	const productNameRegex = helpers.regex('productNameRegex', /^([a-zA-Z])[a-zA-Z0-9\s.-]*$/);
	const descRegex = helpers.regex('descRegex', /^([a-zA-Z])[a-zA-Z0-9\s.,"'!#%()-]*$/);

	export default {
		props: ['admin'],
		data() {
			return {
				sku: '',
				name: '',
				category: '',
				product_type: '',
				description: '',
				avatar: '/images/default-thumbnail.jpg',
				image: '',
				is_featured: 'No',
				has_variant: 'No',
				status: 'Disable',
				price: '',
				categories: [],
				product_types: [],
				server_errors: [],
				variants: [],
				submit: false,
				quantity: '',
				critical_level: '',
				variant_group: '',
				variant_groups: []
			}
		},
		validations() {
			if (this.has_variant == 'No') {
				return {
					name: {
						required,
						maxLength: maxLength(75),
						productNameRegex
					},
					category: {
						required
					},
					description: {
						required,
						descRegex
					},
					image: {
						required
					},
					price: {
						required,
						moneyRegex,
						decimal,
						maxLength: maxLength(8),
						maxValue: maxValue(99999)
					},
					quantity: {
						required,
						noStartZero,
						maxLength: maxLength(4)
					},
					critical_level: {
						required,
						noStartZero,
						maxLength: maxLength(4),
						maxValue: maxValue(this.quantity)
					}
				}
			}
			else
			{
				return {
					name: {
						required,
						maxLength: maxLength(75),
						productNameRegex 
					},
					category: {
						required
					},
					description: {
						required,
						descRegex
					},
					image: {
						required
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
				this.image = files[0]
				this.createImage(this.image)
			},
			createImage(file) {
				let reader = new FileReader()
	
				let vm = this
				reader.onload = (e)	=> {
					vm.avatar = e.target.result
				}
				reader.readAsDataURL(file)
			},
			getCategory() {
				axios.get('/api/category/list')
				.then((response) => {
					this.categories = response.data;
				})
				.catch((error) => {
					console.log(error.response);
				});
			},
			getProductType() {
				if (this.category)
				{
					axios.get('/api/product-type/'+this.category+'/category')
					.then(response => {
						//console.log(response.data)
						this.product_types = response.data;
					})
					.catch(error => {
						console.log(error.response);
					})
				}
				else
				{
					this.product_types = [];
				}
			},
			saveProduct() {
				this.$v.$touch();
				if (!this.$v.$invalid) {
					this.submit = true;
					let form = new FormData();

					form.append('admin_id', this.admin.id);
					form.append('name', this.name);
					form.append('category', this.category);
					form.append('description', this.description);
					form.append('image', this.image);

					form.append('featured', this.is_featured);
					form.append('status', this.status);
					form.append('has_variant', this.has_variant);

					if (this.has_variant == 'No') {
						form.append('price', this.price)
						form.append('quantity', this.quantity);
						form.append('critical_level', this.critical_level);
					}

					axios.post('/api/product/store', form)
					.then((response) => {
						this.submit = false;
						console.log(response.data);
						if (response.data.success) {
							Swal('Product has been created', '', 'success')
							.then((okay) => {
								if (okay) {
									window.location.href = "/admin/products";
								}
							})
						}					
					})
					.catch((error) => {
						this.submit = false;
						if(error.response.status == 422) {
							this.server_errors = error.response.data.errors;
						}
					});
				}
			},
			getVariantGroups() {
				axios.get('/api/variant-groups')
				.then((response) => {
					this.variant_groups = response.data;
				})
				.catch((error) => {
					console.log(error.response);
				});
			}
		},
		created() {
			this.getCategory();
		}
		
	}
</script>