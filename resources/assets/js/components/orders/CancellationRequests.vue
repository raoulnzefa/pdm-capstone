<template>
<div class="row justify-content-center">
    <div class="col-md-10">
        <h2>Cancellation Requests</h2>
        <div class="card">
          <div class="card-body pt-5">
              <div>
                <table class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th width="4%"></th>
                      <th>Date Request</th>
                      <th>Order No.</th>
                      <th>Customer</th>
                      <th>Action</th>
                      <th>Status</th>
                      <th width="12%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-if="loading">
                      <tr>
                        <td colspan="7" align="center">
                          <half-circle-spinner
                            :animation-duration="1000"
                            :size="30"
                            color="#ff1d5e"
                          />
                        </td>
                      </tr>
                    </template>
                    <template v-else>
                      <template v-if="!cancellation_requests.length">
                          <tr>
                            <td colspan="7" align="center">
                                No cancellation requests
                            </td>
                          </tr>
                      </template>
                      <template v-else>
                          <tr v-for="(cancel_order, index) in cancellation_requests" :key="index">
                            <td><i class="fa fa-eye text-success" title="Request viewed" v-if="cancel_order.viewed == 1"></i><i class="fa fa-eye text-danger" title="Request not view" v-else></i></td>
                            <td>{{ cancel_order.date_request }}</td>
                            <td>{{ cancel_order.order_number }}</td>
                            <td>{{ cancel_order.customer.first_name+' '+cancel_order.customer.last_name }}</td>
                            <td>{{ cancel_order.action }}</td>
                            <td>{{ cancel_order.status }}</td>
                            <td><a :href="'/admin/order/cancellation/'+cancel_order.id+'/details'" class="btn btn-dark btn-sm">View Details</a></td>
                          </tr>
                      </template>
                    </template>
                  </tbody>
                </table>
              </div>
              <template v-if="!loading">
                <div class="row" v-if="cancellation_requests.length != 0">
                  <div class="col-md-8">
                    <nav>
                      <ul class="pagination">
                        <li :class="{disabled:!pagination.first_link}" class="page-item"><a href="javascript:void(0);" @click="getCancellationRequests(pagination.first_link)" class="page-link">&laquo;</a></li>
                        <li :class="{disabled:!pagination.prev_link}" class="page-item"><a href="javascript:void(0);" @click="getCancellationRequests(pagination.prev_link)" class="page-link">Prev</a></li>
                        <li v-for="n in pagination.last_page" v-bind:key="n" :class="{active: pagination.current_page == n}" class="page-item"><a href="javascript:void(0);" @click="getCancellationRequests(pagination.path_page + n)" class="page-link">{{ n }}</a></li>
                        <li :class="{disabled:!pagination.next_link}" class="page-item"><a href="javascript:void(0);" @click="getCancellationRequests(pagination.next_link)" class="page-link">Next</a></li>
                        <li :class="{disabled:!pagination.last_link}" class="page-item"><a href="javascript:void(0);" @click="getCancellationRequests(pagination.last_link)" class="page-link">&raquo;</a></li>
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
        </div><!-- card -->
        
    </div>
</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners'
	export default {
		data() {
			return {
				loading: false,
				cancellation_requests: [],
        pagination: {}
			}
		},
		components: {
			HalfCircleSpinner
		},
    methods: {
        getCancellationRequests(pagi) {

          pagi = pagi || '/api/cancel-request/list';

          this.loading = true;
          axios.get(pagi)
          .then((response) => {
              this.loading = false;
              this.cancellation_requests = response.data.data;
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
        }  
    },
    created() {
      this.getCancellationRequests();
    }
	}
</script>