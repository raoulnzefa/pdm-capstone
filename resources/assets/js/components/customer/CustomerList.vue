<template>
<div class="row">
	<div class="col-md-12">
	<div class="clearfix mb-4 mt-4">
		<h2 class="float-left">Customers</h2>
		<form method="POST" v-if="customers.length" action="/admin/report/generate-customer">
			<input type="hidden" name="_token" :value="csrf">
	      <input type="hidden" name="report_type" :value="filter_by">
	      <input type="hidden" name="admin_id" :value="admin.id">
			<button type="submit" class="btn btn-primary float-right">Generate report</button>
		</form>
	</div>
	<div>
		<table class="table table-striped table-bordered table-condensed table-hover">
			<thead>
				<tr>
					<th>ID</th>
					<th>Customer</th>
					<th>Email</th>
					<th>Status</th>
					<th>Date Registered</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<template v-if="loading">
					<tr>
						<td align="center" colspan="6">
							<half-circle-spinner
			                    :animation-duration="1000"
			                    :size="30"
			                    color="#ff1d5e"
			                  />
						</td>
					</tr>
				</template>
				<template v-else>
					<template v-if="!customers.length">
						<tr>
							<td align="center" colspan="6">No customers</td>
						</tr>
					</template>
					<template v-else>
						<tr v-for="(customer, index) in customers" :key="index">
							<td>{{ customer.id }}</td>
							<td>{{ customer.first_name+' '+customer.last_name }}</td>
							<td>{{ customer.email }}</td>
							<td>{{ customer.status}}</td>
							<td>{{ customer.registered }}</td>
							<td align="center">
								<a :href="'/admin/customer/'+customer.id" class="btn btn-sm btn-primary">View</a>
							</td>
						</tr>
					</template>
				</template>
			</tbody>
		</table>
	</div>
	<template v-if="!loading">
       <div class="row" v-if="customers.length != 0">
         <div class="col-md-8">
           <nav>
             <ul class="pagination">
               <li :class="{disabled:!pagination.first_link}" class="page-item"><a href="javascript:void(0);" @click="customerList(pagination.first_link)" class="page-link">&laquo;</a></li>
               <li :class="{disabled:!pagination.prev_link}" class="page-item"><a href="javascript:void(0);" @click="customerList(pagination.prev_link)" class="page-link">Prev</a></li>
               <li v-for="n in pagination.last_page" v-bind:key="n" :class="{active: pagination.current_page == n}" class="page-item"><a href="javascript:void(0);" @click="customerList(pagination.path_page + n)" class="page-link">{{ n }}</a></li>
               <li :class="{disabled:!pagination.next_link}" class="page-item"><a href="javascript:void(0);" @click="customerList(pagination.next_link)" class="page-link">Next</a></li>
               <li :class="{disabled:!pagination.last_link}" class="page-item"><a href="javascript:void(0);" @click="customerList(pagination.last_link)" class="page-link">&raquo;</a></li>
             </ul>
           </nav>
         </div>
         <div class="col-md-4">
           <p class="text-right">Showing {{ pagination.from_page }} to {{ pagination.to_page }} of {{ pagination.total_page }} {{ (pagination.total_page > 1) ? 'entries' : 'entry'}}</p>
         </div>
       </div>
     </template>
	</div>
</div>
</template>
<script>
import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		components: { HalfCircleSpinner },
		props: ['admin'],
		data() {
			return {
				loading: false,
				customers: [],
				pagination: {},
				csrf: document.head.querySelector('meta[name="csrf-token"]').content,
			}
		},
		methods: {
			customerList(pagi) {
				pagi = '/api/customers';

				this.loading = true;
				axios.get(pagi)
				.then(response => {
					this.loading = false;
					this.customers = response.data.data;
					this.pagination = {
	                    current_page: response.data.current_page,
	                    last_page: response.data.last_page,
	                    from_page: response.data.from,
	                    to_page: response.data.to,
	                    total_page: response.data.total,
	                    path_page: response.data.path+'?page=',
	                    first_link: response.data.first_page_url,
	                    last_link: response.data.last_page_url,
	                    prev_link: response.data.prev_page_url,
	                    next_link: response.data.next_page_url
	                };
				})
				.catch(error => {
					this.loading = false;
					console.log(error.response)
				});
			}
		},
		created() {
			this.customerList()
		}
	}
</script>