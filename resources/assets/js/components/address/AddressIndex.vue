<template>
<div>
	<div class="clearfix mb-4">
		<h3 class="float-left">Addresses</h3>
		<a href="/my-account/addresses/create" class="float-right btn btn-primary"><i class="fa fa-plus"></i> Add Address</a>
	</div>
	<div>
		<table class="table table-bordered table-striped table-hovered">
			<thead>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th width="50%">Address</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<template v-if="loading">
					<tr>
						<td colspan="4">
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
					<template v-if="!addresses.length">
						<tr>
							<td class="text-center" colspan="4">No address yet.</td>
						</tr>
					</template>
					<template v-else>
						<tr v-for="(addr, index) in addresses" :key="index">
							<td>{{ addr.id }}</td>
							<td>{{ addr.address_name }}</td>
							<td>{{ addr.street+', '+addr.barangay+', '+addr.municipality+', '+addr.province+', '+addr.zip_code }}</td>
							<td>
								<a :href="'/my-account/address/edit/'+addr.id" class="btn btn-sm btn-primary">Edit</a>
								<button type="button" class="btn btn-sm btn-danger" @click="deleteAddress(addr.id)">Delete</button>
							</td>
						</tr>
					</template>
				</template>
			</tbody>
		</table>
	</div>
</div>
</template>

<script>
	import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		props: ['customer'],
		data() {
			return {
				addresses: [],
				loading: false,
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getAddresses() {
				this.loading = true;
				axios.get('/api/customer-address-get/'+this.customer.id)
				.then(response => {
					this.loading = false;
					this.addresses = response.data;
				})
				.catch(error => {
					this.loading = false;
					console.log(error.response);
				})
			},
			deleteAddress(addrID) {
				Swal({
				  title: 'Are you sure you want to delete this address?',
				  test: '',
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Yes',
				  cancelButtonText: 'No'
				}).then((result) => {
				  if (result.value) {
				  	axios.delete('/api/customer-address-delete/'+addrID)
					.then(response => {
						let res = response.data

						if (res.deleted)
						{
							 Swal('Address has been deleted', '', 'success')
                      window.location.reload();
						}
					})
					.catch(error => {
						console.log(error.response)
					})
				  }
				});
			}
		},
		mounted() {
			this.getAddresses();
		}
		
	}
</script>