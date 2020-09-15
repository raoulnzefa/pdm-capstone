<template>
<div>
	<button class="btn btn-primary float-right" @click="markAsForShipping" :disabled="isBtnClicked"><span v-if="isBtnClicked"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>Mark as For shipping</button>
	
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
			markAsForShipping() {
				this.isBtnClicked = true;
				axios.put('/api/order/mark-as-for-shipping/'+this.order.number, {
			  		admin_id: this.admin.id,
			  	})
				.then(response => {
					let res = response.data

					if (res.success) {
						Swal('Order status has been updated.', '', 'success')
						.then((okay) => {
							if (okay) {
								this.$bus.$emit('refreshOrderDetails', true);
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