<template>
<div class="row justify-content-center">
	<div class="col-md-12">
		<div class="nav">
			<h2 class="mt-4 mb-4">Products</h2>
		</div>
		<div class="row mb-4">
			<div class="col-md-6">
				<button class="btn btn-primary" v-if="!on_search" @click="addProduct"><i class="fas fa-plus"></i> Add product</button>
			</div>
			<div class="col-md-6">
				<div class="input-group" v-if="on_search">
				  	<input type="text" class="form-control" placeholder="Search product" v-model="search_val">
				  	<div class="input-group-append">
				    	<button class="btn btn-secondary" type="button" @click="searchProduct"><i class="fa fa-search"></i> Search</button>
				    	<div class="input-group-append" v-if="on_search">
                        	<button class="btn btn-outline-danger" type="button" @click="showSearchField" v-if="!submit_search">Cancel</button>
                        	<button class="btn btn-outline-danger" type="button" @click="clearSearch" v-if="submit_search">Clear</button>
                        </div>
				    	
				  	</div>
				</div>
				<template v-if="products.length">
					<button class="btn btn-dark float-right mr-1" v-if="!on_search" @click="showSearchField"><i class="fa fa-search"></i> Search</button>
				</template>
			</div>
		</div>
		<div class="">
			<table class="table table-bordered table-striped table-hovered table-condensed">
				<thead>
					<tr>
						<th>Product #</th>
						<th width="30%">Product</th>
						<th>Category</th>
						<th>Price</th>
						<th>Stock</th>
						<th>Status</th>
						<th width="18%">Action</th>
					</tr>
				</thead>
				<tbody>
					<template v-if="loading">
						<tr>
							<td colspan="7" align="center">
								<half-circle-spinner
	                                :animation-duration="1000"
	                                :size="30"
	                                color="#ff1d5e"
	                              />
							</td>
						</tr>
					</template>
					<template v-else>
						<template v-if="!products.length">
							<tr>
								<td colspan="7" align="center">No Products</td>
							</tr>
						</template>
						<template  v-else>
							<tr v-for="(prod, index) in products" :key="index">
								<td class="align-middle">{{prod.number}}</td>
								<td class="align-middle">
									<div class="media">
	                          	<img :src="'/storage/products/'+prod.product_image" class="media-object mr-2" width="20%" height="13%">
	                           <div class="media-body pt-4">
	                              <span class="d-block">{{ prod.product_name }}</span>
	                           </div>
	                       </div>
								</td>
								<td class="align-middle">{{prod.category.name}}</td>
								<td class="align-middle">&#8369;{{prod.product_price}}</td>
								<td class="align-middle">{{prod.product_stock}}</td>
								<td class="align-middle"><span>{{ (prod.product_status === 1) ? 'Active' : 'Disabled' }} </span></td>
								<td class="align-middle clearfix">
									<button class="btn btn-primary btn-sm float-left mr-1" @click="editProduct(prod.number)"><i class="fas fa-edit"></i> Edit</button>
									<add-stock :admin="admin" :product="prod"></add-stock>
								</td>
							</tr>
						</template>
					</template>
				</tbody>
			</table>
		</div>
		<template v-if="!loading">
			<div class="row" v-if="products.length != 0">
               <div class="col-md-8">
                 <nav>
                   <ul class="pagination">
                     <li :class="{disabled:!pagination.first_link}" class="page-item"><a href="javascript:void(0);" @click="getProducts(pagination.first_link)" class="page-link">&laquo;</a></li>
                     <li :class="{disabled:!pagination.prev_link}" class="page-item"><a href="javascript:void(0);" @click="getProducts(pagination.prev_link)" class="page-link">Prev</a></li>
                     <li v-for="n in pagination.last_page" v-bind:key="n" :class="{active: pagination.current_page == n}" class="page-item"><a href="javascript:void(0);" @click="getProducts(pagination.path_page + n)" class="page-link">{{ n }}</a></li>
                     <li :class="{disabled:!pagination.next_link}" class="page-item"><a href="javascript:void(0);" @click="getProducts(pagination.next_link)" class="page-link">Next</a></li>
                     <li :class="{disabled:!pagination.last_link}" class="page-item"><a href="javascript:void(0);" @click="getProducts(pagination.last_link)" class="page-link">&raquo;</a></li>
                   </ul>
                 </nav>
               </div>
               <div class="col-md-4">
                 <p class="text-right">Page: {{ pagination.from_page }} - {{ pagination.to_page }}
                 Total: {{ pagination.total_page }}</p>
               </div>
             </div>
		</template>
		<b-modal 
			title="Add product"
			ref="refsProductModal"
			size="lg"
			no-close-on-esc
			no-close-on-backdrop
			hide-header-close
			:ok-title="okTitle"
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
			   	<label for="colAddProdVariant" class="col-sm-3 col-form-label">Variant:</label>
			   	<div class="col-sm-9">
			   		<input type="text" class="form-control" id="colAddProdVariant"
			   			placeholder="Enter variant"
			   			aria-describedby="addVariantHelp"
			   			tabindex="4"
			   			v-model="variant">
			   		<small id="addVariantHelp" class="form-text text-muted">Example: 10oz</small>
			   	</div>
			  	</div>
			  	<div class="form-group row" v-if="!isEdit">
			   	<label for="colAddProdStock" class="col-sm-3 col-form-label">Stock:</label>
			   	<div class="col-sm-9">
			   		<input type="text" class="form-control" id="colAddStock" 
			   			placeholder="Enter stock"
			   			tabindex="5"
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
			  	<div class="form-group row" v-if="isEdit">
			   	<label for="colAddProdStatus" class="col-sm-3 col-form-label">Disable:</label>
			   	<div class="col-sm-9">
			   	<select class="form-control" id="colAddProdStatus" tabindex="5" v-model="status">
			   			<option value="0">No</option>
			   			<option value="1">Yes</option>
			   		</select>
			   	</div>
			  	</div>
			  	<div class="form-group row">
			   	<label for="coldAddProdCritLvl" class="col-sm-3 col-form-label">Critial level:</label>
			   	<div class="col-sm-9">
			   		<input type="text" class="form-control" id="coldAddProdCritLvl" 
			   			placeholder="Enter critical level"
			   			tabindex="6"
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
					<div class="col-sm-9">
						<input type="file" name="product_image" id="c_image" ref="image" @change="onProductImageChange" hidden tabindex="9">
						<a href="javascript:void(0)" @click="openDialog">
							<img :src="avatar" class="img-fluid img-thumbnail">
						</a>
						<template v-if="!isEdit">
							<div v-if="$v.image.$error">
								<span class="error-feedback" v-if="!$v.image.required">Product image is required</span>	
							</div>
						</template>
					</div>
				</div>
			</form>
	  	</b-modal>
	</div>
