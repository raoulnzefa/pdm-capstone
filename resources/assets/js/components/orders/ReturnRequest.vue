<template>
<div class="row justify-content-center">
    <div class="col-md-10">
        <h2>Retun Requests</h2>
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
                      <th>Action</th>
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
                      <template v-if="!return_requests.length">
                          <tr>
                            <td colspan="7" align="center">
                                No return requests
                            </td>
                          </tr>
                      </template>
                      <template v-else>
                          <tr v-for="(item, index) in return_requests" :key="index">
                            <td><i class="fa fa-eye text-success" title="Request viewed" v-if="item.viewed == 1"></i><i class="fa fa-eye text-danger" title="Request not view" v-else></i></td>
                            <td>{{ item.date_return_request }}</td>
                            <td>{{ item.order_number }}</td>
                            <td>{{ item.customer.first_name+' '+item.customer.last_name }}</td>
                            <td>{{ item.action }}</td>
                            <td>{{ item.status }}</td>
                            <td>
                              <a :href="'/admin/order/return/'+item.id+'/details'" class="btn btn-sm btn-dark">View Details</a>
                            </td>
                          </tr>
                      </template>
                    </template>
                  </tbody>
                </table>
              </div><!-- div table -->
              <template v-if="!loading">
                <div class="row" v-if="return_requests.length != 0">
                  <div class="col-md-8">
                    <nav>
                      <ul class="pagination">
                        <li :class="{disabled:!pagination.first_link}" class="page-item"><a href="javascript:void(0);" @click="getReturnRequests(pagination.first_link)" class="page-link">&laquo;</a></li>
                        <li :class="{disabled:!pagination.prev_link}" class="page-item"><a href="javascript:void(0);" @click="getReturnRequests(pagination.prev_link)" class="page-link">Prev</a></li>
                        <li v-for="n in pagination.last_page" v-bind:key="n" :class="{active: pagination.current_page == n}" class="page-item"><a href="javascript:void(0);" @click="getReturnRequests(pagination.path_page + n)" class="page-link">{{ n }}</a></li>
                        <li :class="{disabled:!pagination.next_link}" class="page-item"><a href="javascript:void(0);" @click="getReturnRequests(pagination.next_link)" class="page-link">Next</a></li>
                        <li :class="{disabled:!pagination.last_link}" class="page-item"><a href="javascript:void(0);" @click="getReturnRequests(pagination.last_link)" class="page-link">&raquo;</a></li>
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
        </div>
    </div>
</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners'
	export default {
		data() {
			return {
				loading: false,
				return_requests: [],
        pagination: {}
			}
		},
		components: {
			HalfCircleSpinner
		},
    methods: {
      getReturnRequests(pagi) {
        pagi = pagi || '/api/return-request/list';
        this.loading = true;
        axios.get(pagi)
        .then((response) => {
          this.loading = false;
          this.return_requests = response.data.data;
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
      this.getReturnRequests();
    }
	}
</script>