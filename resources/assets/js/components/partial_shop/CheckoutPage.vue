<template>
<div>
	<div class="row">
        <div class="col-md-12">
            <h2 class="mb-3 mt-3 ifg-header">CHECKOUT</h2>
        </div>
    </div>
    <form @submit.prevent="placeOrder" method="post" action="checkout/submit" ref="checkout">
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                   <h4 class="mb-0">1. Shipping or Store pickup options</h4>
                </div>
                <div class="card-body">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="shipping_method"
                                        value="shipping" 
                                        v-model.trim="$v.shipping_method.$model"
                                        :class="{'is-invalid': $v.shipping_method.$error}"
                                        @change="setCashPayment">Shipping
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-2">
                        <div class="card-body">
                             <div class="form-check">
                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="shipping_method"
                                        value="store_pickup" 
                                        v-model.trim="$v.shipping_method.$model"
                                        :class="{'is-invalid': $v.shipping_method.$error}"
                                        @change="setCashPayment">Store Pickup
                                </label>

                            </div>
                        </div>
                    </div>
                    <div v-if="$v.shipping_method.$error">
                        <span class="error-feedback" v-if="!$v.shipping_method.required">Shipping method is required</span>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">2. Payment method</h4>
                </div>
                <div class="card-body">
                    <template v-if="shipping_method != 'store_pickup'">
                         <div class="form-check mb-2">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="payment_method"
                                    value="PayPal"
                                    v-model.trim="$v.payment_method.$model"
                                    :class="{'is-invalid': $v.payment_method.$error}">PayPal
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="payment_method"
                                     value="Bank Deposit"
                                     v-model.trim="$v.payment_method.$model"
                                    :class="{'is-invalid': $v.payment_method.$error}">Bank Deposit
                            </label>
                        </div>   
                    </template>
                    <template v-if="shipping_method === 'store_pickup'">
                            <div class="form-check mb-2">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="payment_method"
                                    value="Cash"
                                    v-model.trim="$v.payment_method.$model">Cash
                            </label>
                        </div>
                    </template>
                    <div v-if="$v.payment_method.$error">
                        <span class="error-feedback" v-if="!$v.payment_method.required">Payment method is required</span>
                    </div>
                </div>
            </div>
            <div class="card mb-4" v-if="shipping_method == 'shipping'">
                <div class="card-header">
                   <h4 class="mb-0">3. Your shipping address</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                       <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="shipToDiffAddr" name="ship_to_diff_addr" value="true" v-model="shipToDiffAddr" @change="shipToDiffAddress">
                            <label class="form-check-label" for="shipToDiffAddr">
                               Ship to different address
                            </label>
                        </div>
                    </div>
                    <template v-if="!shipToDiffAddr">
                        <div class="form-group">
                            <label for="selectAddr">Your address:</label>
                            <select class="form-control" 
                                id="selectAddr" 
                                v-model="$v.addrID.$model"
                                :class="{'is-invalid': $v.addrID.$error}" 
                                @change="selectAddress">
                                <option value="">Select address</option>
                                <option v-for="(addr,index) in customer.addresses" :key="index" :value="addr.id">{{ addr.address_name }}</option>
                            </select>
                            <div v-if="$v.addrID.$error">
                                <span class="error-feedback" v-if="!$v.addrID.required">Please select an address</span>
                            </div>
                        </div>
                        <template v-if="addrID">
                            <div class="form-group">
                                <label>Firstname:</label>
                                <input type="text" class="form-control" name="first_name" :value="first_name" readonly>
                            </div>
                            <div class="form-group">
                                <label>Lastname:</label>
                                <input type="text" class="form-control" name="last_name" :value="last_name" readonly>
                            </div>
                            <div class="form-group">
                                <label>Street:</label>
                                <input type="text" class="form-control" name="street" :value="street" readonly>
                            </div>
                            <div class="form-group">
                                <label>Barangay:</label>
                                <input type="text" class="form-control" name="barangay" :value="drp_barangay" readonly>
                            </div>
                            <div class="form-group">
                                <label>Municipality:</label>
                                <input type="text" class="form-control" name="municipality" :value="drp_municipality" readonly>
                            </div>
                            <div class="form-group">
                                <label>Province:</label>
                                <input type="text" class="form-control" name="province" :value="drp_province" readonly>
                            </div>
                            <div class="form-group">
                                <label>Zip code:</label>
                                <input type="text" class="form-control" name="zip_code" :value="zip_code" readonly>
                            </div>
                            <div class="form-group">
                                <label>Mobile no:</label>
                                <input type="text" class="form-control" name="mobile_no" :value="mobile_no" readonly>
                            </div> 
                       </template><!-- your saved address -->
                    </template>
                    <template v-else><!-- ship to different address -->
                        <div class="form-group">
                            <label for="chckfname">Firstname:</label>
                            <input type="text" class="form-control" id="chckfname"
                                v-model.trim="$v.first_name.$model"
                                :class="{'is-invalid': $v.first_name.$error}"
                                name="first_name"
                                placeholder="Enter your firstname">
                            <div v-if="$v.first_name.$error">
                                <span class="error-feedback" v-if="!$v.first_name.required">Firstname is required</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="chcklname">Lastname:</label>
                            <input type="text" class="form-control" id="chcklname"
                                v-model.trim="$v.last_name.$model"
                                :class="{'is-invalid': $v.last_name.$error}"
                                name="last_name"
                                placeholder="Enter your lastname">
                            <div v-if="$v.last_name.$error">
                                <span class="error-feedback" v-if="!$v.last_name.required">Lastname is required</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="chckstreet">Street:</label>
                            <input type="text" class="form-control" id="chckstreet"
                                v-model.trim="$v.street.$model"
                                :class="{'is-invalid': $v.street.$error}"
                                name="street"
                                placeholder="Enter your street">
                            <div v-if="$v.street.$error">
                                <span class="error-feedback" v-if="!$v.street.required">Street is required</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="chckprovince">Province:</label>
                            <select class="form-control" id="chckprovince"
                                v-model.trim="$v.province.$model"
                                :class="{'is-invalid': $v.province.$error}"
                                name="province_id"
                                @change="municipalityList">
                                <option value="">Select province</option>
                               <option v-for="(item, index) in provinceList" :key="index" :value="item.province_id">{{item.name}}</option>
                            </select>
                            <input type="hidden" name="province" :value="drp_province">
                            <div v-if="$v.province.$error">
                                <span class="error-feedback" v-if="!$v.province.required">Province is required</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="chckmunicipality">Municipality:</label>
                            <select class="form-control" id="chckmunicipality"
                                v-model.trim="$v.municipality.$model"
                                :class="{'is-invalid': $v.municipality.$error}"
                                @change="barangayList"
                                name="municipality_id">
                               <option value="" v-if="!municipalities.length">Select province first</option>
                               <option value="" v-else>Select municipality</option>
                               <option v-for="(item, index) in municipalities" :key="index" :value="item.city_id">{{item.name}}</option>
                            </select>
                            <input type="hidden" name="municipality" :value="drp_municipality">
                            <div v-if="$v.municipality.$error">
                                <span class="error-feedback" v-if="!$v.municipality.required">Municipality is required</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="chckbarangay">Barangay:</label>
                            <select class="form-control" id="chckbarangay"
                                v-model.trim="$v.barangay.$model"
                                :class="{'is-invalid': $v.barangay.$error}"
                                name="barangay_id"
                                @change="getBarangay">
                               <option value="" v-if="!barangays.length">Select municipality first</option>
                               <option value="" v-else>Select barangay</option>
                               <option v-for="(item, index) in barangays" :key="index" :value="item.id">{{item.name}}</option>
                            </select>
                            <input type="hidden" name="barangay" :value="drp_barangay">
                            <div v-if="$v.barangay.$error">
                                <span class="error-feedback" v-if="!$v.barangay.required">Barangay is required</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="chckzipcode">Zip Code:</label>
                            <input type="text" class="form-control" id="chckzipcode"
                                v-model.trim="$v.zip_code.$model"
                                :class="{'is-invalid': $v.zip_code.$error}"
                                name="zip_code"
                                placeholder="Enter your zip code">
                            <div v-if="$v.zip_code.$error">
                                <span class="error-feedback" v-if="!$v.zip_code.required">Zip code is required</span>
                                <template v-if="$v.zip_code.required">
                                    <span class="error-feedback" v-if="!$v.zip_code.digitsOnly">Zip code must be digits only</span>
                                </template>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="chckmobileno">Mobile no.:</label>
                            <input type="text" class="form-control" id="chckmobileno"
                                v-model.trim="$v.mobile_no.$model"
                                :class="{'is-invalid': $v.mobile_no.$error}"
                                name="mobile_no"
                                placeholder="Enter your mobile number">
                            <div v-if="$v.mobile_no.$error">
                                <span class="error-feedback" v-if="!$v.mobile_no.required">Mobile no. is required</span>
                                <template v-if="$v.mobile_no.required">
                                   <span class="error-feedback d-block" v-if="!$v.mobile_no.mobileNumber">Mobile number must be a valid format ex.09454545454 and must be 11 digits</span>
                                    <span class="error-feedback d-block" v-if="!$v.mobile_no.isUnique">This mobile no. is already registered</span>
                                </template>
                            </div>
                        </div>
                    </template><!-- ship to different address -->
                </div>
            </div><!-- delivery--> 
            <div class="card mb-4" v-if="shipping_method == 'store_pickup'"><!-- store pickup -->
                <div class="card-header">
                    <h4 class="mb-0">3. Who will pickup this order?</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="chckpickupfname">First name:</label>
                        <input type="text" class="form-control" id="chckpickupfname"
                            v-model.trim="$v.pickup_first_name.$model"
                            :class="{'is-invalid': $v.pickup_first_name.$error}"
                            name="pickup_firstname"
                            placeholder="Enter firstname">
                        <div v-if="$v.pickup_first_name.$error">
                            <span class="error-feedback" v-if="!$v.pickup_first_name.required">Firstname is required</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="chckpickuplname">Last name:</label>
                        <input type="text" class="form-control" id="chckpickuplname"
                            v-model.trim="$v.pickup_last_name.$model"
                            :class="{'is-invalid': $v.pickup_last_name.$error}"
                            name="pickup_lastname"
                            placeholder="Enter lastname">
                        <div v-if="$v.pickup_last_name.$error">
                            <span class="error-feedback" v-if="!$v.pickup_last_name.required">Lastname is required</span>
                        </div>
                    </div>
                </div>
            </div><!-- store pickkup -->
        </div><!-- col-md-4 -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">4. Review your orders</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr v-for="(cart_data, index) in cartData" :key="index">
                            <td>
                                <div class="media">
                                    <img class="media-object mr-2" :src="cart_data.inventory.product.product_image_url" style="width: 72px; height: 72px;">
                                    <div class="media-body">
                                        <h6>{{cart_data.product_name}}</h6>
                                        <h6>Price: {{formatMoney(cart_data.price)}}</h6>
                                        <div class="clearfix">
                                            <h6 class="float-left">Quantity: {{cart_data.quantity}}</h6>
                                            <h6 class="float-right">{{formatMoney(cart_data.total)}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-3 pb-1">
                                <div class="clearfix">
                                    <h5 class="float-left">Subtotal</h5>
                                    <h5 class="float-right">{{ subtotalOrder }}</h5>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-3 pb-1">
                                <div class="clearfix">
                                    <h5 class="float-left" v-if="!has_discount">Discount</h5>
                                    <h5 class="float-left" v-else>Discount ({{discount.discount_percent}}%)</h5>
                                    <h5 class="float-right">{{ discountOrder }}</h5>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="shipping_method == 'shipping'">
                            <td class="pt-3 pb-1">
                                <div class="clearfix">
                                    <h5 class="float-left">Shipping fee</h5>
                                    <h5 class="float-right">{{ shippingRate }}</h5>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-3 pb-1">
                                <div class="clearfix">
                                    <h5 class="float-left">TOTAL</h5>
                                    <h5 class="float-right">{{ totalOrder }}</h5>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="customer_id" :value="customer.id">
                    <input type="hidden" name="order_qty" :value="cartQty">
                   <!--  <input type="hidden" name="shipping_discount_amount" :value="discount_amount"> -->
                    <input type="hidden" name="cart_subtotal" :value="order_subtotal">
                    <input type="hidden" name="subtotal_with_discount" :value="cartSubtotal">
                    <input type="hidden" name="discount" :value="orderDiscount">
                    <input type="hidden" name="shipping_fee" :value="shipping_rate">
                    <input type="hidden" name="order_total" :value="total_order">
                    <input type="hidden" name="_token" :value="csrf">
                    <div class="form-group mb-4 mt-2">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="termsCondition" name="terms_condition" 
                            :class="{'is-invalid' : $v.terms_condition.$error}"
                            v-model.trim="$v.terms_condition.$model"
                            :disabled="notReadAndAccept">
                          <label class="form-check-label" for="termsCondition">
                            I have read and agreed with the <b><a href="javascript:void(0)" @click="openTAC">terms and conditions.</a></b>
                          </label>
                        <div v-if="$v.terms_condition.$error">
                            <span class="error-feedback" v-if="!$v.terms_condition.required">Please read and agree with our terms and conditions</span>
                        </div>
                        </div>
                    </div>

                    <button class="btn btn-block btn-success btn-lg" :disabled="isSubmitted"><i class="fa fa-refresh fa-spin" v-if="isSubmitted"></i> PLACE ORDER</button>
                    
                </div>
            </div>  
        </div><!-- col-md-4 -->
    </div>
 	</form>
 	<div>
    
    <b-modal 
        title="Terms and Conditions"
        ref="refTAC"
        size="lg"
        ok-title="I agree"
        cancel-title="I disagree"
        hide-header-close
        no-close-on-backdrop
        no-close-on-esc
        @ok="agreedTAC"
        @cancel="disagreeTAC">
        <div id="c_tac">
        </div>
      </b-modal>
    </div>
    <div>
</div>
</div>
</template>

<script>
	import { required, minLength, maxLength, numeric, sameAs, helpers } from 'vuelidate/lib/validators';
	import { HalfCircleSpinner } from 'epic-spinners'

	const mobileNumber = helpers.regex('mobileNumber', /^(09)\d{9}$/);

    const digitsOnly = helpers.regex('digitsOnly', /^[0-9]*$/);

	export default {
		props: [
			'customer',
			'cart',
			'shipping_rate',
            'discount',
            'company'
		],
		data() {
			return {
                shipToDiffAddr:false,
				first_name: this.customer.first_name,
				last_name: this.customer.last_name,
				street: '',
				province: '',
				municipality: '',
				barangay: '',
				mobile_no: '',
                zip_code: '',
				shipping_method: '',
				payment_method: '',
				terms_condition: false,
				provinces: [],
				municipalities: [],
				barangays: [],
				isSubmitted: false,
				csrf: document.head.querySelector('meta[name="csrf-token"]').content,
				not_accept_terms_conditions: true,
                order_subtotal: '',
				cartSubtotal: '',
                orderDiscount: '',
				cartData: this.cart,
				shipping_amount: 0,
				total_order: 0,
                discount_amount: 0,
                pickup_first_name: this.customer.first_name,
                pickup_last_name: this.customer.last_name,
                notReadAndAccept: true,
                readyToSubmit: false,
                showDiscountShippingFee: false,
                saveAddr: true,
                useSavedAddress: false,
                addrID: '',
                barangay_name: '',
                municipality_name: '',
                province_name: '',
                drp_province: '',
                drp_municipality: '',
                drp_barangay: '',
                has_discount: false,
			}
		},
		validations() {
			if (this.shipping_method == 'shipping') {
                if (!this.shipToDiffAddr) {
                    return {
                        addrID: {required},
                        shipping_method: { required },
                        payment_method: { required },
                        terms_condition: { sameAs: sameAs(() => true) }
                    }
                } else {
                    return {
                        first_name: { required },
                        last_name: { required },
                        street: { required },
                        province: { required },
                        municipality: { required },
                        barangay: { required },
                        zip_code: { 
                            required,
                            digitsOnly 
                        },
                        mobile_no: { 
                            required,
                            maxLength: maxLength(11),
                            mobileNumber,
                            async isUnique(value) {
                                if (value === '') return true
                                const response = await fetch(`/api/customer-check-mobile/?mobile=${value}`)
                                return Boolean(await (response.json()) ? false : true)
                            }
                        },
                        shipping_method: { required },
                        payment_method: { required },
                        terms_condition: { sameAs: sameAs(() => true) }
                    }
                }
            } else if (this.shipping_method == 'store_pickup') {
                return {
                    pickup_first_name: { required },
                    pickup_last_name: { required },
                    shipping_method: { required },
                    payment_method: { required },
                    terms_condition: { sameAs: sameAs(() => true) }
                }
            } else {
                return {
                    shipping_method: { required },
                    payment_method: { required },
                    terms_condition: { sameAs: sameAs(() => true) }
                }
            }
		},
		methods: {
            shipToDiffAddress() {
                if (this.shipToDiffAddr) {
                    this.addrID = '';
                    this.first_name = '';
                    this.last_name = '';
                    this.street = '';
                    this.province = '';
                    this.municipality = '';
                    this.barangay = '';
                    this.zip_code = '';
                    this.mobile_no = '';
                } 
            },
            getProvinces() {
                axios.get('/api/address/provinces')
                .then(response => {
                    this.provinces = response.data;
                })
                .catch(error => {
                    console.log(error.response);
                });
            },
            municipalityList(e) {
                this.municipality = '';
                this.barangay = '';
                this.barangays = [];
                this.drp_barangay = '';

                if (this.province) {
                    let prv = this.provinces.find(x => x.province_id == this.province);

                    this.drp_province = prv.name;

                    axios.get('/api/address/cities/'+this.province)
                    .then(response => {
                        this.municipalities = response.data;
                    })
                    .catch(error => {
                        console.log(error.response)
                    });
                } else {
                    this.municipalities = [];
                    this.drp_province = '';
                    this.province = '';
                }
            },
            barangayList(e) {
                this.barangay = '';

                if (this.municipality) {

                    let city = this.municipalities.find(x => x.city_id == this.municipality);

                    this.drp_municipality = city.name;

                    axios.get('/api/address/barangays/'+this.municipality)
                    .then(response => {
                        this.barangays = response.data;
                    })
                    .catch(error => {
                        console.log(error.response)
                    });
                } else {
                    this.barangays = [];
                    this.drp_municipality = '';
                    this.drp_barangay = '';
                }
            },
            getBarangay(e) {
                if (this.barangay) {
                    let brgy = this.barangays.find(x => x.id == this.barangay);

                    this.drp_barangay = brgy.name;   

                } else {
                    this.barangay = '';
                    this.drp_barangay = '';
                }
            },
            selectAddress(e) {
                const addr = this.customer.addresses.find(x => x.id === this.addrID);
                this.first_name = addr.firstname;
                this.last_name = addr.lastname;
                this.street = addr.street;
                this.barangay_name = addr.barangay;
                this.municipality_name = addr.municipality;
                this.province_name = addr.province;
                this.zip_code = addr.zip_code;
                this.drp_barangay = addr.barangay;
                this.drp_municipality = addr.municipality;
                this.drp_province = addr.province;
                this.mobile_no = addr.mobile_no;
            },
			placeOrder(e) {
				this.$v.$touch();

    			if (!this.$v.$invalid) {

                    this.readyToSubmit = true;
                    this.isSubmitted = true
                    this.$refs.checkout.submit();
                    
    			}
			},
            openTAC() {
                this.$refs.refTAC.show();
            },
            agreedTAC(e) {
                this.notReadAndAccept = false;
                this.terms_condition = true;
                this.$refs.refTAC.hide();
            },
            disagreeTAC(e) {
                this.notReadAndAccept = true;
                this.terms_condition = false;
                this.$refs.refTAC.hide();
            },
            setCashPayment(e) {
               if (this.shipping_method == 'store_pickup') {
                    this.payment_method = 'Cash'
               } else {
                    this.payment_method = '';
                    this.$v.payment_method.$reset();
               }

            },
            formatMoney(price) {
                return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(price);
            }
		},
		computed: {
            provinceList() {
                let items = this.provinces.sort((a,b) => (a.name > b.name) ? 1 : -1);
                return items;
            },
			shippingRate() {
				let amount = parseFloat(this.shipping_rate);
				
				return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(amount);
			},
            subtotalOrder() {

                let amount = this.cart.reduce(
                    (a, b) => a + parseFloat(b.total),
                    0
                );

                this.order_subtotal = amount;

                this.cartSubtotal = amount;
                               
                return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(amount);

            },
            discountOrder() {
                let amount = 0;

                if (this.discount.is_disabled == 0)
                {
                    if (this.cartQty >= this.discount.order_quantity)
                    {
                        amount = this.cartSubtotal * (this.discount.discount_percent / 100);

                        this.orderDiscount = parseFloat(amount);

                        this.cartSubtotal -= parseFloat(this.orderDiscount);

                        this.has_discount = true;

                        return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(amount);
                    }
                }

                this.has_discount = false;

                return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(amount);
            },
			totalOrder() {

                let amount = this.cartSubtotal;

                if (this.shipping_method === 'shipping')
                {
                    amount += parseFloat(this.shipping_rate)

                    this.total_order = amount;

                    return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(amount);
                }

                this.total_order = amount;
			     
                return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(amount);
			},			
            cartQty() {
				return this.cartData.reduce((a, b) => a + b.quantity, 0);
			}
		},
        mounted() {
            window.addEventListener('beforeunload', (event) => {
                if (!this.readyToSubmit) {
                    event.returnValue = `Are you sure you want to leave?`;
                    this.readyToSubmit = false;
                }
            });

            this.getProvinces();

            const TAC = document.getElementById('c_tac');
            TAC.innerHTML = this.company.terms_and_conditions;
        }
		
	}	
</script>