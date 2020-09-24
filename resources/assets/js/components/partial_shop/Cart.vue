<template>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-danger" v-if="has_error">
			<ul>
				<li v-for="err in server_errors">{{ err[0] }}</li>
			</ul>
		</div>
		<div class="clearfix">
			<h4 class="ifg-header float-left">Cart</h4>
			<template v-if="!loading">
				<button v-if="cart_products.length" class="btn btn-primary float-right" :disabled="cartIsUpdating" @click="updateCartChanges">Update cart</button>
			</template>
		</div>
		<ul>
			<li v-for="item in updated_cart">qty:{{ item.quantity }}</li>
		</ul>
		<template v-if="loading">
			<center class="mt-5 pt-5">
				<half-circle-spinner
					  :animation-duration="1000"
					  :size="40"
					  color="#000"
					/>
			</center>
		</template>
		<template v-else>
			<template v-if="!cart_products.length">
				<div class="alert alert-warning text-center mt-3">
					Your cart is empty.
				</div>
			</template>
			<template v-else>
				<template v-if="cartIsUpdating">
					<center class="mt-5 pt-5">
						<half-circle-spinner
							  :animation-duration="1000"
							  :size="40"
							  color="#000"
							/>
						</center>
					</center>
				</template>
				<template v-else>
				<table class="table table-bordered table-striped mb-0 mt-3">
					<thead>
						<tr>
							<th width="45%">Product(s)</th>
							<th class="text-center" width="13%">Price</th>
							<th class="text-center" width="10%">Quantity</th>
							<th class="text-center" width="13%">Amount</th>
							<th class="text-center" width="15%">Action</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(cart,index) in cart_products" :key="index">
							<td class="align-middle">
								<div class="media">
                          	<img :src="'/storage/products/'+cart.product.image" class="media-object mr-2" width="20%" height="13%">
                           <div class="media-body pt-4">
                              <span class="d-block">{{ cart.carting.inventory.name }}</span>
										<span class="d-block text-success">In stock ({{cart.carting.inventory.quantity }})</span>
                           </div>
                       </div>
							</td>
							<td class="align-middle" align="center">&#8369;{{ cart.price }}</td>
							<td class="align-middle" align="center">
							
								<b-form-input id="qtyToAdd"
					                type="number"
					                v-validate="`between:${min},${cart.carting.inventory.quantity}`"
					                :name="'quantity'+index"
					                class="text-center"
					                :class="{'is-invalid':errors.has('quantity'+index)}"
					                v-model="cart.quantity">
					            </b-form-input>
					            <span class="text-danger" v-if="errors.has('quantity'+index)"><small>Invalid quantity</small></span>
							</td>
							<td class="align-middle" align="center"><span class="d-block">&#8369;{{ cart.total }}</span></td>
							<td class="align-middle text-center">
								<button class="btn btn-sm btn-danger" @click="removeProduct(index)" title="Remove product">Remove</button>
							</td>
						</tr>
					</tbody>
				</table>
				<div class="row mt-3">
					<div class="col-md-4 offset-md-8">
						<div class="card">
							<div class="card-body">
								<div class="clearfix mb-2"><h5 class="float-left">Subtotal: </h5><h5 class="float-right">&#8369;{{sub_total}}</h5></div>
								<div class="clearfix"><h4 class="float-left"><b>Total: </b></h4><h4 class="float-right"><b>&#8369;{{total}}</b></h4></div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<a href="/products" class="btn btn-secondary">Continue Shopping</a>
					</div>
					<div class="col-md-6">
						<div class="clearfix mb-2">
							<a href="/checkout" class="btn btn-lg btn-success float-right">Checkout</a>
						</div>
					</div>
				</div>
				</template>
			</template>
		</template>
	</div><!-- col-md-12 -->
</div>

</template>

