<template>
<div>
	<button class="btn btn-primary float-right" @click="deliverOrder">Ship order</button>
	<b-modal ref="deliverOrderModal"
    		   title="Sending Email"
    		   no-close-on-backdrop
    		   no-close-on-esc
    		   hide-header-close
    		   hide-footer
    		   hide-header
    		   no-fade
    		   centered
    		   size="sm">
	   <center>
		    <half-circle-spinner
	             :animation-duration="1000"
	             :size="50"
	             color="#ff1d5e"
	             class=""
	           />
	           <span class="mt-2 d-block">&nbsp;Please wait...</span>
	   </center>
  </b-modal>
</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners'

	export default {
		props: ['order','admin'],
		data() {
			return {
				isDeliverConfirmed: false,
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			deliverOrder() {
				Swal({
				  	title: 'Deliver this order?',
				  	text: '',
				  	type: 'warning',
				  	showCancelButton: true,
				  	confirmButtonText: 'Yes',
				  	cancelButtonText: 'No'
				})
				.then((result) => {
					if (result.value) {
						this.$refs.deliverOrderModal.show();

					  	axios.put('/api/order/deliver-order/'+this.order.number, {
					  		admin_id: this.admin.id,
					  	})
						.then(response => {
							let res = response.data

							if (res.success) {
								this.$refs.deliverOrderModal.hide();

								Swal('Order status has been updated.', '', 'success')
								.then((okay) => {
									if (okay) {
										this.$bus.$emit('refreshOrderDetails', true);
									}
								})
							}

							this.$refs.deliverOrderModal.hide();

						})
						.catch(error => {
							console.log(error.response)
						})
					}
				});
			}
		}
	}
</script>