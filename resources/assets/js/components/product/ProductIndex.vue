<template>
<div class="row">
	<div class="col-md-12">
		<div>
			<h2 class="mt-4 mb-4">Products</h2>
		</div>
		<div class="row mb-4">
			<div class="col-md-6 clearfix">
				<add-product-no-variant :admin="admin" class="float-left mr-2"></add-product-no-variant>
				<add-product-with-variants :admin="admin"></add-product-with-variants>
			</div>
			<div class="col-md-6">
				<!-- <div class="input-group" v-if="on_search">
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
				</template> -->
			</div>
		</div>
		<div class="">
			<table class="table table-bordered table-striped table-hovered table-condensed">
				<thead>
					<tr>
						<th>ID</th>
						<th width="30%">Product</th>
						<th>Category</th>
						<th>Price</th>
						<th>Status</th>
						<th width="18%">Action</th>
					</tr>
				</thead>
				<tbody>
					<template v-if="loading">
						<tr>
							<td colspan="6" align="center">
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
								<td colspan="6" align="center">No Products</td>
							</tr>
						</template>
						<template  v-else>
							<tr v-for="(prod, index) in products" :key="index">
								<td class="align-middle">{{prod.number}}</td>
								<td class="align-middle">
									<div class="media">
	                          	<img :src="'/storage_images/products/'+prod.product_image" class="media-object mr-2" width="20%" height="13%">
	                           <div class="media-body pt-4">
	                              <span class="d-block">{{ prod.product_name }}</span>
	                           </div>
	                       </div>
								</td>
								<td class="align-middle">{{prod.category.name}}</td>
								<td class="align-middle">
								{{ (prod.product_with_variants.length) ? getFirstAndLastData(prod.product_with_variants) : formatMoney(prod.product_no_variant.price)}}
								</td>
								<td class="align-middle"><span>{{ (prod.product_status === 1) ? 'Enabled' : 'Disabled' }} </span></td>
								<td class="align-middle clearfix" v-if="prod.product_with_variants.length">
									<edit-catalog-with-variants :admin="admin" :product="prod"></edit-catalog-with-variants>
									<product-with-variants :admin="admin" :product="prod"></product-with-variants>
								</td>
								<td class="align-middle clearfix" v-else>
									<edit-catalog-no-variants :admin="admin" :product="prod"></edit-catalog-no-variants>
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
		
	</div>
</div>
</template>
<script>

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
		}
	},
	components: {
		HalfCircleSpinner
	},
	methods: {
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
		getFirstAndLastData(variants) {
			// first item of the array 
			let first=variants[0]['variant_price'];  
			// last item of the array 
			let last=variants[variants.length-1]['variant_price'];  
			// printing the output to screen 20B1
			return this.formatMoney(first)+' - '+this.formatMoney(last);
		},
		formatMoney(value) {
			 return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(value);
		}
    },
    mounted() {
    	this.getProducts();
    	this.$bus.$on('refreshTable', data => {
			if (data == true)
			{
				this.getProducts();
			}
		})
    }
}
</script>
