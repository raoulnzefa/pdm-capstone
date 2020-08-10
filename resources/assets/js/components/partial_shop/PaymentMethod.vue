<template>
<div>
<form method="POST" action="/payment" @submit.prevent="pay" ref="payment">
<div class="row">
	<div class="col-md-6">
		<img src="/images/credit-cards-stripe.png" class="img-fluid">
	</div>	
	<div class="col-md-6">
		<div class="card" style="background: #dee2e6;">
			<div class="card-body">
				<h5 class="mb-4">Your payment type is: <b class="float-right">{{ order.payment_method }}</b></h5>
				<h4 class="mb-0">Amount Due: <b class="float-right">P{{ invoice.amount_due }}</b></h4>
			</div>
		</div>

		<div class="form-group">
		    <label for="name_on_card">Name on card:</label>
		    <input v-validate="'required'" type="text" class="form-control" id="name_on_card" name="Name_on_card" placeholder="Enter Name on card" v-model="name_on_card">
		    <span class="text-danger">{{ errors.first('Name_on_card') }}</span>
		</div>
	  	<div class="form-group">
		    <label for="">Credit Card:</label>
			<!-- <creditcard></creditcard> -->
			<card class='stripe-card'
			      :class='{ complete }'
			      stripe='pk_test_U63HFS7NpRL1LcLoWmSRCCvR'
			      :options='stripeOptions'
			      @change='change($event)'
			    /></card>
			<div id="card-errors" role="alert" v-text="errorMessage" class="text-danger"></div>
			<input type="hidden" name="_token" :value="csrf">
			<input type="hidden" name="customer_email" v-model="cust_email">
			<input type="hidden" name="order_id" v-model="order_id">
		</div>
	
	</div>	
</div>
<div class="row mt-3">
	<div class="col-md-12">
		<button type="button" class="btn btn-primary float-right" :disabled='!complete'@click="pay">Pay Order</button>
	</div>
</div>
</form>	
</div>
</template>
<script>
	import { Card, createToken } from 'vue-stripe-elements-plus'

	export default {
		props: ['invoice', 'order', 'customer'],
		data() {
			return {
				cust_email: this.customer.email,
				order_id: this.order.id,
				csrf: document.head.querySelector('meta[name="csrf-token"]').content,
				name_on_card: '',
				complete: false,
		      	errorMessage: '',
			    stripeOptions: {
			        // see https://stripe.com/docs/stripe.js#element-options for details
			        hidePostalCode: true
			    }
			}
		},
		components: { Card },
		methods: {
			change(event) {
		    	if (event.error) {
				    this.errorMessage = event.error.message;

			  	} else {
			    	this.errorMessage = '';
			    	this.complete = event.complete
			  	}
		    },
		    pay() {
		    	this.$validator.validate().
		    	then(result => {
		    		if (result)
		    		{
		    			var options = {
					    	name: this.name_on_card
					    }
					    createToken(options).then(result => {
					    	
							var hiddenInput = document.createElement('input')
							hiddenInput.setAttribute('type', 'hidden')
							hiddenInput.setAttribute('name', 'stripeToken')
							hiddenInput.setAttribute('value', result.token.id)
							
							this.$refs.payment.appendChild(hiddenInput)

							this.$refs.payment.submit()
					    })
		    		}
		    		this.complete = false
		    	})
		    }
		}
	}
</script>
<style>
	.stripe-card {
		border: 1px solid #ced4da;
		border-radius: 0.25rem;
		padding: 0.375rem 0.75rem;
	},
	.stripe-card.complete {
	  border-color: green;
	}
</style>