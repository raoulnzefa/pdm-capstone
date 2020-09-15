<template>
	<div>
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div>
					<h3 class="ifg-header">Your Cart</h3>
				</div>
				<template v-if="loading">
					<center class="mt-5 pt-5">
						<half-circle-spinner
							  :animation-duration="1000"
							  :size="40"
							  color="#ff1d5e"
							/>
					</center>
				</template>
				<template v-else>
					<template v-if="cartItems.length">
						<table class="table table-bordered mt-4">
							<thead>
								<tr>
									<th width="45%">Product(s)</th>
									<th class="text-center" width="13%">Price</th>
									<th class="text-center" width="10%">Qty</th>
									<th class="text-center" width="13%">Total Price</th>
									<th class="text-center" width="10%">Remove</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(product,index) in cartItems" :key="index">
									<td class="align-middle">
										<div class="media">
			                        <img :src="'/storage/products/'+product.inventory.product.product_image" class="media-object mr-2" width="20%" height="13%" alt="product image">
			                        <div class="media-body pt-4">
			                           <span class="d-block">{{ product.product_name }}</span>
												<span class="d-block text-success">In stock: {{product.in_stock}}</span>
			                        </div>
			                     </div>
									</td>
									<td class="align-middle" align="center">{{ formatMoney(product.price) }}</td>
									<td class="align-middle" align="center">
										<select class="form-control" @change="updateCart(index)" v-model="product.quantity">
											<option v-for="(x,index) in product.in_stock" :value="x" :selected="x === product.quantity">{{ x }}</option>
										</select>
									</td>
									<td class="align-middle" align="center">{{ formatMoney(product.total) }}</td>
									<td class="align-middle" align="center">
										<button class="btn btn-danger btn-sm" @click="removeProduct(product.id)"><i class="fa fa-remove"></i></button>
									</td>
								</tr>
							</tbody>
						</table>
						<div class="row mt-3">
							<div class="col-md-4 offset-md-8">
								<div class="card">
									<div class="card-body">
										<div class="clearfix mb-2"><h5 class="float-left">Subtotal: </h5><h5 class="float-right">{{ subtotal }}</h5></div>
										<div class="clearfix"><h4 class="float-left"><b>Total: </b></h4><h4 class="float-right"><b>{{ subtotal }}</b></h4></div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<a href="/products" role="button" class="btn btn-outline-secondary" :disable="isQtyUpdating">Continue Shopping</a>
							</div>
							<div class="col-md-6">
								<div class="clearfix mb-2">
									<a href="/checkout" role="button" class="btn btn-lg btn-success float-right" :disable="isQtyUpdating">Checkout</a>
								</div>
							</div>
						</div>
					</template>
					<template v-else> <!-- cart is empty -->
						<div class="alert alert-warning text-center mt-3">
							Your cart is empty.
						</div>
					</template>
				</template>
			</div>
		</div>
	</div>
</template>

<script>
	import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		props: ['customer'],
		data() {
			return {
				cartItems: [],
				loading: false,
				isQtyUpdating: false,
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getCart() {
				this.loading = true;
				axios.get('/api/cart/products/'+this.customer.id)
    			.then(response => {
				this.loading = false;
    				this.cartItems = response.data
    			})
    			.catch(error => {
				this.loading = false;
    				console.log(error.response)
    			});
			},
			getCartNoLoading() {
				axios.get('/api/cart/products/'+this.customer.id)
    			.then(response => {
    				this.cartItems = response.data
    			})
    			.catch(error => {
    				console.log(error.response)
    			});
			},
			formatMoney(price) {
				return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(price);
			},
			removeProduct(prodId) {
				Swal({
				  title: 'You are about to remove this product?',
				  text: '',
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Yes',
				  cancelButtonText: 'No'
				}).then((result) => {
					if (result.value) {
				  		axios.delete('/api/cart/remove/'+prodId)
						.then(response => {
							let res = response.data

							if (res.deleted)
							{
								this.$bus.$emit('update-qty', true);
								this.getCartNoLoading();
							}
						})
						.catch(error => {
							console.log(error.response)
						})
					}
				});
			},
			updateCart(prodIndex) {
				let prod = this.cartItems[prodIndex];
				this.isQtyUpdating = true;
				axios.put('/api/cart/update/'+prod.id, {
					quantity: prod.quantity
				})
				.then(response => {
					this.isQtyUpdating = false;
					if (response.data.success) {
						this.$bus.$emit('update-qty', true);
						this.getCartNoLoading();
						Swal('Cart has been updated','', 'success');
					}
				})
				.catch(error => {
					this.isQtyUpdating = false;
					console.log(error.response);
				});
			}
		},
		computed: {
			subtotal() {
				let subtotal = null;
				subtotal = this.cartItems.reduce(
					(a, b) => a + parseFloat(b.total),
					0
				);

				return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(subtotal);
			}
		},
		mounted() {
			this.getCart();
		}
	}
</script>