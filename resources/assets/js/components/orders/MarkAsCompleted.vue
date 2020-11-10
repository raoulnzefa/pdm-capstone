<template>
<div>
	<button class="btn btn-success float-right mr-1" @click="markAsCompleted" :disabled="isBtnClicked"><span v-if="isBtnClicked"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>Mark as completed</button>
	
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
			markAsCompleted() {
				this.isBtnClicked = true;
				axios.put('/api/order/mark-as-completed/'+this.order.number, {
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