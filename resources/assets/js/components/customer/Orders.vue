<template>
<div class="row">
    <div class="col-md-12">
    <h3 class="mb-4">Orders</h3>  
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="input-group">
            <select class="custom-select" v-model="order_status" @change="filterOrderStatus">
              <option :value="null" disabled>Filter by</option>
              <option value="For pickup">For pickup</option>
              <option value="Pending payment">Pending payment</option>
              <option value="Processing">Processing</option>
              <option value="For shipping">For shipping</option>
              <option value="Shipped">Shipped</option>
              <option value="Completed">Completed</option>
              <option value="Cancelled">Cancelled</option>
            </select>
            <div class="input-group-append" v-if="order_status">
              <button class="btn btn-outline-danger" type="button" @click="clearFilter">Clear</button>
            </div>
          </div>
        </div>
        <div class="col-md-6 offset-md-2">
          <template>
            <div class="input-group">
              <input type="text" class="form-control float-right" placeholder="Search order number" v-model="search_val">
              <div class="input-group-append">
                <button class="btn btn-secondary" type="button" @click="searchProduct"><i class="fa fa-search"></i> Search</button>
                <div class="input-group-append" v-if="on_search">
                  <button class="btn btn-outline-danger" type="button" @click="clearSearch">Clear</button>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>
      <div>
        <table class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <th></th>
              <th>Order No.</th>
              <th>Date</th>
              <th>Status</th>
              <th>Qty</th>
              <th>Total</th>
              <th></th>
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
              <template v-if="!orders.length">
                  <tr>
                    <td colspan="8" align="center">
                        No orders
                    </td>
                  </tr>
              </template>
              <template v-else>
                <tr v-for="(order, index) in orders" :key="index">
                  <td align="middle"><span style="font-size: 20px;" :class="order.status_update > 0 ? 'text-danger' : 'text-secondary'"><i class="fa fa-exclamation-circle"></i></span></td>
                  <td>{{ order.number }}</td>
                  <td>{{ order.order_created }}</td>
                  <td>
                    <span class="badge badge-info" style="font-size: 14px;" v-if="order.order_status == 'For pickup'">{{ order.order_status }}</span>
                    <span class="badge badge-warning" style="font-size: 14px;" v-if="order.order_status == 'Pending payment'">{{ order.order_status }}</span>
                    <span class="badge badge-primary" style="font-size: 14px;" v-if="order.order_status == 'Processing'">{{ order.order_status }}</span>
                     <span class="badge badge-for-shipping" style="font-size: 14px;" v-if="order.order_status == 'For shipping'">{{ order.order_status }}</span>
                    <span class="badge badge-for-shipped" style="font-size: 14px;" v-if="order.order_status == 'Shipped'">{{ order.order_status }}</span>
                    <span class="badge badge-success" style="font-size: 14px;" v-if="order.order_status == 'Completed'">{{ order.order_status }}</span>
                    <span class="badge badge-danger" style="font-size: 14px;" v-if="order.order_status == 'Cancelled'">{{ order.order_status }}</span>
                    <span class="badge badge-danger" style="font-size: 14px;" v-if="order.order_status == 'Overdue'">{{ order.order_status }}</span>
                  </td>
                  <td>{{ order.order_quantity }}</td>
                  <td>&#8369;{{ order.order_total }}</td>
                  <td><a :href="'/my-account/order/'+order.number" class="btn btn-sm btn-primary" v-b-tooltip.hover title="View Details">View</a></td>
                </tr>
              </template>
            </template>
          </tbody>
        </table>
      </div>
      <template v-if="!loading">
        <div class="row" v-if="orders.length != 0">
          <template v-if="pagination.total_page > 10">
            <div class="col-md-8">
              <nav>
                <ul class="pagination">
                  <li :class="{disabled:!pagination.first_link}" class="page-item"><a href="javascript:void(0);" @click="getOrders(pagination.first_link)" class="page-link">&laquo;</a></li>
                  <li :class="{disabled:!pagination.prev_link}" class="page-item"><a href="javascript:void(0);" @click="getOrders(pagination.prev_link)" class="page-link">Prev</a></li>
                  <li v-for="n in pagination.last_page" v-bind:key="n" :class="{active: pagination.current_page == n}" class="page-item"><a href="javascript:void(0);" @click="getOrders(pagination.path_page + n)" class="page-link">{{ n }}</a></li>
                  <li :class="{disabled:!pagination.next_link}" class="page-item"><a href="javascript:void(0);" @click="getOrders(pagination.next_link)" class="page-link">Next</a></li>
                  <li :class="{disabled:!pagination.last_link}" class="page-item"><a href="javascript:void(0);" @click="getOrders(pagination.last_link)" class="page-link">&raquo;</a></li>
                </ul>
              </nav>
            </div>
            <div class="col-md-4">
             <!--  <p class="text-right">Page: {{ pagination.from_page }} - {{ pagination.to_page }}
              <p class="text-right">Total: {{ pagination.total_page }}</p></p> -->
            </div>
          </template>
        </div>
      </template>
    </div>
</div>
</template>
<script>
  import { HalfCircleSpinner } from 'epic-spinners';

  export default {
    props: ['customer'],
    data() {
      return {
        orders: [],
        loading: false,
        on_search: false,
        search_val: null,
        order_status: null,
        filter_by: null,
      }
    },
    components: {
      HalfCircleSpinner
    },
    methods: {
      getOrders(pagi) {
        if (this.search_val)
        {
          pagi = pagi || '/api/orders/customer/'+this.customer.id+'?search='+this.search_val;
        }
        else if (this.order_status)
        {
          pagi = pagi || '/api/orders/customer/'+this.customer.id+'?status='+this.order_status;
          
        }
        else 
        {
          pagi = pagi || '/api/orders/customer/'+this.customer.id;
        }

        this.loading = true;
        axios.get(pagi)
        .then(response => {
            this.loading = false;
            this.orders = response.data.data;
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

        }).
        catch(error => {
          this.loading = false;
          console.log(error.response);
        })
      },
      searchProduct() {
        this.on_search = true;

        if (!this.search_val)
        {
          this.on_search = false;
          return null;
        }

        this.getOrders();
      },
      clearSearch() {
        this.search_val = null;
        this.on_search = false;
        this.getOrders();
      },
      filterOrderStatus(e) {
        this.filter_by = e;
        this.getOrders();
        
      },
      clearFilter() {
        this.order_status = null;
        this.filter_by = null;
        this.getOrders();
      }
    },
    mounted() {
      this.getOrders();
    }
  }
</script>