</div>
</template>
<script>
import { HalfCircleSpinner } from 'epic-spinners';
import { required, minLength, maxLength, helpers, decimal } from 'vuelidate/lib/validators';

const numbersOnly = helpers.regex('numbersOnly', /^([1-9])[0-9]*$/);
const moneyRegex = helpers.regex('moneyRegex', /^[1-9][0-9.]*$/);

export default {
	props: ['admin'],
	data() {
		return {
			fields: ['sku','name', 'status'],
			products: [],
			loading: false,
			submit_search: false,
			on_search: false,
			search_val: '',
			pagination: {},
			product_name: '',
			category: '',
			description: '',
			variant: '',
			stock: '',
			critical_level: '',
			price: '',
			edit: false,
			categories: [],
			avatar: '/images/default-thumbnail.jpg',
			image: '',
			isBtnClicked: false,
			server_errors: [],
			isEdit: false,
			modalTitle: 'Add product',
			okTitle: 'Create product',
			prodNumber: '',
			status: 0,
		}
	},
	components: {
		HalfCircleSpinner
	},
	validations() {
		if (!this.isEdit) {
			return {
				product_name: { required },
				category: { required },
				description: { required },
				stock: { required, numbersOnly },
				critical_level: { required, numbersOnly },
				image: { required },
				price: { required, moneyRegex, decimal }
			}
		} else {
			return {
				product_name: { required },
				category: { required },
				description: { required },
				critical_level: { required, numbersOnly },
				price: { required, moneyRegex, decimal }
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
       getProducts(pagi) {
       		if (!this.search_val)
       		{
       			pagi = pagi || '/api/products';
       		}
       		else
       		{
       			pagi = '/api/products?search='+this.search_val;
       		}

       		this.loading = true;

       		axios.get(pagi)
       		.then((response) => {
       			this.loading = false;
       			//console.log(response.data);
       			this.products = response.data.data;
       			this.pagination = {
       				current_page: response.data.current_page,
       				last_page: response.data.last_page,
       				from_page: response.data.from,
       				to_page: response.data.to,
       				total_page: response.data.total,
       				path_page: response.data.path+'?page=',
       				first_link: response.data.first_page_url,
       				last_link: response.data.last_page_url,
       				prev_link: response.data.pre_page_url,
       				next_link: response.data.next_page_url
       			};

       		})
       		.catch((error) => {
       			this.loading = false;
       			console.log(error.response);
       		});
       },
       showSearchField() {
       		this.on_search = !this.on_search;
       },
       searchProduct() {
       		this.submit_search = true;

       		if (!this.search_val)
       		{
       			this.submit_search = false;
       			return null;
       		}

       		this.getProducts();
       },
       clearSearch() {
       		this.search_val = '';
       		this.on_search = false;
       		this.getProducts();
       },
       addProduct() {
       	this.modalTitle = 'Add product';
       	this.okTitle = 'Create product';
       	this.isEdit = false;
       	this.$refs.refsProductModal.show();
       },
       editProduct(prodNum) {
       	let prod = this.products.find(x => x.number == prodNum);
       	this.prodNumber = prodNum;
       	this.product_name = prod.product_name;
       	this.category = prod.category_id;
       	this.description = prod.product_description;
       	this.variant = prod.product_variant;
       	this.critical_level = prod.product_critical_level;
       	this.price = prod.product_price;
       	this.avatar = `/storage/products/${prod.product_image}`;
       	this.status = prod.product_status;
       	this.modalTitle = 'Edit product';
       	this.okTitle = 'Update product';
       	this.isEdit = true;
       	this.$refs.refsProductModal.show();
       },
       saveProduct() {
       	this.$v.$touch();

       	if (!this.$v.$invalid) {
       		this.isBtnClicked = true;

       		let form = new FormData();

				form.append('admin_id', this.admin.id);
				form.append('product_name', this.product_name);
				form.append('product_description', this.description);
				form.append('category', this.category);
				form.append('product_variant', this.variant);
				form.append('product_stock', this.stock);
				form.append('product_critical_level', this.critical_level);
				form.append('product_price', this.price);
				form.append('product_image', this.image);
				form.append('product_status', this.status);


       		if (!this.isEdit) {
       			axios.post('/api/product/store', form)
	       		.then(response => {
	       			if (response.data.success) {
	       				Swal('Product has been created', '', 'success')
							.then((okay) => {
								if (okay) {
									this.$refs.refsProductModal.hide();
									this.$nextTick(() => { this.$v.$reset() });
									this.getProducts();
								}
							})
	       			}
	       			this.isBtnClicked = false;
	       		})
	       		.catch(error => {
	       			this.isBtnClicked = false;
						if(error.response.status == 422) {
							this.server_errors = error.response.data.errors;
						}
	       		});
       		} else {
       			// update product
       			axios.post('/api/product/'+this.prodNumber, form)
	       		.then(response => {
	       			if (response.data.success) {
	       				Swal('Product has been updated', '', 'success')
							.then((okay) => {
								if (okay) {
									this.$refs.refsProductModal.hide();
									this.$nextTick(() => { this.$v.$reset() });
									this.getProducts();
								}
							})
	       			}
	       			this.isBtnClicked = false;
	       		})
	       		.catch(error => {
	       			this.isBtnClicked = false;
						if(error.response.status == 422) {
							this.server_errors = error.response.data.errors;
						}
	       		});
       		}
       	}
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
		submitProduct(e) {
			e.preventDefault();
			this.saveProduct();
		},
		cancelProduct() {
			this.product_name = '';
			this.description = '';
			this.category = '';
			this.variant = '';
			this.stock = '',
			this.critical_level = '';
			this.price = '';
			this.display = 0;
			this.status = 0;
			this.image = '';
			this.$nextTick(() => { this.$v.$reset() });
			this.isEdit = false;
			this.modalTitle = 'Add product';
			this.okTitle = 'Create product';
		},
		focusOnProdName() {
			this.$refs.addProductNameInput.focus();
		}
    },
    mounted() {
    	this.getProducts();
    	this.getCategory();
    },
    created() {
    	this.$bus.$on('refreshTable', data => {
			if (data == true)
			{
				this.getProducts();
			}
		})
    }
}
</script>
