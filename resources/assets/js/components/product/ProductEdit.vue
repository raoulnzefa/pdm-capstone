<template>
<div class="row justify-content-center">
    <div class="col-md-10">
    	<a href="/admin/products" class="mb-3 text-secondary d-block"><i class="fa fa-chevron-left"></i>&nbsp;Back to Products</a>
    	<div class="card">
			<div class="card-body">
				<div class="clearfix">
					<h2 class="float-left">Edit Product</h2>
					<!-- <button class="btn btn-danger float-right" @click="btnDeleteProduct">Delete</button> -->
				</div>
				<div class="alert alert-danger" v-if="server_errors.length != 0">
					<ul class="mb-0">
						<li v-for="(err,index) in server_errors" :key="index">{{ err[0] }}</li>
					</ul>
				</div>
				<form class="mt-4" @submit.prevent="updateProduct">
				
					<div class="form-group row">
						<label for="c_sku" class="col-sm-3 col-form-label text-right">ID:</label>
						<div class="col-sm-3">
							<input type="text" class="form-control" v-model="sku" readonly="true">
						</div>
					</div>
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
								<span class="error-feedback d-block" v-if="!$v.name.productNameRegex">Please input a valid value.</span>
								<span class="error-feedback" v-if="!$v.name.maxLength">Product Name must have at most {{ $v.name.$params.maxLength.max }} letters.</span>	
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
						<label for="" class="col-sm-3 col-form-label text-right">Status:</label>
						<div class="col-sm-2">
							<select class="form-control" v-model="status">
								<option value="Active">Active</option>
								<option value="Disable">Disable</option>
							</select>
						</div>
					</div>
					<template v-if="has_variant == 'No'">
						<div class="form-group row">
								<label for="price" class="col-sm-3 col-form-label text-right">Price:</label>
								<div class="col-sm-2">
									<b-form-input id="price"
						                type="number"
						                :class="{'is-invalid':$v.price.$error}"
						                v-model.trim="$v.price.$model">
						            </b-form-input>
								</div>
								<div class="col-md-4">
									<div v-if="$v.price.$error">
										<span class="error-feedback" v-if="!$v.price.required">Price is required</span>
										<span class="error-feedback d-block" v-if="!$v.price.moneyRegex">Price is invalid</span>
										<span class="error-feedback d-block" v-if="!$v.price.decimal">Price is invalid decimal</span>
										<span class="error-feedback d-block" v-if="!$v.price.maxLength">Price may not be greater than {{ $v.price.$params.maxLength.max }} characters</span>
									</div>
								</div>
							</div>
							
							<div class="form-group row">
								<label for="prod_crit_level" class="col-sm-3 col-form-label text-right">Critical Level:</label>
								<div class="col-sm-2">
									<b-form-input id="prod_crit_level"
						                type="number"
						                :class="{'is-invalid':$v.critical_level.$error}"
						                v-model.trim="$v.critical_level.$model">
						            </b-form-input>
								</div>
								<div class="col-md-4">
									<div v-if="$v.critical_level.$error">
										<span class="error-feedback" v-if="!$v.critical_level.required">Critical level is required</span>
										<span class="error-feedback d-block" v-if="!$v.critical_level.noStartZero">Critical level is invalid</span>
										<span class="error-feedback d-block" v-if="!$v.critical_level.maxLength">Critical level may not be greater than {{ $v.critical_level.$params.maxLength.max }} characters</span>	
									</div>
								</div>
							</div>
					</template>
					
					<button class="float-right mt-3 btn btn-primary" :disabled="disableUpdateBtn">Update product</button>
				</form>
				
			</div><!-- card-body -->
		</div><!-- card -->
    </div>
</div>
</template>

