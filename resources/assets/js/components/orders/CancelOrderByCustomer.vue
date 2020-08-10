<template>
<div>
	<button class="btn btn-danger float-right" @click="cancelOrder" :disabled="isBtnClicked"><span v-if="isBtnClicked"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>Cancel order</button>
	
  </b-modal>
</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners'

	export default {
		props: ['order'],
		data() {
			return {
				isBtnClicked: false,
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			cancelOrder() {
				Swal({
				  	title: 'Cancel this order?',
				  	text: '',
				  	type: 'warning',
				  	showCancelButton: true,
				  	confirmButtonText: 'Yes',
				  	cancelButtonText: 'No'
				})
				.then((result) => {
					if (result.value) {
						this.isBtnClicked = true;
					  	axios.put('/api/order/cancel-order-by-customer/'+this.order.number)
						.then(response => {
							let res = response.data

							if (res.success) {
								Swal('Order has been cancelled.', '', 'success')
								.then((okay) => {
									if (okay) {
										window.location.reload();
									}
								})
							}
							this.isBtnClicked = false;
						})
						.catch(error => {
							console.log(error.response)
						});
					}
				});
			
		}
	}
}
</script>