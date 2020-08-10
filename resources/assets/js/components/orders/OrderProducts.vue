<template>
<div class="row">
    <div class="col-12">
        <h2>Order ID: <b>{{ order.id }}</b></h2>
        <div class="mb-3 d-block clearfix">
            <span v-if="order.status != 'Completed'">
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#additionalOrderModal" v-if="order.status != 'Processing'">Additional Order</button>
              <span v-if="order.status != 'Processing'">
                <button type="button" class="btn btn-primary" @click="allowEdit" v-if="!edit">Edit Details</button>
                <button type="button" class="btn btn-danger" @click="cancelEdit" v-if="edit">Cancel Edit</button>
               </span> 
              <button type="button" class="btn btn-primary" @click="saveToInvoice" v-if="noInvoice">Save to Invoice</button>
            </span>
          <a href="/admin/orders" class="btn btn-primary float-right">Back</a>
        </div>
        <div class="alert alert-danger" v-if="has_error">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                <li v-for="err in server_errors">{{ err[0] }}</li>
            </ul>
        </div>
        <div>
            <table class="table table-bordered table-striped table-hover table-condensed mb-0">
              <thead>
                <tr>
                  <th width="5%">ID</th>
                  <th width="10%">SKU</th>
                  <th width="30%">Product</th>
                  <th width="10%">Quantity</th>
                  <th width="10%">Price</th>
                  <th width="10%">Total</th>
                  <th width="15%" v-if="order.status != 'Completed'">Action</th>
                </tr>
              </thead>
              <tbody>
                  <tr v-for="(product, index) in order_products">
                    <td>{{ product.id }}</td>
                    <td>{{ product.product_sku }}</td>
                    <td>{{ product.prod_name }}<span v-if="product.color != null">&nbsp;{{ product.color }}</span><span v-if="product.size != null">&nbsp;{{ product.size }}</span><span v-if="product.weight != null">&nbsp;{{ product.weight }}</span><span v-if="product.height != null">&nbsp;{{ product.height }}</span></td>
                    <td><input v-validate="'required|min_value:1|numeric'" type="number" :name="'quantity'+index" v-model="product.quantity" class="form-control text-center" :class="{'is-invalid':errors.has('quantity'+index)}" :disabled="!edit"><span class="d-block invalid-feedback">{{ errors.first('quantity'+index) }}</span></td>
                    <td>P{{ product.price }}</td>
                    <td>P{{ product.amount }}</td>
                    <td v-if="order.status != 'Completed'"><span v-if="order.status != 'Processing'"><button type="button" class="btn btn-sm btn-primary" @click="updateQty(index)" :disabled="!edit">Update</button>&nbsp;<button type="button" class="btn btn-sm btn-danger" @click="removeProd(index)" :disabled="!edit">Remove</button></span></td>
                  </tr>
              </tbody>
            </table>
            <table class="table">
              <tr>
                <td></td>
                <th width="20%">Subtotal:</th>
                <td width="20%"><span class="float-right">P{{ subtotal }}</span></td>
              </tr>
              <tr>
                <td></td>
                <th>Discount:</th>
                <td><span class="float-right" v-if="order.discount == '0.00'">P0.00 (0%)</span><span class="float-right" v-if="order.discount != '0.00'">P{{ order.discount }} ({{ dis_percent }}%)</span></td>
              </tr>
              <tr>
                <td></td>
                <th>Delivery Charge:</th>
                <td><span class="float-right" v-if="order.delivery_method != 'Delivery'">P0.00</span><span class="float-right" v-if="order.delivery_method == 'Delivery'">P{{ order.delivery_charge }}</span></td>
              </tr>
              <tr>
                <td></td>
                <th>Grand Total:</th>
                <td><span class="float-right"><h4>P{{ order.grand_total }}</h4></span></td>
              </tr>
            </table>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="additionalOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Additional Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div>
                    <div class="form-group row">
                      <label for="searh_prod" class="col-sm-2 col-form-label">Search Product:</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" id="searh_prod" placeholder="Product name" v-model="search">
                      </div>  
                    </div>  
                </div>
                <table class="table table-bordered table-condensed table-striped table-hover">
                  <thead>
                    <tr>
                      <th width="10%">SKU</th>
                      <th width="30%">Product</th>
                      <th width="8%">Price</th>
                      <th width="7%" class="text-center">Stock</th>
                      <th width="7%" class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(product, index) in filteredProducts">
                      <td>{{ product.sku }}</td>
                      <td>{{ product.prod_name }}<span v-if="product.color != null">&nbsp;{{ product.color }}</span><span v-if="product.size != null">&nbsp;{{ product.size }}</span><span v-if="product.weight != null">&nbsp;{{ product.weight }}</span><span v-if="product.height != null">&nbsp;{{ product.height }}</span></td>
                      <td>P{{ product.price }}</td>
                      <td class="text-center">{{ product.quantity }}</td>
                      <td class="text-center"><button type="button" class="btn btn-sm btn-primary" @click="saveAdditionalOrder(index)">Add</button></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

    </div>
