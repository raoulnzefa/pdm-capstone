<template>
<div>
	<form @submit.prevent="submitSalesReport" target="_blank" method="POST" ref="salesReportForm"  action="/admin/report/sales">
		<input type="hidden" name="_token" :value="csrf">
		<input type="hidden" name="admin_id" :value="admin.id">
		<div class="form-group">
			<label class="mr-2" for="">From:</label>
			<date-picker 
				v-model="$v.from_date.$model" 
				name="from_date"
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
				name="to_date"
				:class="{'is-invalid': $v.to_date.$error}"
				placeholder="Select a date"
		     	@dp-change="toDateChange"></date-picker>
		     	<div v-if="$v.to_date.$error">
             	<span class="error-feedback" v-if="!$v.to_date.required">Please select a date</span>
            </div>
		</div>
		<button type="submit" class="btn btn-primary">Generate Sales</button>
		<button class="btn btn-danger" @click="clearSalesForm">Clear</button>
	</form>
</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';
	import { required } from 'vuelidate/lib/validators';

	export default {
		props: ['admin'],
		data() {
			return {
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
		validations() {
			return {
				from_date: { required },
				to_date: { required } 
			}
		},
		methods: {
			submitSalesReport() {
				this.$v.$touch();

				if (!this.$v.$invalid) {
					this.$refs.salesReportForm.submit();
				}
			},
			fromDateChange(e) {
				this.$set(this.options.to, 'minDate',e.date || null);
			},
			toDateChange(e) {
				this.$set(this.options.from, 'maxDate',e.date || null);
			},
			clearSalesForm() {
				this.to_date = null;
				this.from_date = null;
				this.$nextTick(() => { this.$v.$reset() });
			}
		}
	}
</script>