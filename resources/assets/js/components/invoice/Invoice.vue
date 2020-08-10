<template>
<div class="row justify-content-center">
	<div class="col-md-12">
		<div class="card">
			<div class="card-body">
				<div class="row mb-4">
					<div class="col-md-4">
						<h2>Invoices</h2>
					</div>
					<div class="col-md-4">
						
					</div>
					<div class="col-md-4">
						 <template>
		                    <div class="input-group">
		                      <input type="text" class="form-control" placeholder="Search invoice number" v-model="search_value">
		                      <div class="input-group-append">
		                        <button class="btn btn-secondary" type="button" @click="searchInvoice">Search</button>
		                        <div class="input-group-append" v-if="has_search">
		                          <button class="btn btn-outline-danger" type="button" @click="clearSearch">Clear</button>
		                        </div>
		                      </div>
		                    </div>
		                  </template>
					</div>
				</div>
				<div>
					<table class="table table-striped">
						<thead>
							<tr class="bg-primary text-light">
								<th>Invoice No.</th>
								<th>Invoice Date</th>
								<th>Order No.</th>
								<th>Customer</th>
								<th>Subtotal</th>
								<th>Discount</th>
								<th>Shipping Fee</th>
								<th>Total Amount</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<template v-if="loading">
								<tr>
									<td colspan="9" align="center">
										<half-circle-spinner
				                            :animation-duration="1000"
				                            :size="30"
				                            color="#ff1d5e"
				                          />
									</td>	
								</tr>
							</template>
							<template v-else>
								<template v-if="!invoices.length">
									<tr>
										<td colspan="9" align="center">No invoices</td>	
									</tr>
								</template>
								<template v-else>
									<tr v-for="(invoice, index) in invoices" :key="index">
										<td>{{invoice.number}}</td>
										<td>{{invoice.created}}</td>
										<td>{{invoice.order_number}}</td>
										<td>{{invoice.first_name+' '+invoice.last_name}}</td>
										<td>&#8369;{{invoice.subtotal}}</td>
										<td>&#8369;{{invoice.discount}}</td>
										<td>&#8369;{{invoice.shipping_fee}}</td>
										<td>&#8369;{{invoice.total}}</td>
										<td><a :href="'/admin/invoice/'+invoice.number" class="btn btn-dark btn-sm">View</a></td>
									</tr>
								</template>
							</template>
						</tbody>
					</table>
					<template v-if="!loading">
						<div class="row" v-if="invoices.length != 0">
		                  <div class="col-md-8">
		                    <nav>
		                      <ul class="pagination">
		                        <li :class="{disabled:!pagination.first_link}" class="page-item"><a href="javascript:void(0);" @click="getInvoices(pagination.first_link)" class="page-link">&laquo;</a></li>
		                        <li :class="{disabled:!pagination.prev_link}" class="page-item"><a href="javascript:void(0);" @click="getInvoices(pagination.prev_link)" class="page-link">Prev</a></li>
		                        <li v-for="n in pagination.last_page" v-bind:key="n" :class="{active: pagination.current_page == n}" class="page-item"><a href="javascript:void(0);" @click="getInvoices(pagination.path_page + n)" class="page-link">{{ n }}</a></li>
		                        <li :class="{disabled:!pagination.next_link}" class="page-item"><a href="javascript:void(0);" @click="getInvoices(pagination.next_link)" class="page-link">Next</a></li>
		                        <li :class="{disabled:!pagination.last_link}" class="page-item"><a href="javascript:void(0);" @click="getInvoices(pagination.last_link)" class="page-link">&raquo;</a></li>
		                      </ul>
		                    </nav>
		                  </div>
		                  <div class="col-md-4">
		                    <p class="text-right">Page: {{ pagination.from_page }} - {{ pagination.to_page }}
		                    Total: {{ pagination.total_page }}</p>
		                  </div>
		                </div>
					</template>
				</div>
			</div><!-- /card-body -->
		</div><!-- /card -->
	</div>
</div>	
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';
	
	export default {
		data() {
			return {
				loading: false,
				invoices: [],
				paginate: {},
				search_value: '',
				has_search: false
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getInvoices(pagi) {
				if (this.search_value)
				{
					pagi = pagi || '/api/invoices?search='+this.search_value;
				}
				else
				{
					pagi = pagi || '/api/invoices';
				}
				
				this.loading = true;
				axios.get(pagi)
				.then((response) => {
					this.loading = false;
					this.invoices = response.data.data;
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
				.catch((error) => {
					this.loading = false;
					console.log(error.response);
				});
			},
			searchInvoice() {
				this.has_search = true;

				if (!this.search_value)
				{
					this.has_search = false;
					return null;
				}

				this.getInvoices();
			},
			clearSearch() {
				this.has_search = false;
				this.search_value = '';
				this.getInvoices();
			}
		},
		created() {
			this.getInvoices();
		}
	}
</script>