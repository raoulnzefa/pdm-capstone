<template>
		<div class="row product-frame mb-4">
		<div class="col-sm-6 col-lg-6">
			<div>
				<img :src="'/storage/products/'+product.product_image" class="img-fluid">	
			</div>
		</div>
		<div class="col-sm-6 col-lg-6">
			<div class="card">
				<div class="card-body">
					<h3 class="card-title">{{ product.product_name }}</h3>
					<div v-if="variant">
						<h4>{{ formatMoney(variantPrice) }}</h4>
						<span class="badge badge-success" style="font-size: 16px;">In stock: {{variantInStock}}</span>
						<h6 class="mb-1 mt-4 font-weight-bold">Description:</h6>
						<p class="text-justify">{{ product.product_description }}</p>
					</div>
					<form @submit.prevent="addToCart">
					<div class="row">
						<div class="col-md-6 col-lg-6">
							<div class="form-group">
								<label class="font-weight-bold">Select variant:</label>
								<select class="form-control"
									v-model.trim="$v.variant_item.$model"
									:class="{'is-invalid': $v.variant_item.$error}">
									<option value="" disabled>Select a variant</option>
									<option v-for="(x,index) in product.product_with_variants" :key="index" :value="x.inventory_number">{{ x.variant_value }}</option>
								</select>
								<div v-if="$v.variant_item.$error">
									<span class="error-feedback" v-if="!$v.variant_item.required">Please select a variant</span>	
								</div>
							</div>
							<div class="form-group mb-0">
								<label class="font-weight-bold">Quantity:</label>
								<input type="text"
									class="form-control" 
									v-model.trim="$v.quantity.$model"
									:class="{'is-invalid': $v.quantity.$error}">
							</div>
						</div>
					</div>
					<div v-if="$v.quantity.$error" class="mt-0">
						<span class="error-feedback" v-if="!$v.quantity.required">Please enter a quantity</span>	
						<template v-if="$v.quantity.required">
							<span class="error-feedback" v-if="!$v.quantity.numbersOnly">Please enter a valid quantity</span>
							<template v-if="$v.quantity.numbersOnly">
								<span class="error-feedback" v-if="!$v.quantity.between">Please enter a quantity not greater than the available stocks</span>
							</template>
						</template>	
					</div>
					<button type="submit" class="btn btn-primary mt-4" :disabled="!variant_item"><i class="fa fa-shopping-cart"></i> Add to cart</button>
				</form>
				</div>
			</div>
		</div><!-- col-md-6 -->
	</div>
</template>
<script>
	import { required, between, helpers } from 'vuelidate/lib/validators';

	const numbersOnly = helpers.regex('numbersOnly', /^([1-9])[0-9]*$/);

	export default {
		props: ['product','variant', 'product_variants','customer'],
		data() {
			return {
				quantity: 1,
				in_cart_qty: null,
				price: null,
				variant_item: this.variant.inventory_number,
				productInCartNum: '',
				cartItems: [],
				inventoryStock: null,
			}
		},
		validations() {
			return {
				quantity: {
					required,
					numbersOnly,
					between: between(1,this.quantityLimit),
				},
				variant_item: {required},
			}
		},
		methods: {
			addToCart() {
				this.$v.$touch();

				if (!this.$v.$invalid) {
					axios.post('/api/cart/add-cart-variant', {
						variant: this.variant_item,
						quantity: this.quantity,
						customer_id: this.customer.id,
					})
					.then(response => {
						if (response.data.success) {
							this.$bus.$emit('update-qty', true);

							Swal('Product has been added to cart','', 'success')
							.then(okay => {
								if (okay.value)
								{
									this.quantity = 1;
									this.getCart();
								}
							})
						}
					})
					.catch(error => {
						console.log(error.response);
					});
				}
			},
			getCart() {
				axios.get('/api/cart/products/'+this.customer.id)
				.then(response => {
					this.cartItems = response.data;
				})
				.catch(error => {
					console.log(error.response);
				});
			},
			formatMoney(price) {
             return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(price);
         }
		},
		computed: {
			variantPrice() {
				let variant = null;
				let price = null;

				if (this.variant_item) {
					variant = this.product_variants.find(x => x.inventory_number == this.variant_item);
					price = variant.variant_price;

					return price;
				}

				return null;
				
			},
			variantInStock() {
				let variant = null;
				let stock = null;

				if (this.variant_item) {
					variant = this.product_variants.find(x => x.inventory_number == this.variant_item);
					stock = variant.inventory.inventory_stock;
					this.inventoryStock = stock;
					return stock;
				}

				return null;
			},
			quantityLimit() {
				let qtyLimit = null;
				if (this.variant_item) {
					if (this.cartItems.length) {
						let item = this.cartItems.find(x => x.inventory_number == this.variant_item);

						if (item) {

							return qtyLimit = this.inventoryStock - item.quantity;
						} else {
							return qtyLimit = this.inventoryStock;
						}

					} else {
						return qtyLimit = this.inventoryStock;
					}
				}
				return this.inventoryStock;
			}
		},
		mounted() {
			this.getCart();
		}
	}
</script>