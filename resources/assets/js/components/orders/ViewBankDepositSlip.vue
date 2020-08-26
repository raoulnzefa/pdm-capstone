<template>
<div>
	<button class="btn btn-primary float-right" @click="viewBankDeposit">View bank deposit slip</button>
	<b-modal id="depositSlipModal"
   	ref="depositSlipModal"
   	title="Bank deposit slip"
   	no-fade
   	centered
   	no-close-on-backdrop
   	no-close-on-esc
   	hide-header-close
   	size="lg"
   	cancel-title="Close"
   	@ok="markAsPaid"
   	:ok-disabled="isMarkAsPaid"
   	:cancel-disabled="isMarkAsPaid">
      <div>
      	<img :src="bank_deposit_slip" class="img-fluid">
      </div>
      <div slot="modal-ok">
      	<span v-if="isMarkAsPaid"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>Mark as paid
      </div>
  </b-modal>
</div>
</template>
<script>
	export default {
		props: ['order','admin'],
		data() {
			return {
				isMarkAsPaid: false,
				bank_deposit_slip: '',
				hasBankDepositSlip: this.order.bank_deposit_slip,
			}
		},
		methods: {
			viewBankDeposit() {
				
				if (this.hasBankDepositSlip) {
					this.bank_deposit_slip = `/storage/deposit_slip/${this.order.bank_deposit_slip.image}`;
					this.$refs.depositSlipModal.show();
				} else {
					Swal('No uploaded bank deposit slip', '', 'error')
				}
			},
			markAsPaid(e) {
				e.preventDefault();
				this.isMarkAsPaid = true;
				axios.put('/api/order/mark-as-paid/'+this.order.number, {
					payment_status: 'Paid',
					admin_id: this.admin.id
				})
				.then(res => {
					if (res.data.success) {
						this.$refs.depositSlipModal.hide();
						Swal('Order status has been updated.', '', 'success')
						.then((okay) => {
							if (okay) {
								this.$bus.$emit('refreshOrderDetails', true);
							}
						})
					}
				})
				.catch(err => {
					console.log(err.response);
				});

			}
		}
	}
</script>