<template>
<div>
	<div>			
		<center>
			<div style="width:550px; margin-bottom: 40px;">
				<img src="/images/logo.jpg" width="220" height="120" style="margin-bottom: 10px;">
				<p class="text-center mb-0 p">Bunlo, Mac Arthur Hi-way, 2500 Bocaue, Bulacan</p>
				<p class="text-center mb-0">09987901118</p>
				<p class="text-center mb-0">infinityfightgear.site</p>
				<p class="text-center mb-0"><b>Customer List</b></p></p>
			</div>
		</center>
	</div>
	<div>
		<div>
			<table width="100%">
				<tr>
					<td class="p">Printed Date: <b>{{ dateNow }}</b></td>
					<td align="right">Printed by: <b>{{ admin.first_name+' '+admin.last_name }}</b></td>
				</tr>
			</table>
		</div>
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

	</div>
</div>
</template>
<script>
	export default {
		props: ['admin', 'customers', 'dateNow'],
	}
</script>