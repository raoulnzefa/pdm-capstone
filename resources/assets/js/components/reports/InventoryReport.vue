<template>
<div class="row justify-content-center">
	<div class="col-md-4">
		<form method="post" @submit.prevent="submitInventoryReport" ref="inventoryReportFrom" action="/reports/display-inventory-report">
			<input type="hidden" name="_token" :value="csrf">
			<input type="hidden" name="admin_id" :value="admin.id">
			<div class="form-group">
				<label>From:</label>
				<date-picker 
		     			v-model="$v.fromDate.$model" 
		     			:config="options.from"
		     			name="from_date"
		     			placeholder="Select date"
		     			:class="{'is-invalid': $v.fromDate.$error}"
		     			@dp-change="fromDateChange"></date-picker>
	     		<div v-if="$v.fromDate.$error">
             	<span class="error-feedback" v-if="!$v.fromDate.required">Please select a date</span>
            </div>
			</div>
			<div class="form-group">
				<label>To:</label>
				<date-picker 
		     			v-model="$v.toDate.$model" 
		     			:config="options.to"
		     			name="to_date"
		     			placeholder="Select date"
		     			:class="{'is-invalid': $v.toDate.$error}"
		     			@dp-change="toDateChange"></date-picker>
		     	<div v-if="$v.toDate.$error">
                <span class="error-feedback" v-if="!$v.toDate.required">Please select a date</span>
            </div>
			</div>
			<div class="form-group">
				<label>Type:</label>
				<select class="form-control" name="type" v-model="$v.type.$model">
					<option value="all_stocks">All stocks</option>
					<option value="critical_level">Critical levels</option>
					<option value="out_of_stocks">Out of stocks</option>
				</select>
			</div>
			<button type="submit" class="btn btn-primary">Generate</button>
		</form>
	</div>
</div><!-- row -->
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';
	import { required, minLength, maxLength, helpers } from 'vuelidate/lib/validators';
	
	export default {
		props:['admin'],
		data() {
			return {
				loading: false,
				csrf: document.head.querySelector('meta[name="csrf-token"]').content,
				fromDate: null,
				toDate: null,
				type: 'all_stocks',
				options: {
					from: {
						format: 'MM/DD/YYYY',
						useCurrent: false,
					},
					to: {
						format: 'MM/DD/YYYY',
						useCurrent: false,
					}
				},
				submit: false,
			}
		},
		components: {
			HalfCircleSpinner
		},
		validations() {
			return {
				fromDate: { required },
				toDate: { required },
				type: { required }
			}
		},
		methods: {
			fromDateChange(e) {
				this.$set(this.options.to, 'minDate',e.date || null);
			},
			toDateChange(e) {
				this.$set(this.options.from, 'minDate',e.date || null);
			},
			submitInventoryReport() {
				this.$v.$touch();

				if (!this.$v.$invalid) {
					this.submit = true;
					this.$refs.inventoryReportFrom.submit();
				}
			}
		},
	}
</script>