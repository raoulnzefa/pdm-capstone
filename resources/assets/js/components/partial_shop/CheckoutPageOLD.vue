<template>
<div class="mb-5">
	<h2 class="mb-4">Checkout</h2>
	<div class="row">
		<div class="col-md-6">
			<h4 class="mb-4">Address</h4>
			<div class="form-group">
			    <div class="form-check">
			      <input class="form-check-input" type="checkbox" id="defaultAddr" @change="defaultAddress">
			      <label class="form-check-label" for="defaultAddr">
			        Use my default address
			      </label>
			    </div>
			</div>
			<div>
				<template v-if="!default_addr">
					<div class="form-group">
						<label>First Name:</label>
						<input type="text" name="first_name" class="form-control" :class="{'is-invalid':$v.first_name.$error}" v-model.trim="$v.first_name.$model" tabindex="1">
						<div v-if="$v.first_name.$error">
							<span class="error-feedback" v-if="!$v.first_name.required">First Name is required</span>
						</div>
					</div>
					<div class="form-group">
						<label>Last Name:</label>
						<input type="text" name="last_name" class="form-control" :class="{'is-invalid':$v.last_name.$error}" v-model.trim="$v.last_name.$model" tabindex="2">
						<div v-if="$v.last_name.$error">
							<span class="error-feedback" v-if="!$v.last_name.required">Last Name is required</span>
						</div>
					</div>
					<div class="form-group">
						<label>Street:</label>
						<input type="text" name="street" class="form-control" :class="{'is-invalid':$v.street.$error}" v-model.trim="$v.street.$model" tabindex="3">
						<div v-if="$v.street.$error">
							<span class="error-feedback" v-if="!$v.street.required">Street is required</span>
						</div>
					</div>
					<div class="form-group">
						<label>Province:</label>
						<select class="form-control" v-model="$v.province.$model" @change="municipalityList" tabindex="4" :class="{'is-invalid': $v.province.$error}" name="province">
                            <option value="">Select Province</option>
                            <option v-for="(province, index) in provinces" :key="index" :value="province.id">{{ province.name }}</option>
                        </select>
						<div v-if="$v.province.$error">
							<span class="error-feedback" v-if="!$v.province.required">Province is required</span>
						</div>
					</div>
					<div class="form-group">
						<label>Municipality:</label>
						<select class="form-control" v-model="$v.municipality.$model" @change="barangayList" :class="{'is-invalid': $v.municipality.$error}" tabindex="5" name="municipality">
                            <option value="" v-if="!municipalities.length">Select Province First</option>
                            <option value="" v-else>Select Municipality</option>
                            <option v-for="(municipal, index) in municipalities" :key="index" :value="municipal.id">{{ municipal.name }}</option>
                        </select>
						<div v-if="$v.municipality.$error">
							<span class="error-feedback" v-if="!$v.municipality.required">Municipality is required</span>
						</div>
					</div>
					<div class="form-group">
						<label>Barangay:</label>
						<select class="form-control" v-model="$v.barangay.$model" :class="{'is-invalid': $v.barangay.$error}" tabindex="6" name="barangay">
                            <option value="" v-if="!barangays.length">Select Municipality First</option>
                            <option value="" v-else>Select Barangay</option>
                            <option v-for="(item, index) in barangays" :key="index" :value="item.id">{{ item.name }}</option>
                        </select>
						<div v-if="$v.barangay.$error">
							<span class="error-feedback" v-if="!$v.barangay.required">Barangay is required</span>
						</div>
					</div>
					<div class="form-group">
						<label>Phone Number:</label>
						<input type="text" name="phone_number" class="form-control" :class="{'is-invalid' : $v.phone_number.$error}" v-model.trim="$v.phone_number.$model" tabindex="7">
						<div v-if="$v.phone_number.$error">
							<span class="error-feedback" v-if="!$v.phone_number.required">Phone number is required</span>
						</div>
					</div>
					<div class="form-group">
						<label>Company:</label>
						<input type="text" name="company" class="form-control" tabindex="8">
					</div>
				</template>
				<template v-else>
					<div class="form-group">
						<label>First Name:</label>
						<input type="text" name="first_name" class="form-control" v-model="customer.first_name" readonly>
					</div>
					<div class="form-group">
						<label>Last Name:</label>
						<input type="text" name="last_name" class="form-control" v-model="customer.last_name" readonly>
					</div>
					<div class="form-group">
						<label>Street:</label>
						<input type="text" name="street" class="form-control" v-model="customer.street" readonly>
					</div>
					<div class="form-group">
						<label>Province:</label>
						<input type="text" name="province" class="form-control" v-model="customer.province.name" readonly>
					</div>
					<div class="form-group">
						<label>Municipality:</label>
						<input type="text" name="municipality" class="form-control" v-model="customer.municipality.name" readonly>
					</div>
					<div class="form-group">
						<label>Barangay:</label>
						<input type="text" name="barangay" class="form-control" v-model="customer.barangay.name" readonly>
					</div>
					<div class="form-group">
						<label>Phone Number:</label>
						<input type="text" name="phone_number" class="form-control" v-model="customer.phone_number" readonly>
					</div>
					<div class="form-group">
						<label>Company:</label>
						<input type="text" name="company" class="form-control" v-model="customer.company" readonly>
					</div>
				</template>
			</div><!-- address form -->
			<h4 class="mt-5 mb-4">Shipping</h4>
			<div>
				<h2>{{shipping_method}}</h2>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="shipping_method" id="shippingOne" value="Shipping" v-model.trim="$v.shipping_method.$model" :class="{'is-invalid' : $v.shipping_method.$error}">
					<label class="form-check-label" for="shippingOne">
				    Shipping
					</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="shipping_method" id="shippingTwo" value="Pickup" v-model.trim="$v.shipping_method.error" :class="{'is-invalid' : $v.shipping_method.$error}">
					<label class="form-check-label" for="shippingTwo">
					Pickup
					</label>
				</div>
			</div>
			<h4 class="mt-5 mb-4">Payment</h4>
			<div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="payment_method" id="paymentA" value="PayPal" v-model.trim="$v.payment_method.model" :class="{'is-invalid' : $v.payment_method.$error}">
					<label class="form-check-label" for="paymentA">
				    PayPal
					</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="payment_method" id="paymentB" value="Bank Deposit" v-model.trim="$v.payment_method.model" :class="{'is-invalid' : $v.payment_method.$error}">
					<label class="form-check-label" for="paymentB">
					Bank Deposit
					</label>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="radio" name="payment_method" id="paymentC" value="Cash" v-model.trim="$v.payment_method.model" :class="{'is-invalid' : $v.payment_method.$error}">
					<label class="form-check-label" for="paymentC">
					Cash
					</label>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<h4 class="mb-4">Order</h4>
			<div>
				<ul class="list-group" v-for="(prod, index) in cart_details">
				  	<li class="list-group-item pl-3">
				  		<div class="clearfix">
				  			<img :src="'/storage/products/'+prod.product.image" width="100" height="80" class="img-fluid float-left mr-2">
				  			<span class="d-block">{{prod.product.name}}</span>
				  			<span class="d-block">&#8369;{{prod.price}}</span>
				  			<span class="d-block">x{{prod.quantity}}</span>
				  		</div>
				  	</li>
				</ul>
				<div class="row mt-3">
					<div class="col-md-7 offset-md-5">
						<div class="clearfix">
							<h5 class="float-left">Subtotal: </h5>
							<h5 class="float-right">&#8369;0.00</h5>
						</div>
						<div class="clearfix">
							<h5 class="float-left">Discount: </h5>
							<h5 class="float-right">&#8369;0.00</h5>
						</div>
						<div class="clearfix">
							<h5 class="float-left">Shipping: </h5>
							<h5 class="float-right">&#8369;0.00</h5>
						</div>
						<div class="clearfix">
							<h5 class="float-left">Total Amount: </h5>
							<h5 class="float-right">&#8369;0.00</h5>
						</div>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-md-7 offset-md-5">
						<div class="card">
							<div class="card-body">
								<div class="form-group">
									<label>Voucher Code:</label>
									<input type="text" name="" class="form-control" :class="{'is-invalid' : $v.voucher_code.$error}" v-model.trim="$v.voucher_code.$model">
									<div v-if="$v.voucher_code.$error">
										<span class="error-feedback" v-if="!$v.voucher_code.required">Voucher code is required</span>
									</div>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="exampleRadios" id="voucherBenefit1" value="discount" :class="{'is-invalid':$v.voucher_benefit.$error}" v-model.trim="$v.voucher_benefit.$model">
									<label class="form-check-label" for="voucherBenefit1">
									Discount
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="exampleRadios" id="voucherBenefit2" value="free_shipping" :class="{'is-invalid':$v.voucher_benefit.$error}" v-model.trim="$v.voucher_benefit.$model">
									<label class="form-check-label" for="voucherBenefit2">
									Free shipping
									</label>
								</div>
								<div class="mt-2" v-if="$v.voucher_benefit.$error">
									<span class="error-feedback" v-if="!$v.voucher_benefit.required">Voucher benefit is required</span>
								</div>
								<button class="btn btn-dark mt-3" @click="applyVoucher" :disabled="applying_voucher"><span v-if="applying_voucher"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>Apply Voucher</button>
							</div>
						</div>
					</div>
				</div>
			</div><!-- div order details -->
			<div class="clearfix">
				<div class="form-group float-right">
				    <div class="form-check">
				      <input class="form-check-input" type="checkbox" id="termsCondition" @change="acceptTermsConditions">
				      <label class="form-check-label" for="termsCondition">
				        I have read and accept the <b><a href="javascript:void(0)" data-toggle="modal" data-target="#exampleModalCenter" class="text-dark">Terms and Conditions.</a></b>
				      </label>
				    </div>
				</div>
			</div>
			<div class="clearfix">
				<button class="btn btn-dark btn-lg float-right" @click="placeOrder" :disabled="not_complete"><span v-if="placing_order"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>Place Order</button>
			</div>
		</div>
	</div>
	<!-- Modal -->
	<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLongTitle">Terms and Conditions</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
   			<!-- <p class="">Welcome to the infinityfightgear.site which is operated by or for Infinity Fightgear shop, and thank you for visiting. By visiting infinityfightgear.site and accessing the information about the products provided by Webfinity, means you have read, understood and accepted these terms and conditions.</p> -->
			<p class="mb-1"><b>Account Registration</b></p>
			<p>For the customers, please fill up the input fields in the registration page to create your own account. Make sure you register a valid email for verification to receive a verification email to activate your account. Through this, you can able to login to our website using your email and password.</p>
			<p class="mb-1"><b>Order, Acceptance and Changes to order</b></p>
			<p>These terms and condition shall apply to our registered customers. The customer should log in first before placing an order. After the log in, customer may request orders through Webfinity. The customer may choose the order product that will be added to the cart on the website. The customer may add and remove product in the cart and can update the quantity of the chosen product before making any payment. Then the customer can checkout by clicking the proceed checkout button. In checkout page, the customer may change the address for his delivery. The acceptance of orders for every products shall be at the entire discretion of Webfinity. If the customers has any complains with the return order or decided to cancel the order, customer may see our Return and Cancellation Policy.</p>
			<p class="mb-1"><b>Modes of Shipping and Payment</b></p>
			<p>The customer can choose the type of Shipping method if the order is for Shipping or for Pickup. In payment method, if the customer choose the Shipping, customer can pay using PayPal or Bank Deposit; if the customer choose the Pickup, customer can pay using PayPal or Cash. In choosing PayPal payment, customer will redirect to PayPal website to finish the order payment; In Bank Deposit payment, customer will pay to the Bank account that is given to an order email notification. In Cash payment, the customer will visit the Webfinity shop to give the payment in cash.</p>
			<p class="mb-1"><b>Payment</b></p>
			<p>In Bank Deposit payment, the customer must pay the payment within 2 days. If the payment will not issue after 2 days, the order will be cancel. In Cash payment, the customer can reserve product within 2 days. If the product will not pick up and unpaid after 2 days, the reserved product will be cancel. If the reserved product is paid through PayPal payment, Webfinity will wait for the customer to pick up his order product within 3 days. After the payment, customer will receive an email notification indicating that the payment was receive. The customer will also receive a delivery email notification for order delivery.</p>
			<p class="mb-1"><b>Delivery Area</b></p>
			<p>INFINITY FIGHTGEAR offers delivery services within the Philippines only.</p>
			<p class="mb-1"><b>Shipping Limitations</b></p>
			<p>When an order is placed, it will be shipped to an address designated by the purchaser as long as that shipping address is compliant with the shipping restrictions contained on our Web Site. All purchases from our Web Site are made pursuant to a shipment process of Infinity Fightgear. As a result, risk of loss and title for products purchased from this Web Site pass to you upon delivery of the products to the carrier. You are responsible for filing any claims with carriers for damaged and/or lost shipments.</p>
			<p class="mb-1"><b>Responsible Use and Conduct</b></p>
			<p>Please make sure that any information you provide will always be accurate, correct, and up to date. You are responsible for maintaining the confidentiality of any login information associated with any account you use to access to our website. Accordingly, you are responsible for all activities that occur under your accounts.</p>
			<p>Your use of our website is subject to all applicable local, national and international laws and regulations, and you agree not to violate such laws and regulations. Any attempt by any person to deliberately damage our website is a violation of criminal and civil laws. Webfinity reserves the right to seek damages from any such person to the fullest extent permitted by law.</p>
			<p class="mb-1"><b>Copyrights/Trademarks</b></p>
			<p>All content and materials available on our website, including but not limited to text, graphics, website name, code, images and logos are the intellectual property of Infinity Fightgear, and are protected by applicable copyright and trademark law. Any inappropriate use, including but not limited to the reproduction, distribution, display or transmission of any content on our site is strictly prohibited, unless specifically authorized by the respective owners of Infinity Fightgear.</p>
			<p class="mb-1"><b>Disclaimer</b></p>
			<p>By using our website, you understand and agree that all the content, materials, information, software and products are provided on an "as is" and "as available". This means that we do not represent or warrant to you that the use of our website will meet your needs or requirements. The use of our website will be uninterrupted, timely, secure or free from errors. The information obtained by using our website will be accurate or reliable, and any defects in the operation or functionality will be repaired or corrected.</p>
			<p class="mb-1"><b>Limitation of Liability</b></p>
			<p>In conjunction with the Disclaimer as explained above, you expressly understand and agree that any claim against us shall be limited to the amount you paid, if any, for use of products will not be liable for any direct, indirect, incidental, consequential or exemplary loss or damages which may be incurred by you as a result of using our website, or as a result of any changes, data loss or corruption, cancellation, loss of access, or downtime to the full extent that applicable limitation of liability laws apply. </p>
			<p class="mb-1"><b>Jurisdiction</b></p>
			<p>This agreement and all claims relating to the relationship between the parties are governed by the laws of the Republic of the Philippines. If one or more of the provisions contained in this Agreement is held invalid, illegal or unenforceable in any respect by any court of competent jurisdiction, such holding will not impair the validity, legality, or enforceability of the remaining provisions.</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	  </div>
	</div><!-- Modal -->
