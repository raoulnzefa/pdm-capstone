<template>
<div>
	<button class="btn btn-success float-right" @click="pickedupOrder" :disabled="isBtnClicked">Mark as completed</button>
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
			pickedupOrder() {
				Swal({
				  	title: 'Mark this order as completed?',
				  	text: '',
				  	type: 'warning',
				  	showCancelButton: true,
				  	confirmButtonText: 'Yes',
				  	cancelButtonText: 'No'
				})
				.then((result) => {
					if (result.value) {
						this.isBtnClicked = true;
					  	axios.put('/api/order/picked-up/'+this.order.number, {
					  		admin_id: this.admin.id,
					  	})
						.then(response => {
							let res = response.data

							if (res.success) {
								this.isBtnClicked = false;
								Swal('Order status has been updated.', '', 'success')
								.then((okay) => {
									if (okay) {
										this.$bus.$emit('refreshOrderDetails', true);
									}
								})
							}


						})
						.catch(error => {
							this.isBtnClicked = false;
							console.log(error.response)
						})
					}
				});
			}
		}
	}
</script>