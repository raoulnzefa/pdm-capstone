<template>
<div class="row">
    <div class="col-12">
        <h2>Invoice List</h2>
        <div class="mb-3 clearfix">
          <!-- <button class="btn btn-primary float-left mr-1" @click="viewOrder">View Order Products</button>
          <button class="btn btn-primary float-left" @click="approveOrder">Approve Order</button>
          <button class="btn btn-danger float-right">Cancel Order</button> -->
        </div>

        <div id="PrintTable">
            <table class="table table-bordered table-striped table-hover table-condensed">
              <thead>
                <tr>
                  <th width="5%">ID</th>
                  <th width="7%">Order #</th>
                  <th width="13%">Customer</th>
                  <th width="10%">Created</th>
                  <th width="10%">Due Date</th>
                  <th width="10%">Total</th>
                  <th width="11%">Balance Due</th>
                  <th width="11%">Amount Due</th>
                  <th width="8%">Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  <tr v-for="(invoice, index) in invoices">
                    <td>{{ invoice.id }}</td>
                    <td>{{ invoice.order_id }}</td>
                    <td>{{ invoice.first_name+' '+invoice.last_name }}</td>
                    <td>{{ invoice.created_at }}</td>
                    <td>{{ invoice.due_date }}</td>
                    <td>P{{ invoice.total }}</td>
                    <td>P{{ invoice.balance_due }}</td>
                    <td>P{{ invoice.amount_due }}</td>
                    <td>{{ invoice.status }}</td>
                    <td>
                      <div class="clearfix">
                        <div class="dropdown show float-left mr-1" v-if="invoice.status != 'Paid'">
                          <a class="btn btn-primary btn-sm dropdown-toggle" href="#" role="button" id="invoiceDropdownLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Mark as</a>

                          <div class="dropdown-menu" aria-labelledby="invoiceDropdownLink">
                            <a class="dropdown-item" href="javascript:void(0)" @click="updateStatusInvoice('Paid', index)">Paid</a>
                            <a class="dropdown-item" href="javascript:void(0)" @click="updateStatusInvoice('Partial', index)">Partial</a>
                          </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary" @click="sendInvoice(index)" v-if="invoice.status === 'Created'">Send</button>
                        <button type="button" class="btn btn-sm btn-primary" v-if="invoice.status === 'Paid'">View</button>
                      </div>
                    </td>
                  </tr>
              </tbody>
            </table>
        </div>
        <button class="btn btn-primary" v-print="'#PrintTable'">Print</button>
       
    </div>
</div>
</template>

<script>
    export default {
      data() {
          return {
              invoices: []
          }
      },
      methods: {
          invoiceList() {
            axios.get('/api/invoice/list')
            .then(response => {
                this.invoices = response.data
            })
            .catch(error => {
                console.log(error.response)
            })
          },
          sendInvoice(index) {
            let invoice = this.invoices[index]

            axios.put('/api/invoice/send/'+invoice.id, {
                status: 'Sent'
            })
            .then(response => {
                let res = response.data

                if (res.success)
                {
                    Swal('Invoice Sent', 'Invoice has been successfully sent.', 'success')
                    this.invoiceList()
                }
            })

          },
          updateStatusInvoice(status, index) {
            let invoice = this.invoices[index]

            axios.put('/api/invoice/status/'+invoice.id, {
                status: status
            })
            .then(response => {
                let res = response.data

                if (res.success)
                {
                    Swal('Invoice Status', 'Invoice status has been successfully updated.', 'success')
                    this.invoiceList();
                }

            })
            .catch(error => {
                console.log(error.response)
            })
          }
      },
      created() {
          this.invoiceList()
      },
    }
</script>
