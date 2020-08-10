<template>

	<form @submit.prevent="addProductToCart">
	  	<div class="form-group">
	    	<label for="">Quantity</label>
	    	<input type="number" name="quantity" class="form-control col-sm-2" :class="{'is-invalid': errors.has('qty')}" v-model.trim="$v.qty.$model">
	    	<div v-if="$v.qty.$error">
            <span class="invalid-feedback" v-if="!$v.qty.required">Qty is required</span>
            <template v-if="$v.qty.required">
            	<span class="invalid-feedback" v-if="!$v.qty.between">Please input a quantity that not greater than available stocks {{ $v.qty.$params.between.max}}</span>
            </template>
        </div>
	  	</div>
	  	<div class="form-group">
	    	<button type="submit" class="btn btn-dark ifg-btn" :disabled="addingToCart"><i class="fa fa-plus"></i>&nbsp;<span v-if="!addingToCart">Add to Cart</span><span v-else>Adding...</span></button>
	  	</div>
	</form>
</template>

<script>
	import { required, between } from 'vuelidate/lib/validators';

	export default {
		props: ['customer', 'product'],
		data() {
			return {
				qty: 1,
				addingToCart: false,
				inStock: this.product.inventory.quantity,
			}
		},
		validations() {
			return {
				qty: {
					required,
					between: between(1, inStock)
				}
			}
		},
		methods: {
			addProductToCart() {
				this.$validator.validate()
				.then(result => {
					if (result)
					{
						this.addingToCart = true;

						if (this.qty >= this.product.inventory.quantity)
						{
							Swal('Invalid', 'Please input a quantity that not greater than available stocks.', 'error')
							.then((okay) => {
								
								this.qty = 1;
								this.$validator.reset();
								this.addingToCart = false;
							});
						}
						else
						{
							axios.post('/api/cart/add-cart', {
								email: this.customer,
								sku: this.product.sku,
								quantity: this.qty
							})
							.then(response => {
								this.addingToCart = false;
								let data = response.data;
								if (data.success)
								{
									this.$bus.$emit('update-qty', true);
									Swal('Product added to cart','', 'success')
									.then(okay => {
										if (okay.value)
										{
											this.qty = 1
										}
									})
								}
							})
							.catch(error => {
								this.addingToCart = false;
								console.log(error.response);
							});	
						}
						
					}
				})
			}
		}
	}
</script>