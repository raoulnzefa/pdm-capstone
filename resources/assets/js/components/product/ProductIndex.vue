<template>
<div class="row">
	<div class="col-md-12">
		<div class="nav">
			<h2 class="mt-4 mb-4">Product catalog</h2>
		</div>
		<div class="row mb-4">
			<div class="col-md-6 clearfix">
				<add-product-no-variant :admin="admin" class="float-left mr-2"></add-product-no-variant>
				<add-product-with-variants :admin="admin"></add-product-with-variants>
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
						<th width="40%">Product</th>
						<th>Category</th>
						<th>Status</th>
						<th width="18%">Action</th>
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
								<td class="align-middle"><span>{{ (prod.product_status === 1) ? 'Enabled' : 'Disabled' }} </span></td>
								<td class="align-middle clearfix">
									<button class="btn btn-primary btn-sm float-left mr-1" @click="editProductCatalog(prod.number)"><i class="fas fa-edit"></i> Edit</button>
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
                 <p class="text-right">Showing {{ pagination.from_page }} to {{ pagination.to_page }} of {{ pagination.total_page }} {{ (pagination.total_page > 1) ? 'entries' : 'entry'}}</p>
               </div>
             </div>
		</template>
		<b-modal 
			title="Edit product catalog"
			ref="refsProductCatalogModal"
			size="lg"
			no-close-on-esc
			no-close-on-backdrop
			hide-header-close
			ok-title="Update product"
			@ok="submitProductCatalog"
			@cancel="cancelProductCatalog"
			@shown="focusOnProdName"
			:ok-disabled="isBtnClicked">
			<form @submit.stop.prevent="saveProductCatalog">
				<div class="alert alert-danger" v-if="server_errors.length != 0">
					<ul class="mb-0">
						<li v-for="(err,index) in server_errors" :key="index">{{ err[0] }}</li>
					</ul>
				</div>
				<div class="form-group row">
			   	<label for="catalogEdit1" class="col-sm-3 col-form-label">Product name:</label>
			   	<div class="col-sm-9">
			   		<input type="text" class="form-control" id="catalogEdit1" 
			   			placeholder="Enter product name"
			   			tabindex="1"
			   			v-model.trim="$v.product_name.$model"
			   			:class="{'is-invalid': $v.product_name.$error}"
			   			ref="catalogProductName">
			   		<div v-if="$v.product_name.$error">
	                	<span class="error-feedback" v-if="!$v.product_name.required">Product name is required</span>
	                </div>
			   	</div>
			  	</div>
			  	<div class="form-group row">
			   	<label for="colAddCategory" class="col-sm-3 col-form-label">Category:</label>
			   	<div class="col-sm-9">
			   		<select class="form-control" id="catalogEdit2"
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
			   	<label for="catalogEdit3" class="col-sm-3 col-form-label">Description:</label>
			   	<div class="col-sm-9">
			   		<textarea class="form-control" id="catalogEdit3" rows="4"
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
			   	<label for="catalogEdit4" class="col-sm-3 col-form-label">Status:</label>
			   	<div class="col-sm-9">
			   	<select class="form-control" id="catalogEdit4" tabindex="4" v-model="status">
			   			<option value="1">Enable</option>
			   			<option value="0">Disable</option>
			   		</select>
			   	</div>
			  	</div>
			  	<div class="form-group row">
					<label for="catalogEdit5" class="col-sm-3 col-form-label">Product image:</label>
					<div class="col-sm-4">
						<input type="file" name="product_image" id="catalogEdit5" ref="image" @change="onProductImageChange" hidden tabindex="5">
						<a href="javascript:void(0)" @click="openDialog">
							<img :src="avatar" class="img-fluid img-thumbnail">
						</a>
					</div>
				</div>
			</form>
	  	</b-modal>
	</div>
</div>
</template>
<script>
import { required, minLength, maxLength, helpers } from 'vuelidate/lib/validators';
import { HalfCircleSpinner } from 'epic-spinners';

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
			status: '',
			image: '',
			avatar: '/images/default-thumbnail.jpg',
			isBtnClicked: false,
			prodNumber: '',
			server_errors: [],
			categories: [],
		}
	},
	components: {
		HalfCircleSpinner
	},
	validations() {
		return {
			product_name: { required },
			category: { required },
			description: { required },
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
       getCategory() {
			axios.get('/api/category/list')
			.then((response) => {
				this.categories = response.data;
			})
			.catch((error) => {
				console.log(error.response);
			});
		},
		editProductCatalog(prodNum) {
			let catalog = this.products.find(x => x.number === prodNum);
			this.prodNumber = prodNum;
			this.product_name = catalog.product_name;
			this.category = catalog.category_id;
			this.description = catalog.product_description;
			this.status = catalog.product_status;
			this.avatar = `/storage/products/${catalog.product_image}`
			this.$refs.refsProductCatalogModal.show();
		},
		cancelProductCatalog() {
			this.product_name = '';
			this.description = '';
			this.category = '';
			this.status = '';
			this.prodNumber = '';
			this.isBtnClicked = false;
			this.server_errors = [];
			this.$nextTick(() => { this.$v.$reset() });
		},
		focusOnProdName() {
			this.$refs.catalogProductName.focus();
		},
		saveProductCatalog() {
			this.$v.$touch();

			if (!this.$v.$invalid) {
				this.isBtnClicked  = true;

				let form = new FormData();
				form.append('admin_id', this.admin.id);
				form.append('product_name', this.product_name);
				form.append('product_description', this.description);
				form.append('category', this.category);
				form.append('product_status', this.status);
				form.append('product_image', this.image);

				axios.post('/api/product/catalog/'+this.prodNumber, form)
				.then(response => {
					if (response.data.success) {
						Swal('Product catalog has been updated.', '', 'success')
						.then((okay) => {
							if (okay) {
								this.$refs.refsProductCatalogModal.hide();
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
		},	
		submitProductCatalog(e) {
			e.preventDefault();
			this.saveProductCatalog();
		}
    },
    mounted() {
    	this.getProducts();
    	this.getCategory();
    	this.$bus.$on('refreshTable', data => {
			if (data == true)
			{
				this.getProducts();
			}
		})
    }
}
</script>
