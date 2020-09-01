<template>
	<div class="row product-frame mb-4">
		<div class="col-sm-6 col-lg-6">
			<div class="">
				<img :src="'/storage/products/'+product.product_image" class="img-fluid">	
			</div>
		</div>
		<div class="col-sm-6 col-lg-6">
			<form @submit.prevent="addToCart">
			<div class="card">
				<div class="card-body">
					<h3 class="card-title">{{ product.product_name }}</h3>
					<div>
						<h4>{{ formatMoney(product.product_no_variant.price) }}</h4>
						<span class="badge badge-success" style="font-size: 16px;">In stock: {{ product.product_no_variant.inventory.inventory_stock}}</span>
						<h6 class="mb-1 mt-4 font-weight-bold">Description:</h6>
						<p class="text-justify">{{ product.product_description }}</p>
					</div>
					<div class="row mt-5">
						<div class="col-md-6 col-lg-6">
							<div class="form-group mb-0">
								<label class="font-weight-bold">Quantity:</label>
								<input type="text" class="form-control w-50"
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
					<button type="submit" class="btn btn-primary mt-4" :disabled="isBtnClicked"><i class="fa fa-shopping-cart"></i> Add to cart</button>
				</div>
			</div>
			</form>
		</div><!-- col-md-6 -->
	</div>
</template>

<script>
	import { required, between, helpers } from 'vuelidate/lib/validators';

	const numbersOnly = helpers.regex('numbersOnly', /^([1-9])[0-9]*$/);

	export default {
		props: ['product','customer'],
		data() {
			return {
				quantity: 1,
				isBtnClicked: false,
				cartItems: [],
				stock: this.product.product_no_variant.inventory.inventory_stock,
				inventory_num: this.product.product_no_variant.inventory_number,
			}
		},
		validations() {
			return {
				quantity: {
					required,
					numbersOnly,
					between: between(1, this.quantityLimit),
				}
			}
		},
		methods: {
			addToCart() {
				this.$v.$touch();

				if (!this.$v.$invalid) {
					this.isBtnClicked = true;
					axios.post('/api/cart/add-cart-no-variant', {
						customer_id: this.customer.id,
						product: this.inventory_num,
						quantity: this.quantity,
					})
					.then(response => {
						this.isBtnClicked = false;
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
						this.isBtnClicked = false;
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
			quantityLimit() {
				let qtyLimit = null;

				if (this.cartItems.length) {
					let item = this.cartItems.find(x => x.inventory_number == this.inventory_num);

					if (item) {

						return qtyLimit = this.stock - item.quantity;
					} else {
						return qtyLimit = this.stock;
					}

				} else {
					return qtyLimit = this.stock;
				}

				return qtyLimit = this.stock;
			}
		},
		mounted() {
			this.getCart();
		}
	}
</script>