</div>
</template>

<script>
	import { required, minLength, maxLength, numeric, sameAs, helpers } from 'vuelidate/lib/validators';
	import { HalfCircleSpinner } from 'epic-spinners'

	export default {
		props: ['customer'],
		data() {
			return {
				default_addr: false,
				first_name: '',
				last_name: '',
				street: '',
				province: '',
				municipality: '',
				barangay: '',
				phone_number: '',
				company: '',
				shipping_method: '',
				payment_method: '',
				provinces: [],
				municipalities: [],
				barangays: [],
				not_complete: true,
				cart_details: [],
				cart_loading: false,
				voucher_code: '',
				voucher_benefit: '',
				placing_order: false,
				applying_voucher: false
			}
		},
		validations() {
			if (!this.default_addr) {
				return {
					first_name: {
						required
					},
					last_name: {
						required
					},
					street: {
						required
					},
					province: {
						required
					},
					municipality: {
						required
					},
					barangay: {
						required
					},
					phone_number: {
						required
					},
					company: {
						required
					},
					shipping_method: {
						required
					},
					payment_method: {
						required
					},
					voucher_code: {
						required
					},
					voucher_benefit: {
						required
					}
				}
			}
			else
			{
				return {
					shipping_method: {
						required
					},
					payment_method: {
						required
					},
					voucher_code: {
						required
					},
					voucher_benefit: {
						required
					}
				}
			}
		},
		methods: {
			defaultAddress() {
				this.default_addr = !this.default_addr;
			},
			getCartDetails() {
       			this.cart_loading = true;

       			axios.get('/api/cart/products/'+this.customer.id)
       			.then(response => {
       				this.cart_loading = false;
       				this.cart_details = response.data;
       			})
       			.catch(error => {
       				this.cart_loading = false;
       				console.log(error.response);
       			})
       			
       		},
       		provinceList() {
                axios.get('/api/provinces')
                .then(response => {
                    this.provinces = response.data;
                })
                .catch(error => {
                    console.log(error.response);
                });
            },
            municipalityList() {
                if (this.province) {
                    axios.get('/api/municipalities/province/'+this.province)
                    .then((response) => {
                        console.log(response.data);

                        this.municipalities = response.data;
                    })
                    .catch((error) => {
                        console.log(error.response);
                    });
                }
                else
                {
                    this.municipalities = [];
                    this.province = '';
                }
            },
            barangayList() {
                if (this.municipality) {
                    axios.get('/api/barangays/municipal/'+this.municipality)
                    .then((response) => {
                        console.log(response.data);

                        this.barangays = response.data;
                    })
                    .catch((error) => {
                        console.log(error.response);
                    });
                }
                else
                {
                    this.barangays = [];
                    this.municipality = '';
                }
            },
            applyVoucher() {
            	this.$v.voucher_code.$touch();
            	this.$v.voucher_benefit.$touch();

            	if (!this.$v.voucher_code.$invalid && !this.$v.voucher_benefit.$invalid) {
            		axios.post('/api/voucher-code/apply', {
            			customer: this.customer.id,
            			code: this.voucher_code,
            			benefit: this.voucher_benefit
            		})
            		.then((response) => {
            			this.applying_voucher = false;
            			console.log(response);
            		})
            		.catch((error) => {
            			this.applying_voucher = false;
            			 if (error.response.status == 422) {
	                    	this.voucher_errors = error.response.data.errors;
	                    }
            		});
            	}
            },
            acceptTermsConditions(e) {
            	this.not_complete = !this.not_complete;
            },
       		placeOrder() {
       			if (!this.default_addr) {
       				this.$v.first_name.$touch();
       				this.$v.last_name.$touch();
       				this.$v.street.$touch();
       				this.$v.province.$touch();
       				this.$v.municipality.$touch();
       				this.$v.barangay.$touch();
       				this.$v.phone_number.$touch();
       				this.$v.shipping_method.$touch();
       				this.$v.payment_method.$touch();

       			}
       		},
		},
		created() {
			this.provinceList();
			this.getCartDetails();
		}
	}	
</script>