</div>
</template>

<script>
    export default {
      props: ['id'],
      data() {
          return {
            order: [],
            order_products: [],
            subtotal: '0.00',
            server_errors: [],
            has_error: false,
            dis_percent: 0,
            additional_products: [],
            search: '',
            invoice: [],
            noInvoice: false,
            edit: false,
            completed: false
          }
      },
      methods: {
        getOrderProducts()
        {
            axios.get('/api/order/'+this.id+'/products')
            .then(response => {
                this.order_products = response.data
            })
            .catch(error => {
                console.log(error.response)
            })
        },
        getSubtotal() {
            axios.get('/api/order-product/'+this.id+'/subtotal')
            .then(response => {
                this.subtotal = response.data
            })
            .catch(error => {
                console.log(error.response)
            })
        },
        updateQty(index) {
            this.$validator.validate()
            .then(result => {
                if (result)
                {
                    let product = this.order_products[index]

                    axios.put('/api/order-product/'+product.id+'/update', {
                        quantity: product.quantity,
                        order_id: this.order.id
                    })
                    .then(response => {
                        console.log(response.data)
                        let res = response.data

                        if (res.updated)
                        {
                            Swal('Updated', res.message, 'success')
                            this.getOrderData()
                            this.getOrderProducts()
                            this.getSubtotal()
                            this.dis_percent = res.discount_percent
                            this.edit = false
                        }
                    })
                    .catch(error => {
                        if (error.response.status == 422)
                        {
                            this.server_errors = error.response.data.errors
                            this.has_error = true
                            this.not_terms_and_conditions = true
                        }
                    })
                }
            })
        },
        getOrderData() {
            axios.get('/api/order/'+this.id+'/detail')
            .then(response => {
                this.order = response.data

                if (this.order.status == 'Completed')
                {
                    this.completed = true
                }
            })
            .catch(error => {
                console.log(error.response)
            })
        },
        removeProd(index) {
            Swal({
              title: 'Are you sure?',
              text: 'You want to remove this product into your cart?',
              type: 'warning',
              showCancelButton: true,
              confirmButtonText: 'Yes',
              cancelButtonText: 'No'
            }).then((result) => {
              let product = this.order_products[index]

              if (result.value) {
                  axios.delete('/api/order-product/'+product.id+'/remove/'+this.order.id)
                  .then(response => {
                    let res = response.data

                    if (res.deleted)
                    {
                        this.getOrderProducts()
                        this.getSubtotal()
                        this.getOrderData()
                        this.dis_percent = res.discount_percent
                    }
                  })
                  .catch(error => {
                    console.log(error.response)
                  })
              }
            })
        },
        additionalProducts() {
            axios.get('/api/product/table')
            .then(response => {
                this.additional_products = response.data
            })
            .catch(error => {
                console.log(error.response)
            })
        },
        saveAdditionalOrder(index) {
            let product = this.additional_products[index]

            axios.post('/api/order-product/additional', {
                order_id: this.order.id,
                quantity: 1,
                product_sku: product.sku
            })
            .then(response => {
                let res = response.data

                if (res.success)
                {
                    Swal('Additional Order', res.message, 'success')
                    this.getOrderProducts()
                    this.getSubtotal()
                    this.getOrderData()
                    this.dis_percent = res.discount_percent
                }
            })
            .catch(error => {
                console.log(error.response)
            })
        },
        saveToInvoice() {
            axios.post('/api/invoice', {
                order_id: this.order.id,
                customer_email: this.order.customer_email
            })
            .then(response => {
                let res = response.data

                if (res.saved)
                {
                    Swal('Invoice Created', 'Invoice has been successfully saved.', 'success')
                    this.getInvoiceOrder()
                }
            })  
            .catch(error => {
                console.log(error.response)
            })
        },
        getInvoiceOrder() {
            axios.get('/api/invoice/order/'+this.id)
            .then(response => {
                this.invoice = response.data

                if (this.invoice.order_id == this.id)
                {
                    this.noInvoice = false
                }
                
                if (Object.keys(response.data).length == 0)
                {
                    this.noInvoice = true
                }
  
            })
            .catch(error => {
                console.log(error.response)
            })
        },
        allowEdit() {
          this.edit = true
        },
        cancelEdit() {
          this.edit = false
        }
      },
      created() {
        this.getOrderProducts()
        this.getSubtotal()
        this.getOrderData()
        this.additionalProducts()
        this.getInvoiceOrder()
      },
      computed: {
        filteredProducts() {
          return this.additional_products.filter((product) => {
            return product.prod_name.match(this.search)
          })
        }
      }
    }
</script>

