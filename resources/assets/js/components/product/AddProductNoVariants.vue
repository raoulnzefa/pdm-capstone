<template>
	<div>
		<button class="btn btn-primary" @click="addProduct"><i class="fas fa-plus"></i> Add product</button>
		<b-modal 
			title="Add product"
			ref="refsAddProductModal"
			size="lg"
			no-close-on-esc
			no-close-on-backdrop
			hide-header-close
			ok-title="Add product"
			@ok="submitProduct"
			@cancel="cancelProduct"
			@shown="focusOnProdName"
			:ok-disabled="isBtnClicked">
			<form @submit.stop.prevent="saveProduct">
				<div class="alert alert-danger" v-if="server_errors.length != 0">
					<ul class="mb-0">
						<li v-for="(err,index) in server_errors" :key="index">{{ err[0] }}</li>
					</ul>
				</div>
				<div class="form-group row">
			   	<label for="colAddProdName" class="col-sm-3 col-form-label">Product name:</label>
			   	<div class="col-sm-9">
			   		<input type="text" class="form-control" id="colAddProdName" 
			   			placeholder="Enter product name"
			   			tabindex="1"
			   			v-model.trim="$v.product_name.$model"
			   			:class="{'is-invalid': $v.product_name.$error}"
			   			ref="addProductNameInput">
			   		<div v-if="$v.product_name.$error">
	                	<span class="error-feedback" v-if="!$v.product_name.required">Product name is required</span>
	                </div>
			   	</div>
			  	</div>
			  	<div class="form-group row">
			   	<label for="colAddCategory" class="col-sm-3 col-form-label">Category:</label>
			   	<div class="col-sm-9">
			   		<select class="form-control" id="colAddCategory"
			   			tabindex="2"
			   			v-model.trim="$v.category.$model"
			   			:class="{'is-invalid': $v.category.$error }">
			   			<option value="" selected disabled>Select category</option>
			   			<option v-for="(category, index) in categories" :value="category.id">{{ category.name }}</option>
			   		</select>
			   		<div v-if="$v.category.$error">
							<span class="error-feedback" v-if="!$v.category.required">Category is required</span>	
						</div>
			   	</div>
			  	</div>
			  	<div class="form-group row">
			   	<label for="colAddProdDesc" class="col-sm-3 col-form-label">Description:</label>
			   	<div class="col-sm-9">
			   		<textarea class="form-control" id="colAddProdDesc" rows="4"
			   			placeholder="Enter description"
			   			tabindex="3"
			   			v-model.trim="$v.description.$model"
			   			:class="{'is-invalid': $v.description.$error }"
			   			></textarea>
			   		<div v-if="$v.description.$error">
							<span class="error-feedback" v-if="!$v.description.required">Description is required</span>	
						</div>
			   	</div>
			  	</div>
			  	<div class="form-group row">
			   	<label for="colAddProdStock" class="col-sm-3 col-form-label">Stock:</label>
			   	<div class="col-sm-9">
			   		<input type="text" class="form-control" id="colAddStock" 
			   			placeholder="Enter stock"
			   			tabindex="4"
			   			v-model.trim="$v.stock.$model"
			   			:class="{'is-invalid': $v.stock.$error }"
			   			>
			   		<div v-if="$v.stock.$error">
							<span class="error-feedback" v-if="!$v.stock.required">Stock is required</span>	
							<template v-if="$v.stock.required">
								<span class="error-feedback" v-if="!$v.stock.numbersOnly">Please enter a valid value</span>
							</template>	
						</div>
			   	</div>
			  	</div>
			  	<div class="form-group row">
			   	<label for="coldAddProdCritLvl" class="col-sm-3 col-form-label">Critial level:</label>
			   	<div class="col-sm-9">
			   		<input type="text" class="form-control" id="coldAddProdCritLvl" 
			   			placeholder="Enter critical level"
			   			tabindex="4"
			   			v-model.trim="$v.critical_level.$model"
			   			:class="{'is-invalid': $v.critical_level.$error }"
			   			>
			   		<div v-if="$v.critical_level.$error">
							<span class="error-feedback" v-if="!$v.critical_level.required">Critical level is required</span>	
							<template v-if="$v.critical_level.required">
								<span class="error-feedback" v-if="!$v.critical_level.numbersOnly">Please enter a valid value</span>
							</template>	
						</div>
			   	</div>
			  	</div>
			  		<div class="form-group row">
			   	<label for="colAddProdStatus" class="col-sm-3 col-form-label">Status:</label>
			   	<div class="col-sm-9">
			   	<select class="form-control" id="colAddProdStatus" tabindex="5" v-model="status">
			   			<option value="1">Enable</option>
			   			<option value="0">Disable</option>
			   		</select>
			   	</div>
			  	</div>
			  	<div class="form-group row">
			   	<label for="colAddProdPrice" class="col-sm-3 col-form-label">Price:</label>
			   	<div class="col-sm-9">
			   		<input type="text" class="form-control" id="colAddProdPrice" 
			   			placeholder="Enter price"
			   			tabindex="7"
			   			v-model.trim="$v.price.$model"
			   			:class="{'is-invalid': $v.price.$error }"
			   			>
			   		<div v-if="$v.price.$error">
							<span class="error-feedback" v-if="!$v.price.required">Price is required</span>
							<template v-if="$v.price.required">
								<span class="error-feedback" v-if="!$v.price.moneyRegex">Please enter a valid value</span>
								<template v-if="$v.price.moneyRegex">
									<span class="error-feedback" v-if="!$v.price.decimal">Please enter a valid value</span>
								</template>
							</template>	
						</div>
			   	</div>
			  	</div>
			  	<div class="form-group row">
					<label for="c_image" class="col-sm-3 col-form-label">Product image:</label>
					<div class="col-sm-4">
						<input type="file" name="product_image" id="c_image" ref="image" @change="onProductImageChange" hidden tabindex="8">
						<a href="javascript:void(0)" @click="openDialog">
							<img :src="avatar" class="img-fluid img-thumbnail">
						</a>
						<div v-if="$v.image.$error">
							<span class="error-feedback" v-if="!$v.image.required">Product image is required</span>	
						</div>
					</div>
				</div>
			</form>
	  	</b-modal>
	</div>
