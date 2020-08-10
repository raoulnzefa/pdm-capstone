<template>
<div class="row">
	<template v-if="loading">
		<div class="col-md-12">
			<center class="mt-5">
				<half-circle-spinner
					  :animation-duration="1000"
					  :size="40"
					  color="#ff1d5e"
					/>
			</center>
		</div>
	</template>
	<template v-else>
		<template v-if="!featured_products.length">
			<div class="col-md-12">
				<p class="mt-3">There are no products listed under featured products.</p>
			</div>
		</template>
		<template v-else>
			<div class="col-md-3" v-for="(product, index) in featured_products" :key="index">
				
				<div class="card">
					<a :href="'/product/view/'+product.url" class="product-link">
					<img :src="'/storage/products/'+product.image" class="img-fluid prod-img-frame card-img-top" alt="" width="500" height="500">
					</a>
					<div class="card-body">
						<h6 class="text-center">{{  product.name }}</h6>

						<div class="text-center">
							<template v-if="product.has_variant == 'No'">
								<template v-if="product.discount">
									<h6 class="mb-0"><s class="text-secondary">&#8369; {{ product.product_no_variant.price }}</s> &#8369; {{ product.product_no_variant.discounted_price }} <span class="badge badge-danger">{{ product.discount }}% off</span></h6>
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
								<a :href="'/product/view/'+product.url" class="btn btn-dark mt-2 ifg-btn" v-if="customer != 'No Email'">Choose an option</a>
							</template>
						</div>
		
					</div>
				</div><!-- card -->
			</div><!-- col-md-3 -->
		</template>
	</template>
	
</div>
</template>
<script>
import { HalfCircleSpinner } from 'epic-spinners'

	export default {
		props: ['customer'],
		data() {
			return {
				featured_products: [],
				loading: false
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getFeaturedProducts() {
				this.loading = true;
				axios.get('/api/featured-product')
				.then((response) => {
					this.loading = false;
					this.featured_products = response.data;
				})
				.catch((error) => {
					this.loading = false;
					console.log(error.response);
				});
			}
		},
		created() {
			this.getFeaturedProducts();
		}
	}
</script>