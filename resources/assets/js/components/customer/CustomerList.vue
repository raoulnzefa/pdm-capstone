<template>
<div class="row justify-content-center">
	<div class="col-md-10">
		<div class="card mb-4">
			<div class="card-header">
				<h2 class="card-header-text">Customers</h2>
			</div>
			<div class="card-body pt-5">
				<div>
					<table class="table table-striped table-bordered table-condensed table-hover">
						<thead>
							<tr>
								<th>ID</th>
								<th>Customer</th>
								<th>Email</th>
								<th>Date Registered</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<template v-if="loading">
								<tr>
									<td align="center" colspan="5">
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
										<td align="center" colspan="5">No customers</td>
									</tr>
								</template>
								<template v-else>
									<tr v-for="(customer, index) in customers" :key="index">
										<td>{{ customer.id }}</td>
										<td>{{ customer.first_name+' '+customer.last_name }}</td>
										<td>{{ customer.email }}</td>
										<td>{{ customer.registered}}</td>
										<td>{{ customer.status }}</td>
									</tr>
								</template>
							</template>
						</tbody>
					</table>
				</div>
				<!-- <template v-if="!loading">
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
                    <p class="text-right">Page: {{ pagination.from_page }} - {{ pagination.to_page }}
                    Total: {{ pagination.total_page }}</p>
                  </div>
                </div>
              </template> -->
			</div>
		</div>
	</div>
</div>
</template>
<script>
import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		components: { HalfCircleSpinner },
		data() {
			return {
				loading: false,
				customers: [],
				pagination: {}
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