<script>
	import { required, minLength, maxLength, helpers, maxValue, decimal } from 'vuelidate/lib/validators';
	import { HalfCircleSpinner } from 'epic-spinners';

	// `/storage/products/${this.prod.image}`,
	const noStartZero = helpers.regex('noStartZero', /^[1-9][0-9]*$/);
	const moneyRegex = helpers.regex('moneyRegex', /^[1-9][0-9.]*$/);
	const productNameRegex = helpers.regex('productNameRegex', /^([a-zA-Z])[a-zA-Z0-9\s.-]*$/);
	const descRegex = helpers.regex('descRegex', /^([a-zA-Z])[a-zA-Z0-9\s.,"'!#%()-]*$/);


    export default {
    	props:['prod', 'admin'],
       	data() {
       		if (this.prod.has_variant == 'No') {
	       		return {
	       			has_product: false,
	       			server_errors: [],
	   				sku: this.prod.sku,
	   				name: this.prod.name,
	   				category: this.prod.category_id,
	   				description: this.prod.description,
	   				price: this.prod.product_no_variant.price.replace(/,/g,''),
	   				avatar: `/storage/products/${this.prod.image}`,
	   				has_variant: this.prod.has_variant,
	   				is_featured: this.prod.featured,
	   				status: this.prod.status,
	   				critical_level: this.prod.product_no_variant.critical_level,
	   				disableUpdateBtn: false,
	   				categories: [],
	   				image: ''
	       		}
       		} else {
       			return {
       				server_errors: [],
	   				sku: this.prod.sku,
	   				name: this.prod.name,
	   				category: this.prod.category_id,
	   				description: this.prod.description,
	   				has_variant: this.prod.has_variant,
	   				is_featured: this.prod.featured,
	   				status: this.prod.status,
	   				disableUpdateBtn: false,
	   				categories: [],
	   				image: '',
	   				avatar: `/storage/products/${this.prod.image}`
       			}
       		}
       },
       components: { HalfCircleSpinner },
       validations() {
			if (this.has_variant == 'Yes')
			{
				return {
					name: {
						required,
						productNameRegex,
						maxLength: maxLength(75)
					},
					category: {
						required
					},
					description: {
						required,
						descRegex
					},
					image: {
						
					}
				}
			}
			else
			{
				return {
					name: {
						required,
						productNameRegex,
						maxLength: maxLength(75)
					},
					category: {
						required
					},
					description: {
						required,
						descRegex
					},
					image: {
						
					},
					price: {
						required,
						moneyRegex,
						decimal,
						maxLength: maxLength(8),
						maxValue: maxValue(99999)
					},
					critical_level: {
						required,
						noStartZero,
						maxLength: maxLength(4)
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
			removeVariant() {
				Swal({
	              title: 'Are you sure?',
	              text: "You want to remove this variant?",
	              type: 'warning',
	              showCancelButton: true,
	              confirmButtonColor: '#3085d6',
	              cancelButtonColor: '#d33',
	              confirmButtonText: 'Yes'
	            }).then((result) => {
	              if (result.value) {
	               	 
	               	axios.put('/api/product-variant/'+this.sku+'/remove')
	               	.then((response) => {
	               		
	               	})
	               	.catch((error) => {
	               		console.log(error.response);
	               	});

	              }
	            });
			},
			updateProduct() {
				this.$v.$touch();

				if (!this.$v.$invalid) {
					this.disableUpdateBtn = true;

					let form = new FormData();

					form.append('admin_id', this.admin.id);
					form.append('name', this.name);
					form.append('category', this.category);
					form.append('description', this.description);

					if (this.image) {
						form.append('image', this.image);
					}

					if (this.has_variant == 'No') {
						form.append('price', this.price);
						form.append('critical_level', this.critical_level);
					}

					if (this.is_featured) {
						form.append('featured', this.is_featured);
					}

					if (this.status) {
						form.append('status', this.status);
					}

					axios.post('/api/product/'+this.sku, form)
					.then((response) => {
						this.disableUpdateBtn = false;
						if (response.data.success) {
							Swal('Saved','Product was successfully saved.', 'success')
							.then((okay) => {
								if (okay) {
									window.location.href = '/admin/products';
								}
							});
						}
					})
					.catch((error) => {
						this.disableUpdateBtn = false;
						if (error.response.status == 422) {
							this.server_errors = error.response.data.errors;
						}
					});
				}
			},
			btnDeleteProduct()
			{
				Swal({
				  title: 'Delete',
				  text: 'Are you sure you want to delete this product? It will also delete the inventory of this product?',
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Yes',
				  cancelButtonText: 'No'
				}).then((result) => {
				  if (result.value) {
				  	axios.delete('/api/product/delete/'+this.sku)
					.then(response => {
						if (response.data.success)
						{
							Swal('Delete' , 'Product has been deleted', 'success')
							.then((okay) => {
								if (okay)
								{
									window.location.href = '/admin/products';
								}
							})
						}
					})
					.catch(error => {
						console.log(error.response)
					})
				  }
				});
			}
       },	
       created() {
       		this.getCategory();
       	}
    }
</script>
