<template>
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<h2 class="mt-4 mb-4">Voucher</h2>
			<button class="btn btn-primary mb-4" @click="addVoucherCode"><i class="fa fa-plus"></i> Add voucher</button>
			<table class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th>ID</th>
						<th>Code</th>
						<th>Description</th>
						<th>Discount %</th>
						<th>Start</th>
						<th>End</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<template v-if="loading">
						<tr>
							<td colspan="7">
								<center>
									<half-circle-spinner
					                :animation-duration="1000"
					                :size="30"
					                color="#ff1d5e"
					              />
								</center>
							</td>
						</tr>
					</template>
					<template v-else>
						<template v-if="!vouchers.length">
							<tr>
								<td colspan="7" class="text-center">No Vouchers.</td>
							</tr>
						</template>
						<template v-else>
							<tr v-for="(voucher, index) in vouchers" :key="index">
								<td>{{ voucher.id }}</td>
								<td>{{ voucher.voucher_code }}</td>
								<td>{{ voucher.voucher_description }}</td>
								<td>{{ voucher.voucher_discount_percent }}</td>
								<td>{{ voucher.voucher_start }}</td>
								<td>{{ voucher.voucher_end }}</td>
								<td>
									<button class="btn btn-primary btn-sm" @click="editVoucher(voucher)">Edit</button>
								</td>
							</tr>
						</template>
					</template>
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
             @hidden="resetThem"
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
		      	<label>Description:</label>
		      	<textarea class="form-control" placeholder="Enter voucher description" tabindex="2"
		      		v-model.trim="$v.voucher.description.$model"
		      		:class="{'is-invalid': $v.voucher.description.$error}" rows="3"></textarea>
		      	<div v-if="$v.voucher.code.$error">
                	<span class="error-feedback" v-if="!$v.voucher.description.required">Please enter voucher description</span>
                </div>
		      </div>
		      
		      <div class="form-group">
		     		<label>Discount:</label>
		     		<input type="text" tabindex="3" class="form-control" placeholder="Enter voucher discount"
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
		     		<date-picker 
		     			v-model="$v.voucher.dateStart.$model" 
		     			ref="refDateStart" 
		     			:config="options.start"
		     			placeholder="Select start date"
		     			:class="{'is-invalid': $v.voucher.dateStart.$error}"
		     			@dp-change="startDateChange"></date-picker>
		     		<div v-if="$v.voucher.dateStart.$error">
                	<span class="error-feedback" v-if="!$v.voucher.dateStart.required">Please select a date</span>
                </div>
		     	</div>
		     	<div class="form-group">
		     		<label>End date:</label>
		     		<date-picker 
		     			v-model="$v.voucher.dateEnd.$model" 
		     			ref="refDateEnd" 
		     			:config="options.end"
		     			placeholder="Select end date"
		     			:class="{'is-invalid': $v.voucher.dateEnd.$error}"
		     			@dp-change="endDateChange"></date-picker>
		     		<div v-if="$v.voucher.dateEnd.$error">
                	<span class="error-feedback" v-if="!$v.voucher.dateEnd.required">Please select a date</span>
                </div>
		     	</div>
		    </b-modal>
		</div>
	</div>
</template>
<script>
	// maxDate: new Date(new Date().getFullYear(), 12, 0), get end date of current year
	import { required, minLength, maxLength, helpers, between, requiredIf } from 'vuelidate/lib/validators';
   import { HalfCircleSpinner } from 'epic-spinners';

   const numbersOnly = helpers.regex('numbersOnly', /^([1-9])[0-9]*$/);
   const now= new Date();

	export default {
		props: ['admin'],
		data() {
			return {
				vouchers: [],
				sampleDate: '',
				loading: false,
				voucher: {
					code: '',
					description: '',
					discount: '',
					dateStart: null,
					dateEnd: null,
				},
				voucherTypes: ['Discount', 'Free shipping'],
				ok_title: '',
				modal_title: '',
				isEdit: false,
				isSubmitted: false,
				server_errors: [],
				voucher_id: '',
				options: {
					start: {
						format: 'MM/DD/YYYY',
						useCurrent: false,
						minDate: new Date(),
						maxDate: false,
					},
					end: {
						format: 'MM/DD/YYYY',
						useCurrent: false,
						minDate: new Date(),
					}
				}
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
					description: {
						required
					},
					discount: {
						numbersOnly,
						between: between(1,100),
						required,
					},
					dateStart: { required },
					dateEnd: { required }
				}
			}
		},
		methods: {
			getVouchers() {
				this.loading = true;
				axios.get('/api/vouchers')
				.then(response => {
					this.loading = false
					this.vouchers = response.data;
				})
				.catch(error => {
					this.loading = false;
					console.log(error.response);
				});
			},
			addVoucherCode() {
				const inputDate = '10/01/2020';
				this.modal_title = 'Add voucher';
				this.ok_title = 'Create';
				this.$refs.refsVoucherModal.show();
				
			},
			startDateChange(e) {
				//console.log('startDateChange', e.date);
				this.$set(this.options.end, 'minDate',e.date || null);
			},
			endDateChange(e) {
				//console.log('startDateChange', e.date);
				this.$set(this.options.start, 'maxDate',e.date || null);
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
					description: '',
					discount: '',
					dateStart: null,
					dateEnd: null,
				};
				this.$v.voucher.$reset();
				this.server_errors = [];
				this.voucher_id = '';
			},
			cancelVoucherCode() {
				this.resetThem();
			},
			editVoucher(voucher) {
				this.isEdit = true;
				this.modal_title = 'Edit voucher';
				this.ok_title = 'Update';
				this.voucher_id = voucher.id;
				this.voucher.code = voucher.voucher_code;
				this.voucher.description = voucher.voucher_description;
				this.voucher.discount = voucher.voucher_discount_percent;
				this.voucher.dateStart = voucher.voucher_start;
				this.voucher.dateEnd = voucher.voucher_end;
				this.$refs.refsVoucherModal.show();
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
							voucher_description: this.voucher.description,
							voucher_discount_percent: this.voucher.discount,
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
										this.getVouchers();
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
					} else {
						axios.put('/api/voucher/update/'+this.voucher_id, {
							admin_id: this.admin.id,
							voucher_code: this.voucher.code,
							voucher_description: this.voucher.description,
							voucher_discount_percent: this.voucher.discount,
							voucher_start: this.voucher.dateStart,
							voucher_end: this.voucher.dateEnd,
						})
						.then(response => {
							this.isSubmitted = false;

							if (response.data.success) {
								Swal('Voucher has been updated', '', 'success')
								.then((okay) => {
									if (okay) {
										this.$refs.refsVoucherModal.hide();
										this.$v.voucher.$reset();
										this.resetThem();
										this.getVouchers();
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
			},
			formatVoucherDate($value) {
				let d = new Date($value);

				return d.getMonth();
			}
		},
		mounted() {
			this.getVouchers();
		}
	}
</script>