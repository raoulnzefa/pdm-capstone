<template>
	<div class="row">
		<div class="col-md-12">
			<div class="clearfix mb-4 mt-4">
				<h2 class="float-left">Sales</h2>
				<button class="btn btn-primary float-right" @click="showCustomerDateForm">Generate with Date Range</button>
			</div>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Category</th>
						<th>Product</th>
						<th>Price</th>
						<th>Quantity</th>
						<th>Total Price</th>
					</tr>
				</thead>
				<tbody>
					<template v-if="loading">
						<tr>
							<td colspan="5">
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
						<template v-if="sales.length">
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</template>
						<template v-else>
							<tr>
								<td colspan="5" align="center">No sales for the current month.</td>
							</tr>
						</template>
					</template>
				</tbody>
			</table>
			<!-- Modal Component -->
        	<b-modal id="dateRangeModal"
                 ref="dateRangeModal"
                 title="Generate Sales with Date Range"
                 no-close-on-backdrop
                 no-close-on-esc
                 hide-header-close
                 ok-title="Submit"
                 @ok="submitDateRange">
            <form @submit.stop.prevent="dateRangeForm" ref="salesReportForm">
					<div class="form-group">
						<label class="mr-2" for="">From:</label>
						<date-picker 
							v-model="$v.from_date.$model" 
							:config="options.from"
							:class="{'is-invalid': $v.from_date.$error}"
							placeholder="Select a date"
					     	@dp-change="fromDateChange"></date-picker>
					     	<div v-if="$v.from_date.$error">
				          	<span class="error-feedback" v-if="!$v.from_date.required">Please select a date</span>
				         </div>
					</div>
					<div class="form-group">
						<label class="mr-2" for="">To:</label>
						<date-picker 
							v-model="$v.to_date.$model" 
							:config="options.to"
							:class="{'is-invalid': $v.to_date.$error}"
							placeholder="Select a date"
					     	@dp-change="toDateChange"></date-picker>
					     	<div v-if="$v.to_date.$error">
				          	<span class="error-feedback" v-if="!$v.to_date.required">Please select a date</span>
				         </div>
					</div>
				</form>
            
          </b-modal>
		</div>
	</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';
	import { required } from 'vuelidate/lib/validators';

	export default {
		data() {
			return {
				submitted: false,
				sales: [],
				loading: false,
				from_date: null,
				to_date: null,
				csrf: document.head.querySelector('meta[name="csrf-token"]').content,
				options: {
					from: {
						format: 'MM/DD/YYYY',
						useCurrent: false,
						minDate: new Date(2019, 12, 1),
						maxDate: false,
					},
					to: {
						format: 'MM/DD/YYYY',
						useCurrent: false,
						minDate: new Date(2019, 12, 1),
					}
				},
			}
		},
		components: {
			HalfCircleSpinner
		},
		validations() {
			return {
				from_date: { required },
				to_date: { required } 
			}
		},
		methods: {
			getSales() {
				this.loading = true;
				axios.get('/api/sales')
				.then((reponse) => {
					this.loading = false;
					this.sales = reponse.data;
				})
				.catch((error) => {
					this.loading = false;
					console.log(error.reponse);
				});
			},
			dateRangeForm() {
				this.$v.$touch();

				if (!this.$v.$invalid) {
					this.submitted = true;

				}
			},
			submitDataRange(e) {
				e.preventDefault();
			},
			fromDateChange(e) {
				this.$set(this.options.to, 'minDate',e.date || null);
			},
			toDateChange(e) {
				this.$set(this.options.from, 'maxDate',e.date || null);
			},
			showCustomerDateForm() {
				this.$refs.dateRangeModal.show();
			}
		},
		mounted() {
			this.getSales();
		}
	}
</script>