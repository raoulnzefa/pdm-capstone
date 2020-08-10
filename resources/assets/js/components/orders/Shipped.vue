<template>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
          <div class="card-body">
              <h2 class="mb-3">Shipped</h2>
              <div>
                <table class="table table-bordered table-striped table-hover">
                  <thead>
                    <tr>
                      <th class="text-center">Order No</th>
                      <th class="text-center">Date Order</th>
                      <th class="text-center">Date Shipped</th>
                      <th class="text-center">Customer</th>
                      <th class="text-center">Status</th>
                      <th class="text-center">Quantity</th>
                      <th class="text-center">Total</th>
                      <th width="12%" class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-if="loading">
                      <tr>
                        <td colspan="8" align="center">
                          <half-circle-spinner
                            :animation-duration="1000"
                            :size="30"
                            color="#ff1d5e"
                          />
                        </td>
                      </tr>
                    </template>
                    <template v-else>
                      <template v-if="!shipped_orders.length">
                          <tr>
                            <td colspan="8" align="center">
                                No shipped orders
                            </td>
                          </tr>
                      </template>
                      <template v-else>
                           <tr v-for="(order, index) in shipped_orders" :key="index">
                            <td align="center">{{ order.number }}</td>
                            <td align="center">{{ order.date_order }}</td>
                            <td align="center">{{ order.shipping_order.date_shipped }}</td>
                            <td align="center">{{ order.customer.first_name+' '+order.customer.last_name }}</td>
                            <td align="center"><h5><span class="badge badge-primart">{{ order.status }}</span></h5></td>
                            <td align="center">{{ order.quantity }}</td>
                            <td align="center">PHP {{ order.total }}</td>
                            <td align="center">
                              <a :href="'/admin/order/'+order.number+'/details'" class="btn btn-sm btn-dark">View Details</a>
                            </td>
                          </tr>
                      </template>
                    </template>
                  </tbody>
                </table>
              </div>
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
				shipped_orders: []
			}
		},
		components: {
			HalfCircleSpinner
		},
    methods: {
        getShippedOrders() {
          this.loading = true;
          axios.get('/api/orders/shipped')
          .then((response) => {
              this.loading = false;
              this.shipped_orders = response.data;
          })
          .catch((error) => {
              this.loading = false;
              console.log(error.response);
          });
        }
    },
    created() {
      this.getShippedOrders();
    }
	}
</script>