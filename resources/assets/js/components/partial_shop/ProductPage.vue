<template>
<div class="mb-5">
<div class="row">
	<template v-if="loading">
		<div class="col-md-12">
			<center class="mt-5 pt-5">
				<half-circle-spinner
					  :animation-duration="1000"
					  :size="40"
					  color="#ff1d5e"
					/>
			</center>
		</div>
	</template>
	<template v-else>
		<template v-if="!products.length">
			<div class="col-md-12">
				<p class="mt-3">There are no products listed under this category.</p>
			</div>
		</template>
		<template v-else>
			<div class="col-md-4" v-for="(product, index) in products" :key="index">	
				<div class="card">
					<a :href="'/product/view/'+product.url" class="product-link">
					
						<img :src="'/storage/products/'+product.image" class="img-fluid prod-img-frame card-img-top" alt="" width="500" height="500">
					
					</a>
					<div class="card-body">
						<h6 class="text-center">{{  product.name }}</h6>
		
						<div class="text-center">
							<template v-if="product.has_variant == 'No'">
								<template v-if="product.discount">
									<h6 class="mb-0"><s class="text-secondary">&#8369;{{ product.product_no_variant.price }}</s> &#8369;{{ product.product_no_variant.discounted_price }} <span class="badge badge-danger">{{ product.discount }}% off</span></h6>
								</template>
								<template v-else>
									<h6 class="mb-0">&#8369;{{ product.product_no_variant.price }}</h6>
								</template>
								<template v-if="product.product_no_variant.inventory.quantity > 0">
									<span class="mb-2 d-block text-success">{{product.product_no_variant.inventory.availability}}</span>
									<add-cart-page :customer="customer" :product="product" v-if="customer != 'No Email'"></add-cart-page>	
								</template>
								<template v-if="product.product_no_variant.inventory.quantity <= 0">
									<span class="d-block text-danger">{{product.product_no_variant.inventory.availability}}</span>
								</template>	
							</template>
							<template v-else>
								<a :href="'/product/view/'+product.url" class="btn btn-dark ifg-btn mt-2" v-if="customer != 'No Email'">CHOOSE OPTION</a>
							</template>
						</div>
		
					</div>
				</div>
			</div><!-- col-md-4 -->
		</template>
	</template>
</div>

<template v-if="!loading">
<div class="row" v-if="products.length != 0">
  <div class="col-md-12">
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
  <!-- <div class="col-md-4">
    <p class="text-right">Page: {{ pagination.from_page }} - {{ pagination.to_page }}
    Total: {{ pagination.total_page }}</p>
  </div> -->
</div>
</template>

</div>
</template>
<script>
import { HalfCircleSpinner } from 'epic-spinners'
	
	export default {
		props: ['customer'],
		data() {
			return { 
				products: [],
				loading: false,
				pagination: {}
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getProducts(pagi) {
				pagi = pagi || '/api/product-page';

				this.loading = true;
				axios.get(pagi)
				.then((response) => {
					this.loading = false;
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
					console.log(error.response.data);
				});
			}
		},
		created() {
			this.getProducts();
		}
	}
</script>