<script>
/*variable.toLocaleString() with commas 1,000.00*/
	import { HalfCircleSpinner } from 'epic-spinners';
	import { required, between } from 'vuelidate/lib/validators';

    export default {
    	props: ['customer'],
       	data() {
	       	return {
	       		cart_products: [],
	       		server_errors: [],
	       		has_error: false,
	       		has_product: '',
	       		sub_total: '',
	       		total: '',
					loading: false,
					delivery_charge: '',
					discount_percent: '',
					discount_amount: '',
					updated_cart: [],
					updating: false,
					ipm_step: 1,
					ipm_vertical: false,
					quantity: 1,
					min: 1,
					totalProdQty: 0,
					cartIsUpdating: false,
	       	}
	   },
	   components: {
		   HalfCircleSpinner,
		   IntegerPlusminus
	   },
	   validations() {
	   		return {
	   			cart_products: {
	   				$each: {
	   					product: {
	   					}
	   				}
	   			},
	   			checkoutChoice: {required}
	   		}
	   },
       methods: {
       		cartDetails() {
				this.loading = true;

       			axios.get('/api/cart/products/'+this.customer.id)
       			.then(response => {
					this.loading = false;
       				this.cart_products = response.data
       			})
       			.catch(error => {
					this.loading = false;
       				console.log(error.response)
       			})
       		},
       		updateCartChanges() {

       			this.$validator.validate()
       			.then(result => {
       				if (result)
       				{
       					this.cartIsUpdating = true;
       					axios.post('/api/cart/update', {
       						cart_update: this.cart_products,
       					})
       					.then(response => {
       						let res = response.data
       						if (res.success)
       						{
       							console.log(res.product_qty);
       							this.$bus.$emit('update-qty', true);
       							this.has_error = false
       							this.cart_products = res.updated_cart;
       							this.totalProdQty = res.product_qty;
			       				this.sub_total = res.subtotal;
			       				this.total = res.total;
       						}
       						this.cartIsUpdating = false;
       					})
       					.catch(error => {
       						if (error.response.status == 422)
		                    {
		                      this.server_errors = error.response.data.errors
		                      this.has_error = true
		                    }

		                   this.cartIsUpdating = false;
       					})
       				}
       			});
       		},
       		updateCart() {

       			let product = this.cart_products[index]

       			this.$validator.validate()
       			.then(result => {
       				if (result)
       				{
       					axios.put('/api/cart/update/'+product.id, {
       						quantity: product.quantity
       					})
       					.then(response => {
       						let res = response.data

       						if (res.success)
       						{
       							this.$bus.$emit('update-qty', true);
       							this.has_error = false
       							this.cartDetails()
       							this.cartTotalDetails()
       						}
       					})
       					.catch(error => {
       						if (error.response.status == 422)
		                    {
		                      this.server_errors = error.response.data.errors
		                      this.has_error = true
		                    }
       					})
       				}
       			})
       			// this.updating = true;
       			// axios.post('/api/cart/update', {
       			// 	cart_update: this.cart_products
       			// })
       			// .then((response) => {
       			// 	this.updating = false;
       			// 	if (response.data.success)
       			// 	{
       			// 		//Swal('Updated', 'Cart has been updated', 'success')
       			// 		this.cartDetails()
       			// 		this.cartTotalDetails()
       			// 		this.$bus.$emit('update-qty', true);
       			// 	}
       			// })
       			// .catch((error) => {
       			// 	this.updating = false;
       			// 	console.log(error.response);
       			// });
       		},
       		updateQuantity(index) {
       			
       			let product = this.cart_products[index]

       			this.$validator.validate()
       			.then(result => {
       				if (result)
       				{
       					axios.put('/api/cart/update/'+product.id, {
       						quantity: product.quantity
       					})
       					.then(response => {
       						let res = response.data
       						console.log(res);
       						if (res.success)
       						{
       							this.$bus.$emit('update-qty', true);
       							this.has_error = false
       							this.cartDetails()
       							this.cartTotalDetails()
       						}
       					})
       					.catch(error => {
       						if (error.response.status == 422)
		                    {
		                      this.server_errors = error.response.data.errors
		                      this.has_error = true
		                    }
       					})
       				}
       			})
       		},
       		removeProduct(index) {
       			let product = this.cart_products[index]

	       		Swal({
					  title: 'Are you sure you want to remove this product into your cart?',
					  text: '',
					  type: 'warning',
					  showCancelButton: true,
					  confirmButtonText: 'Yes',
					  cancelButtonText: 'No'
					}).then((result) => {
					  if (result.value) {
					  	axios.delete('/api/cart/remove/'+product.id)
						.then(response => {
							let res = response.data

							if (res.deleted)
							{
								this.$bus.$emit('update-qty', true);
								this.cartDetails();
								this.cartTotalDetails();
							}
						})
						.catch(error => {
							console.log(error.response)
						})
					  }
					});
       		}, 
       		cartTotalDetails() {
       			axios.get('/api/cart/total-details/'+this.customer.id)
       			.then(response => {
       				let res = response.data;
       				//console.log(res);
       				this.totalProdQty = res.product_qty;
       				this.sub_total = res.subtotal;
       				this.total = res.total;
       			})
       			.catch(error => {
       				console.log(error.response)
       			})
       		},
       		proceedToCheckout(e) {
       			this.$refs.checkoutModal.show();
       		},
       		submitCheckoutChoice(e) {
       			e.preventDefault();
       			this.saveCheckoutChoice();
       		},
       		saveCheckoutChoice() {
       			this.$v.checkoutChoice.$touch();
       		}
       },
       created() {
       		this.cartDetails()
       		this.cartTotalDetails();
       }
    }
</script>