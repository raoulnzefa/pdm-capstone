<template>
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<h2 class="mt-4 mb-4">Voucher</h2>
			<button class="btn btn-primary mb-4" @click="addVoucherCode"><i class="fa fa-plus"></i> Add voucher</button>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>ID</th>
						<th>Code</th>
						<th>Type</th>
						<th>Start</th>
						<th>End</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>

			<!-- Modal Component -->
		    <b-modal id="voucherModal"
             ref="refsVoucherModal"
             :title="modal_title"
             no-close-on-backdrop
             no-close-on-esc
             hide-header-close
             :ok-title="ok_title"
             @shown="focusOnVoucherCode"
             @ok="saveVoucherCode"
             @cancel="cancelVoucherCode"
             :ok-disabled="isSubmitted"
             :cancel-disabled="isSubmitted"
             >
            <div class="alert alert-danger" v-if="server_errors.length != 0">
					<ul class="mb-0">
						<li v-for="(err,index) in server_errors" :key="index">{{ err[0] }}</li>
					</ul>
				</div>
		      <div class="form-group">
		      	<label>Code:</label>
		      	<input type="text" class="form-control" tabindex="1" placeholder="Enter voucher code"
		      		v-model.trim="$v.voucher.code.$model"
		      		ref="voucherCodeFocus"
		      		:class="{'is-invalid': $v.voucher.code.$error}">
		      	<div v-if="$v.voucher.code.$error">
                	<span class="error-feedback" v-if="!$v.voucher.code.required">Please enter a voucher code</span>
                </div>
		      </div>
		      <div class="form-group">
		      	<label>Type:</label>
		      	<select class="form-control" tabindex="2" 
		      		v-model.trim="$v.voucher.type.$model"
		      		:class="{'is-invalid': $v.voucher.type.$error}">
		      		<option value="" disabled>Select voucher type</option>
		      		<option v-for="(type,index) in voucherTypes" :key="index" :value="type">{{ type }}</option>
		      	</select>
		      	<div v-if="$v.voucher.type.$error">
                	<span class="error-feedback" v-if="!$v.voucher.type.required">Please select a voucher type</span>
                </div>
		      </div>
		      
		      <div class="form-group">
		     		<label>Discount:</label>
		     		<input type="text" class="form-control" placeholder="Enter voucher discount"
		     			v-model.trim="$v.voucher.discount.$model"
		     			:class="{'is-invalid': $v.voucher.discount.$error}">
		     			<div v-if="$v.voucher.discount.$error">
		     				<span class="error-feedback" v-if="!$v.voucher.discount.required">Please enter a voucher discount</span>
	                	<template v-if="$v.voucher.discount.required">
	                		<span class="error-feedback" v-if="!$v.voucher.discount.numbersOnly">Please enter a valid value</span>
		                	<template v-if="$v.voucher.discount.numbersOnly">
		                		<span class="error-feedback" v-if="!$v.voucher.discount.between">Please enter a value between {{$v.voucher.discount.$params.between.min}} and {{$v.voucher.discount.$params.between.max}}</span>
		                	</template>
	                	</template>
	               </div>
		     	</div>
		     	<div class="form-group">
		     		<label>Start date:</label>
		     		<input type="text" name="" id="dateStart" class="form-control">
		     	</div>
		     		<div class="form-group">
		     		<label>End date:</label>
		     		<input type="text" name="" id="dateEnd" class="form-control">
		     	</div>
		    </b-modal>
		</div>
	</div>
</template>
<script>
	import { required, minLength, maxLength, helpers, between, requiredIf } from 'vuelidate/lib/validators';
   import { HalfCircleSpinner } from 'epic-spinners';

   const numbersOnly = helpers.regex('numbersOnly', /^([1-9])[0-9]*$/);
   const now= new Date();

	export default {
		props: ['admin'],
		data() {
			return {
				sampleDate: '',
				loading: false,
				voucher: {
					code: '',
					type: '',
					discount: '',
					dateStart: '',
					dateEnd: ''
				},
				voucherTypes: ['Discount', 'Free shipping'],
				ok_title: '',
				modal_title: '',
				isEdit: false,
				isSubmitted: false,
				server_errors: [],
				voucher_id: '',
			}
		},
		components: {
			HalfCircleSpinner
		},
		validations() {
			return {
				voucher: {
					code: {
						required
					},
					type: {
						required
					},
					discount: {
						numbersOnly,
						between: between(1,100),
						required: requiredIf(function() {
							return (this.voucher.type === 'Discount') ? true : false
						})
					}
				}
			}
		},
		methods: {
			getVoucherCodes() {

			},
			addVoucherCode() {
				const inputDate = '10/01/2020';
				this.modal_title = 'Add voucher';
				this.ok_title = 'Create';
				this.$refs.refsVoucherModal.show();
				
			},
			changeMinDate(e) {
				alert('ok');
			},
			focusOnVoucherCode() {
				this.$refs.voucherCodeFocus.focus();
			},
			resetThem() {
				this.isEdit = false;
				this.ok_title = 'Create';
				this.modal_title = "Add voucher";
				this.voucher = {
					code: '',
					type: '',
					discount: '',
					dateStart: '',
					dateEnd: '',
				};
				this.$v.voucher.$reset();
				this.server_errors = [];
				this.voucher_id = '';
			},
			cancelVoucherCode() {
				this.resetThem();
			},
			editVoucher(voucher) {

			},
			saveVoucherCode(e) {
				e.preventDefault();
				this.$v.voucher.$touch();

				if (!this.$v.voucher.$invalid) {
					this.isSubmitted = true;

					if (!this.isEdit) {
						axios.post('/api/voucher/create', {
							admin_id: this.admin.id,
							voucher_code: this.voucher.code,
							voucher_type: this.voucher.type,
							voucher_discount: this.voucher.discount,
							voucher_start: this.voucher.dateStart,
							voucher_end: this.voucher.dateEnd,
						})
						.then(response => {
							this.isSubmitted = false;

							if (response.data.success) {
								Swal('Voucher has been created', '', 'success')
								.then((okay) => {
									if (okay) {
										this.$refs.refsVoucherModal.hide();
										this.$v.voucher.$reset();
										this.resetThem();
										this.getVoucherCodes();
									}
								})
							}
						})
						.catch(error => {
							this.isSubmitted = false;
							if(error.response.status == 422) {
								this.server_errors = error.response.data.errors;
							}
						});
					}
				}
			}
		},
		mounted() {
			$('#dateStart').datetimepicker({
				format: 'MM/DD/YYYY',
				viewMode: 'days',
				minDate: new Date(),
				maxDate: new Date(new Date().getFullYear(), 12, 0),
			});

			$('#dateEnd').datetimepicker({
				format: 'MM/DD/YYYY',
				viewMode: 'days',
				minDate: new Date(),
				maxDate: new Date(new Date().getFullYear(), 12, 0),
			});

			$('#dateStart').on("dp.change", function(e) {
				$('#dateEnd').data('DateTimePicker').minDate(e.date);
			})
		}
	}
</script>