<template>
	<div>
		<input type="hidden" :value="prod_stock">
		<h2 class="font-weight-bold">{{ product.name }}</h2>
		<template v-if="product.has_variant == 'Yes'">
			<h3 v-if="variant_price != ''">&#8369;{{ variant_price }}</h3>
			<h6 v-if="variant_status != ''" class="text-uppercase">
				<!-- In stock -->
				<template v-if="variant_qty > 0">
					<span class="text-success">{{ variant_status }} ( {{ variant_qty }} )</span>
				</template>
				<!-- Out of stock -->
				<template v-else>
					<span class="text-danger">{{ variant_status }} ( {{ variant_qty }} )</span>
				</template>
				
			</h6>
		</template>
		<template v-else>
			<h3>&#8369;{{ product.product_no_variant.price }}</h3>
			<template v-if="product.product_no_variant.inventory.quantity > 0">	
				<h6 class="text-success text-uppercase">{{ product.product_no_variant.inventory.availability }} ( {{ product.product_no_variant.inventory.quantity }} )</h6>
			</template>
			<template v-else>
				<h6 class="text-danger text-uppercase">{{ product.product_no_variant.inventory.availability }} ( {{ product.product_no_variant.inventory.quantity }} )</h6>
			</template>
		</template>
		<div class="row mt-4">
			<div class="col-md-12">
				<form>
					<div v-if="product.has_variant === 'Yes'">
						<div class="form-group">
							<label>Choose Option:</label>
							<select class="form-control" v-model.trim="$v.variant.$model" @change="getVariantDetails" :class="{'is-invalid' : $v.variant.$error }">
								<option :value="null" disabled>Please select an option</option>
								<option value="" v-if="loadingA">Loading...</option>
								<option v-else v-for="(variant, index) in variants" :value="index" :disabled="variant.inventory.quantity <= 0">{{ variant.variant }} <span v-if="variant.inventory.quantity <= 0">Out of stock</span></option>
							</select>
							<div v-if="$v.variant.$error">
								<span class="error-feedback" v-if="!$v.variant.required">Option is required</span>
							</div>
						</div><!-- /selection-->
						<div class="form-group mt-4" v-if="validVariantStock >= 1">
					    	<label for="">Quantity:</label>
					    	<select class="custom-select"
				            :class="{'is-invalid':$v.quantity.$error}"
				            v-model.trim="$v.quantity.$model">
						   	<option v-for="(x,index) in validVariantStock" :key="index" :value="x">{{x}}</option>
						  	</select>
					    	<div v-if="$v.quantity.$error">
								<span class="error-feedback" v-if="!$v.quantity.required">Please enter a quantity</span>
								<template v-if="$v.quantity.required">
									<span class="error-feedback" v-if="!$v.quantity.minValue">Please enter a quantity not less than {{ $v.quantity.$params.minValue.min }}</span>
									<template v-if="$v.quantity.minValue">
										<span class="error-feedback d-block" v-if="!$v.quantity.between">Please enter a quantity that not greater than available stocks</span>
									</template>
								</template>
							</div>
						  	<div class="form-group">
						    	<button type="button" class="btn btn-primary ifg-btn mt-4" :disabled="submit" @click="addToCart">ADD TO CART</button>	
						  	</div>
					  	</div><!-- input quainty and button add to cart section -->
					  	<div class="alert alert-danger" v-if="showErrorMsg">
							<p class="mb-0">You already added the available quantity to the cart.</p>
						</div>
					</div>
					<div v-else>
						<template>
							<template v-if="validStock > 0"><!-- product_no_variant section -->
								<div class="form-group">
							    	<label for="">Quantity:</label>
							    	<select class="custom-select"
						            :class="{'is-invalid':$v.quantity.$error}"
						            v-model.trim="$v.quantity.$model">
								   	<option v-for="(x,index) in validStock" :key="index" :value="x">{{x}}</option>
								  	</select>
							    	<div v-if="$v.quantity.$error">
										<span class="error-feedback" v-if="!$v.quantity.required">Quantity is required</span>
										<template v-if="$v.quantity.required">
											<span class="error-feedback" v-if="!$v.quantity.minValue">Please enter a quantity not less than {{ $v.quantity.$params.minValue.min }}</span>
											<template v-if="$v.quantity.minValue">
												<span class="error-feedback d-block" v-if="!$v.quantity.between">Please enter a quantity that not greater than available stocks</span>
											</template>
										</template>
									</div>
							  	</div>
							  	<div class="form-group">
							    	<button type="button" class="btn btn-primary ifg-btn mt-4" @click="addToCart" :disabled="submit">ADD TO CART</button>	
							  	</div>
							</template><!-- product_no_variant section -->
							<template v-else>
								<div class="alert alert-danger">
									<p class="mb-0">You already added the available quantity to the cart.</p>
								</div>
							</template>
						</template>
					</div>
				</form>

			</div>
		</div>
		<div class="mt-4" v-html="product.description">
			
		</div>
	</div>
