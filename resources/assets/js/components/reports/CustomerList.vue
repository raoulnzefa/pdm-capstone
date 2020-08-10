<template>
<div class="row justify-content-center">
	<div class="col-md-10">
		<div class="card">
			<div class="card-body">
				<button class="btn btn-primary" v-print="'#printCustomerReport'"><i class="fa fa-print"></i> Print</button>
				<table class="table table-bordered mt-4">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Email</th>
							<th>Status</th>
							<th>Registered</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(customer, index) in customers" :key="index">
							<td>{{ customer.id}}</td>
							<td>{{ customer.first_name+' '+customer.last_name}}</td>
							<td>{{ customer.email}}</td>
							<td>{{ customer.status}}</td>
							<td>{{ customer.registered}}</td>
						</tr>
					</tbody>
				</table>
				<div class="d-none d-print-block">
					<customer-report-component
						id="printCustomerReport"
						:customers="customers"
						:dateNow="date_now"
						:admin="admin"
						></customer-report-component>
				</div>
			</div><!-- card-body -->
		</div><!-- card -->
	</div>
</div>
</template>
<script>
	export default {
		props: ['admin'],
		data() {
			return {
				customers: [],
				date_now: ''
			}
		},
		methods: {
			customerList() {
				axios.get('/api/customer-report')
				.then(response => {

					this.customers = response.data.customers;
					this.date_now = response.data.date_now;
				})
				.catch(error => {
					console.log(error.response);
				});
			}
		},
		created() {
			this.customerList();
		}

	}
</script>