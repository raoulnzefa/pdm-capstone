<template>
	<div class="row mt-5">
		<div class="col-md-12">
		<h4 class="mb-4">Related Products</h4>
		<template v-if="loading">
			<center class="mt-5">
				<half-circle-spinner
					  :animation-duration="1000"
					  :size="30"
					  color="#ff1d5e"
					/>
			</center>
		</template>
		<template v-else>
			<template v-if="related_products == ''">
				<h5 class="text-center">No related products</h5>
			</template>
			<template v-else>
				<div class="row">
					<div class="col-md-3" v-for="(product, index) in related_products" :key="index">
						<div class="card">
							<a :href="'/product/view/'+product.url" class="product-link">
							<img :src="'/storage/products/'+product.image" class="img-fluid card-img-top prod-img-frame" alt="" width="500" height="500">
							</a>
							<div class="card-body">
								<h6 class="text-center">{{  product.name }}</h6>

								<div class="text-center">
									<template v-if="product.has_variant == 'No'">
										<template v-if="product.discount">
											<h6><s class="text-secondary">PHP {{ product.product_no_variant.price }}</s> PHP {{ product.product_no_variant.discounted_price }} <span class="badge badge-danger">{{ product.discount }}% off</span></h6>
										</template>
										<template v-else>
											<h6>PHP {{ product.product_no_variant.price }}</h6>
										</template>
										<add-cart-page :product="product" :customer="customer"></add-cart-page>	
									</template>
									<template v-else>
										<a :href="'/product/view/'+product.url" class="btn btn-dark mt-2 ifg-btn">Choose an option</a>
									</template>
								</div>
				
							</div>
						</div><!-- card -->
					</div>
				</div>
			</template>
		</template>
		</div>
	</div>
</template>
<script>
import { HalfCircleSpinner } from 'epic-spinners'

	export default {
		props: ['prod', 'customer'],
		data() {
			return {
				loading: false,
				related_products: []
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getRelatedProducts()
			{
				this.loading = true;
				axios.get('/api/product/'+this.prod.sku+'/related')
				.then((respose) => {
					console.log(respose.data);
					this.loading = false;
					this.related_products = respose.data;
				})
				.catch((error) => {
					this.loading = false;
					console.log(error.respose);
				});
			}
		},
		created() {
			this.getRelatedProducts();
		}
	}
</script>