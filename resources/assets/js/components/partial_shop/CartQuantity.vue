<template>
<span class="badge badge-pill badge-danger">{{ total }}</span>
</template>

<script>
	export default {
		props: ['customer'],
		data() {
			return {
				total: 0
			}
		},
		methods: {
			cartQuantity() {
				axios.get('/api/cart/quantity/'+this.customer.id)
				.then(response => {
					let qty = response.data
					this.total = qty
				})
				.catch(error => {
					console.log(error.response)
				})
			}
		},
		created() {
			this.cartQuantity()
			this.$bus.$on('update-qty', data => {
				if (data == true)
				{
					this.cartQuantity()
				}
			})
		}

	}
</script>