<template>
<div>
	<button class="btn btn-primary float-left" v-print="'#printInvoice'"><i class="fa fa-print"></i>&nbsp;Print</button>
	<div class="d-none d-print-block">
	  	<invoice-component ref="invoiceDiv" id="printInvoice" :invoice="invoice_data" :invoiceProducts="invoice_products"></invoice-component>
	</div>
</div>	
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';
	
	export default {
		props:['invoice'],
		data() {
			return {
				loading: false,
				invoice_data: this.invoice,
				invoice_products: this.invoice.invoice_products,
				customer_data: [],
				order: []
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getInvoice() {
				this.loading = true;
				axios.get('/api/invoice/'+this.invoice)
				.then((response) => {
					this.loading = false;
					this.invoice_data = response.data;
					this.invoice_products = this.invoice_data.invoice_products;
					this.customer_data = this.invoice_data.order.customer;
					this.order = this.invoice_data.order;
				})
				.catch((error) => {
					this.loading = false;
					console.log(error.response);
				});
			}
		}
	}
</script>