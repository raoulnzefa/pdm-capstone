<template>
	<div class="text-center">
		<button type="button" class="btn btn-dark ifg-btn" @click="addToCart">Add to Cart</button>	
	</div>
	
</template>

<script>
	export default {
		props: ['customer', 'product'],
		data() {
			return {
				qty: 1
			}
		},
		methods: {
			addToCart() {
				axios.post('/api/cart/add-cart', {
					customer_id: this.customer.id,
					quantity: this.qty,
					product_sku: this.product.sku,
					no_variant_number: this.product.product_no_variant.number,
					product_name: this.product.name,
					price: this.product.price
				})
				.then(response => {
					console.log(response.data);
					if (response.data.success) {
						Swal('Product added to cart', '','success')
						this.$bus.$emit('update-qty', true)
					}

					if (response.data.invalid_qty) {
						Swal('Error', 'Invalid quantity', '', 'error');
					}
				})
				.catch(error => {
					console.log(error.response)
				});
				
			}
		}
	}
</script>