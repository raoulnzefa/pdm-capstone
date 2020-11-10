<template>
<div>
	<button class="btn btn-success mr-1 float-right" @click="receiveOrder" :disabled="isBtnClicked"><span v-if="isBtnClicked"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>Receive order</button>
	
  </b-modal>
</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners'

	export default {
		props: ['order','admin'],
		data() {
			return {
				isBtnClicked: false,
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			receiveOrder() {
				this.isBtnClicked = true;
				axios.put('/api/order/receive-order/'+this.order.number)
				.then(response => {
					let res = response.data

					if (res.success) {
						Swal('Order status has been updated.', '', 'success')
						.then((okay) => {
							if (okay) {
								window.location.reload();
							}
						})
					}
					
				})
				.catch(error => {
					console.log(error.response)
				});
			
		}
	}
}
</script>