</template>
<script>
	import { required, minValue, between, maxValue, helpers } from 'vuelidate/lib/validators';
	import { IntegerPlusminus } from 'vue-integer-plusminus';

	const cartQty = helpers.regex('cartQty', /^([1-9])[0-9]*$/);

	export default {
		props: ['product', 'customer', 'cart'],
		data() {
			return {
				variants: [],
				details: [],
				loadingA: false,
				variant: null,
				price: '',
				sku: '',
				shipping_rate: '',
				quantity: 1,
				submit: false,
				server_errors: [],
				details_loading: false,
				variant_price: '',
				variant_status: '',
				variant_id: '',
				variant_stock: '',
				no_variant_stock: '',
				validStock: '',
				cartProdQty: '',
				cartingID: '',
				prodNum: '',
				validVariantStock: ''
			}
		},
		components: {
			IntegerPlusminus
		},
		validations() {
			if (this.product.has_variant == 'Yes') {
				return {
					variant: {
						required
					},
					quantity: {
					 	required,
					 	minValue: minValue(1),
					 	between: between(1, this.validVariantStock),
					 	cartQty
					}
				}
			} else {
				return {
					quantity: {
						required,
						minValue: minValue(1),
						between: between(1, this.validStock),
						cartQty
					}
				}
			}
		},
		methods: {
			getVariants() {
				this.loadingA = true;
				axios.get('/api/product-variants/active/'+this.product.sku)
				.then((response) => {
					this.loadingA = false;
					//console.log(response.data);
					this.variants = response.data;
				})
				.catch((error) => {
					this.loadingA = false;
					console.log(error.response);
				});
			},
			getVariantDetails(e) {
				if (this.variant === '') {
					this.variant_price = '';
					this.variant_status = '';
					this.variant_qty = '';
					this.variant_id = '';
					this.quantity = 1;
					this.variant_stock = '';
				} else {
					var variant = this.variants[this.variant];
					this.variant_price = variant.price;
					this.variant_status = variant.inventory.availability;
					this.variant_qty = variant.inventory.quantity;
					this.variant_id = variant.number;
					this.quantity = 1;
					this.variant_stock = variant.inventory.quantity;
				}
				
			},
			addToCart() {
				
				let form = new FormData();

				form.append('customer_id', this.customer.id);
				form.append('product_sku', this.product.sku);
				
				if (this.product.has_variant == 'Yes') {
					form.append('variant_number', this.variant_id);
				} else {
					form.append('no_variant_number', this.product.product_no_variant.number);
				}

				form.append('quantity', this.quantity);

			

				this.$v.$touch();

				if (!this.$v.$invalid) {
					// submit add to cart method
					this.submitAddToCart(form);
				}
				
			},
			submitAddToCart(form) {
				this.submit = true;

					axios.post('/api/cart/add-cart', form)
					.then((response) => {
						//console.log('HEY'+response.data);
						if (response.data.success) {	

			
							//console.log(response.data);
							this.$bus.$emit('update-qty', true)
							
							Swal('Product added to cart','', 'success')
							.then(okay => {
								if (okay.value)
								{
									window.location.reload();
									this.submit = false;
									this.quantity = 1;
									this.variant = null;
									this.variant_id = '';
									this.variant_price = '';
									this.variant_status = '';
									this.variant_qty = '';
									this.variant_stock = '';
									
									if (this.product.has_variant == 'Yes') {
										this.$nextTick(() => { this.$v.$reset() });
									}
								}
							});

						} else if (response.data.invalid_qty) {
							this.submit = false;
							this.showErrorMsg = true;
						}

					})
					.catch((error) => {
						this.submit = false;
						console.log(error.response);
					});
				
			}
		},
		created() {
			if (this.product.has_variant == 'Yes') {
				this.getVariants();
			}
		},
		computed: {
			prod_stock() {
				var stock = '';
				let cart = '';

				if (this.product.has_variant == 'No') {
					stock = this.product.product_no_variant.inventory.quantity;
					this.prodNum = this.product.product_no_variant.number;
				} else {
					stock = this.variant_stock;
					this.prodNum = (this.variant_id) ? this.variant_id : '';
				}


				if (!this.cart.length) {
					if (this.product.has_variant == 'No') {
						this.validStock = stock;
					} else {
						this.validVariantStock = stock;
					}
				} else {
					// if cart is not empty
					if (this.prodNum) {
						cart = this.cart.find(x => x.carting_id == this.prodNum);
						if (cart) {
							this.cartProdQty = cart.quantity;
							this.cartingID = cart.carting_id;
						} else {
							this.cartProdQty = '';
							this.cartingID = '';
						}
						// this.validStock = stock - this.cartProdQty;
							//this.validStock = 999
						if (this.product.has_variant == 'No') {
							this.validStock = stock - this.cartProdQty;
						} else {
							this.validVariantStock = stock - this.cartProdQty;
						}
					}


				}

				return stock;
			},
			showErrorMsg() {
				
					if (this.validVariantStock === 0) {
						return true
					} else { 
						return false;
					}
				
			}
		}

	}
</script>