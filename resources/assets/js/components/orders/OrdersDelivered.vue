<template>
<div class="row justify-content-center">
    <div class="col-md-12">
        <div>
          <h2>Orders "Delivered"</h2>
        </div>
        <div class="card">
          <div class="card-body">
              <div class="row mb-4">
                <div class="col-md-3">
                  
                </div>
              </div>
              <div>
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th></th>
                      <th>Order #</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th>Customer</th>
                      <th>Payment method</th>
                      <th>Payment status</th>
                      <th>Qty</th>
                      <th>Total</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-if="loading">
                      <tr>
                        <td colspan="10" align="center">
                          <half-circle-spinner
                            :animation-duration="1000"
                            :size="30"
                            color="#ff1d5e"
                          />
                        </td>
                      </tr>
                    </template>
                    <template v-else>
                      <template v-if="!orders.length">
                        <tr>
                          <td colspan="10" align="center">
                            No delivered orders.
                          </td>
                        </tr>
                      </template>
                      <template v-else>
                        <tr v-for="(order, index) in orders" :key="index">
                          <td align="middle"><span v-if="order.viewed > 0"><i class="fa fa-eye" v-b-tooltip.hover title="Order already view"></i></span><span v-else><i class="fa fa-eye-slash" v-b-tooltip.hover title="Order not yet view"></i></span></td>
                          <td>{{ order.number }}</td>
                          <td>{{ order.order_created }}</td>
                          <td>{{ order.order_status }}</td>
                          <td>{{ order.customer.first_name+' '+order.customer.last_name }}</td>
                          <td>{{ order.order_payment_method }}</td>
                          <td><span style="font-size: 14px;" class="badge" :class="order.order_payment_status == 'Paid' ? 'badge-success' : 'badge-warning'">{{ order.order_payment_status }}</span></td>
                          <td>{{ order.order_quantity }}</td>
                          <td>&#8369;{{ order.order_total }}</td>
                          <td><a :href="'/admin/order/'+order.number+'/details'" class="btn btn-sm btn-primary">View</a></td>
                        </tr>
                      </template>
                    </template>
                  </tbody>
                </table>
              </div>
          </div><!-- card-body -->
        </div><!-- card -->
    </div><!-- col-md-12 -->
</div>
</template>

<script>
  import { HalfCircleSpinner } from 'epic-spinners';

  export default {
    props: ['admin'],
    data() {
      return {
        orders: [],
        loading: false,
      }
    },
    components: {
      HalfCircleSpinner
    },
    methods: {
      getOrdersDelivered() {
        this.loading = true;
        axios.get('/api/orders/delivered')
        .then(res => {
          this.orders = res.data;
          this.loading = false;
        })
        .catch(err => {
          this.loading = false;
        });
      }
    },
    created() {
      this.getOrdersDelivered();
    }
  }
</script>