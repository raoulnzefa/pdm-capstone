<template>
	<div class="row product-frame mb-4">
		<div class="col-sm-6 col-lg-6">
			<div>
				<img :src="'/storage/products/'+product.product_image" class="img-fluid" width="80%">	
			</div>
		</div>
		<div class="col-sm-6 col-lg-6">
			<div class="card">
				<div class="card-body">
					<h3 class="card-title">{{ product.product_name }}</h3>
					<div v-if="product.product_has_variant < 1">
						<div>
							<h4>&#8369;{{ product.product_no_variant.price }}</h4>
							<h5>In stock: {{ product.inventory.inventory_stock }}</h5>
						</div>
						<div class="row mt-5">
							<div class="col-md-6 col-lg-6">
								<div class="form-group">
									<label class="font-weight-bold">Quantity:</label>
									<select class="form-control">
										<option></option>
									</select>
								</div>
							</div>
						</div>
						<button class="btn btn-primary">Add to cart</button>
					</div>
					<div v-else>
						<!--price and available stocks -->
						<!-- <add-cart-with-variants 
							:customer="customer"
							:product="product" 
							:variant="variant"
							:productVariants="product_variants"></add-cart-with-variants> -->
					</div>
				</div>
			</div>
		</div><!-- col-md-6 -->
	</div>
</template>

<script>
	export default {
		props: ['product','variant', 'product_variants','customer'],
		data() {
			return {

			}
		}
	}
</script>