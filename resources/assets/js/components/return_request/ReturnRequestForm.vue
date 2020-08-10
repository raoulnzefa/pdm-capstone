<template>
<div class="row">
    <div class="col-md-12">
        <h2>Return Request</h2>
				<div class="card">
          <div class="card-body">
            <div class="alert alert-info">
              Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
              tempor incididunt ut labore et dolore magna aliqua.
            </div>
            <h4 class="mb-4">Order #{{ order.number}}</h4>
            <div class="mb-5 mt-4">
            <p class="mb-4">Which product do you want to return?</p>
            <table class="table table-bordered table-striped mb-5">
              <thead>
                <tr>
                  <th>Ordered Product(s)</th>
                  <th>Price</th>
                  <th width="15%">Qty</th>
                  <th class="text-center" width="15%"></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(prod, index) in returnProducts" :key="index">
                  <td class="align-middle">
                    <img :src="'/storage/products/'+prod.inventory.product.image" class="img-fluid" width="20%" height="13%">
                    <span>{{ prod.product_name }}</span>
                  </td>
                  <td class="align-middle">&#8369;{{ prod.price }}</td>
                  <td class="align-middle text-center">{{ prod.quantity }}</td>
                  <td class="align-middle" align="middle">
                    <button class="btn btn-sm btn-danger" @click="returnThis(prod.id)">Return</button>
                  </td>
                </tr>
              </tbody>
            </table>
            
            </div>

          </div><!-- card-body -->
        </div><!-- card -->
        
        <!-- Modal Component -->
        <b-modal id="returnRequestModal"
                 ref="returnRequestModal"
                 title="Return Request Details"
                 ok-title="Submit"
                 no-close-on-backdrop
                 no-close-on-esc
                 :ok-disabled="submitted"
                 @ok="submitReturn"
                 @cancel="resetReturn"
                 @hidden="resetReturn">
            <div>
                <p class="mb-4">Why are you returning <b>{{ productName }}?</b></p>
                <form @submit.stop.prevent="returnDetails">
                  <div class="form-group">
                      <label for="returnItemQty">Return Qty.:</label>
                      <select class="form-control" id="returnItemQty" name="return_qty" v-model="qty">
                        <option v-for="x in returnQty" :value="x">{{x}}</option>
                      </select>
                  </div>
                  <div class="form-group">
                      <label for="returnAction">Return Action:</label>
                      <input type="text" name="action" class="form-control" id="returnAction" readonly value="Replacement">
                  </div>
                  <div class="form-group">
                      <label for="returnReason">Return Reason:</label>
                      <select class="form-control" id="returnReason" name="reason" 
                          v-model.trim="$v.reason.$model"
                          :class="{'is-invalid': $v.reason.$error}">
                        <option value="">Select a reason</option>
                        <option v-for="(reason,index) in reasons" :key="index" :value="reason.id">{{reason.title}}</option>
                      </select>
                      <div v-if="$v.reason.$error">
                        <span class="error-feedback" v-if="!$v.reason.required">Reason is required</span>
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="returnComment">Comment:</label>
                      <textarea class="form-control" rows="5" id="returnComment" name="comment" v-model="comment" placeholder="Your comment..."></textarea>
                  </div>
                </form>
            </div>
        </b-modal>

        <!-- Modal Component -->
        <b-modal id="returnTermsConditionsModal"
                 ref="returnTermsConditionsModal"
                 title="Return Policy"
                 no-close-on-backdrop
                 no-close-on-esc>
            <div>
              Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
              tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
              quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
              consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
              cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
              proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
            </div>
        </b-modal>
    </div>
</div>
</template>

<script>
  import { required, minLength, maxLength, helpers, between } from 'vuelidate/lib/validators';

  export default {
    props: [
      'order',
      'reasons_data',
      'invoice',
    ],
    data() {
      return {
        returnProducts: this.order.order_products,
        reasons: this.reasons_data,
        qty: 1,
        returnQty: '',
        productName: '',
        reason: '',
        comment: '',
        submitted: false,
        returnProductID: '',
      }
    },
    validations() {
      return {
        reason: {required}
      }
    },
    methods: {
      submitReturn(e) {
        e.preventDefault();
        this.returnDetails();
      },
      returnDetails() {
        this.$v.$touch()

        if (!this.$v.$invalid) {
          this.submitted = true;
          axios.post('/api/return-request/order', {
            order_number: this.order.number,
            invoice_number: this.invoice.number,
            return_product_id: this.returnProductID,
            reason: this.reason,
            comment: this.comment,
            customer_id: this.order.customer_id,
            return_qty: this.returnQty,
          })
          .then(res => {
            if (res.data.success) {
              this.$refs.returnRequestModal.hide();
              this.$nextTick(() => { this.$v.$reset() });
              window.location.href = res.data.redirect;
            }
            this.submitted = false;
          })
          .catch(err => {
            console.log(err.response);
            this.submitted = false;
          });
        }
      },
      returnThis(prodId) {
        const product = this.returnProducts.find(x => x.id == prodId);
        this.returnQty = product.quantity;
        this.productName = product.product_name;
        this.returnProductID = prodId;
        this.$refs.returnRequestModal.show();
      },
      showReturnPolicy(e) {
        this.$refs.returnTermsConditionsModal.show();
      },
      resetReturn() {
        this.productName = '';
        this.returnQty = '';
        this.$nextTick(() => { this.$v.$reset() });
      },
      testNumber(val) {
        return val + 10;
      }
    }
  }
</script>