</template>

<script>
	import { required, minLength, maxLength, helpers, decimal } from 'vuelidate/lib/validators';

	const numbersOnly = helpers.regex('numbersOnly', /^([1-9])[0-9]*$/);
	const moneyRegex = helpers.regex('moneyRegex', /^[1-9][0-9.]*$/);

	export default {
		props: ['admin'],
		data() {
			return {
				product_name: '',
				category: '',
				description: '',
				stock: '',
				critical_level: '',
				price: '',
				status: 1,
				avatar: '/images/default-thumbnail.jpg',
				image: '',
				categories: [],
				isBtnClicked: false,
				server_errors: [],
				readyToSubmit: false,
				turnOnBeforeUnload: false,
			}
		},
		validations() {
			return {
				product_name: { required },
				category: { required },
				description: { required },
				stock: { required, numbersOnly },
				critical_level: { required, numbersOnly },
				image: { required },
				price: { required, moneyRegex, decimal }
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
			addProduct() {
	       	this.$refs.refsAddProductModal.show();
	       	// window.addEventListener('beforeunload', (event) => {
	        //      if (!this.readyToSubmit) {
	        //          event.returnValue = `Are you sure you want to leave?`;
	        //          this.readyToSubmit = false;
	        //      }
	        //  });
	      },
	      saveProduct() {
	      	this.$v.$touch();

	      	if (!this.$v.$invalid) {
	      		this.isBtnClicked = true;
	      		this.readyToSubmit = true;

	       		let form = new FormData();

					form.append('admin_id', this.admin.id);
					form.append('product_name', this.product_name);
					form.append('product_description', this.description);
					form.append('category', this.category);
					form.append('product_stock', this.stock);
					form.append('product_critical_level', this.critical_level);
					form.append('product_price', this.price);
					form.append('product_image', this.image);
					form.append('product_status', this.status);

					axios.post('/api/product/create-no-variant', form)
	       		.then(response => {
	       			if (response.data.success) {
	       				Swal('Product has been created', '', 'success')
							.then((okay) => {
								if (okay) {
									this.$refs.refsAddProductModal.hide();
									this.$nextTick(() => { this.$v.$reset() });
									this.$bus.$emit('refreshTable', true);
								}
							})
	       			}
	       			this.isBtnClicked = false;
	       			this.readyToSubmit = false;
	       		})
	       		.catch(error => {
	       			this.isBtnClicked = false;
	       			this.readyToSubmit = false;
						if(error.response.status == 422) {
							this.server_errors = error.response.data.errors;
						}
	       		});
	      	}
	      },
	      submitProduct(e) {
				e.preventDefault();
				this.saveProduct();
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
			cancelProduct() {
				this.product_name = '';
				this.description = '';
				this.category = '';
				this.stock = '',
				this.critical_level = '';
				this.price = '';
				this.status = 1;
				this.image = '';
				this.server_errors = [];
				this.$nextTick(() => { this.$v.$reset() });
			},
			focusOnProdName() {
				this.$refs.addProductNameInput.focus();
			}
		}, // methods
		mounted() {
			this.getCategory();
		}
	}
</script>