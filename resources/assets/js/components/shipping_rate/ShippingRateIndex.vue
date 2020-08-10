<template>
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2>Delivery fee</h2>
				<div class="card">
          <div class="card-body">
            <template v-if="loading">
              <center>
                <half-circle-spinner
                    :animation-duration="1000"
                    :size="30"
                    color="#ff1d5e"
                  />
              </center>              
            </template>
            <template v-else>
              <div class="mb-3">
                <button type="button" class="btn btn-dark" @click="openShippingRateModal"><span v-if="!shipping_rates.length"><i class="fa fa-plus"></i> Set delivery fee</span><span v-else><span class="fa fa-edit"></span> Edit delivery fee</span></button>
              </div>
              <div>
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Manila rate</th>
                      <th>Province rate</th>
                      <th>Qty. order discount</th>
                      <th>Discount percent</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-if="!shipping_rates.length">
                      <tr>
                        <td colspan="4" class="text-center">No delivery details.</td>
                      </tr>
                    </template>
                    <template v-else>
                        <tr v-for="(shipping_rate, index) in shipping_rates" :key="index">
                          <td>&#8369;{{ shipping_rate.manila_rate }}</td>
                          <td>&#8369;{{ shipping_rate.province_rate }}</td>
                          <td>{{ shipping_rate.order_quantity }}</td>
                          <td>{{ shipping_rate.discount_percentage }}%</td>
                        </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </template>
          </div>
        </div><!-- card -->
        <!-- Modal Component -->
        <b-modal id="shippingRateModal"
             ref="shippingRateModal"
             :title="modal_title"
             no-close-on-backdrop
             no-close-on-esc
             :ok-title="ok_title"
             :ok-disabled="submit"
             @ok="submitShippingRate"
             @cancel="cancelShippingRate"
             @hidden="cancelShippingRate">
          <form @submit.stop.prevent="saveShippingRate">
            <div class="form-group">
              <label for="shipManilaRate">Manila rate:</label>
              <input type="text" name="manila_rate" id="shipManilaRate" class="form-control"
                    v-model.trim="$v.manila_rate.$model"
                    :class="{'is-invalid': $v.manila_rate.$error}">
              <div v-if="$v.manila_rate.$error">
                <span class="error-feedback" v-if="!$v.manila_rate.required">Please enter a value</span>
                <span class="error-feedback" v-if="!$v.manila_rate.moneyRegex">Please enter a valid format</span>
              </div>
            </div>
            <div class="form-group">
              <label for="shipProvinceRate">Province rate:</label>
              <input type="text" name="province_rate" id="shipProvinceRate" class="form-control"
                    v-model.trim="$v.province_rate.$model"
                    :class="{'is-invalid': $v.province_rate.$error}">
                  <div v-if="$v.province_rate.$error">
                    <span class="error-feedback" v-if="!$v.province_rate.required">Please enter a value</span>
                    <span class="error-feedback" v-if="!$v.province_rate.moneyRegex">Please enter a valid format</span>
                  </div>
            </div>
            <div class="form-group">
              <label for="shipQtyOrderDiscount">Quantity order discouunt:</label>
              <select name="qty_order_discount" id="shipQtyOrderDiscount" class="form-control"
                  v-model.trim="$v.qty_order_discount.$model"
                  :class="{'is-invalid': $v.qty_order_discount.$error}">
                <option v-for="(qty, index) in quantites" :key="index">{{qty}}</option>
              </select>
              <div v-if="$v.qty_order_discount.$error">
                <span class="error-feedback" v-if="!$v.qty_order_discount.required">Please enter a value</span>
              </div>
            </div>
            <div class="form-group">
              <label for="shipDiscountPercent">Discount percentage:</label>
              <select name="discount_percent" id="shipDiscountPercent" class="form-control"
                    v-model.trim="$v.discount_percent.$model"
                    :class="{'is-invalid': $v.discount_percent.$error}">
                  <option v-for="(x, index) in discouunts" :key="index">{{x}}</option>
              </select>
              <div v-if="$v.discount_percent.$error">
                <span class="error-feedback" v-if="!$v.discount_percent.required">Please enter a value</span>
              </div>
            </div>
          </form>  
        </b-modal>
    </div>
</div>
</template>

<script>
  import { required, minLength, maxLength, helpers } from 'vuelidate/lib/validators';
  import { HalfCircleSpinner } from 'epic-spinners';
  
  const moneyRegex = helpers.regex('moneyRegex', /^[1-9][0-9.]*$/);

  export default {
    props: ['admin'],
    data() {
      return {
        shipping_rates: [],
        loading: false,
        modal_title: '',
        ok_title: '',
        submit: false,
        manila_rate: '',
        province_rate: '',
        qty_order_discount: '',
        discount_percent: '',
        edit: false,
        server_errors: [],
        shipping_rate_id: '',
        quantites: [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20],
        discouunts: [5,10,15,20,25,30,35,40,45,50],
      }
    },
    components: {
      HalfCircleSpinner
    },
    validations() {
      return {
        manila_rate: {
          required,
          moneyRegex
        },
        province_rate: {
          required,
          moneyRegex
        },
        qty_order_discount: {
          required
        },
        discount_percent: {
          required
        }
      }
    },
    methods: {
      getShippingRate() {
        this.loading = true;
        axios.get('/api/get-shipping-rate')
        .then(response => {
          this.loading = false;
          this.shipping_rates = response.data;
        })
        .catch(error => {
          this.loading = false;
          console.log(error.response);
        });
      },
      openShippingRateModal(e) {
        if (!this.shipping_rates.length) {
          this.modal_title = 'Set delivery fee';
          this.ok_title = 'Save details';
        } else {
          this.edit = true;
          this.ok_title = 'Update details';
          this.modal_title = 'Edit delivery rate';
          const data = this.shipping_rates.find(x => x.id == 1);
          this.manila_rate = parseFloat(data.manila_rate);
          this.province_rate = parseFloat(data.province_rate);
          this.qty_order_discount = data.order_quantity;
          this.discount_percent = data.discount_percentage;
          this.shipping_rate_id = data.id;
        }

        this.$refs.shippingRateModal.show();
      },
      saveShippingRate() {
        this.$v.$touch();

        if (!this.$v.$invalid) {
          this.submit = true;

          if (!this.edit) {
            axios.post('/api/set-shipping-rate', {
              manila_rate: this.manila_rate,
              province_rate: this.province_rate,
              qty_order_discount: this.qty_order_discount,
              discount_percent: this.discount_percent,
              admin_id: this.admin.id
            })
            .then(response => {
              this.submit = false;
              if (response.data.success) {
                 Swal('Delivery fee has been created', '', 'success')
                  .then((okay) => {
                    if (okay) {
                        this.$refs.shippingRateModal.hide();
                        this.resetModal();
                        this.getShippingRate();
                    }
                  });
              }
            })
            .catch(error => {
              this.submit = false;
              if (error.response.status == 422) {
                this.server_errors = error.response.data.errors;
              }
            });
          } else {
            //edit
            axios.put('/api/update-shipping-rate/'+this.shipping_rate_id, {
              manila_rate: this.manila_rate,
              province_rate: this.province_rate,
              qty_order_discount: this.qty_order_discount,
              discount_percent: this.discount_percent,
              admin_id: this.admin.id
            })
            .then(response => {
              this.submit = false;
              if (response.data.success) {
                 Swal('Delivery has been updated','', 'success')
                  .then((okay) => {
                    if (okay) {
                        this.$refs.shippingRateModal.hide();
                        this.resetModal();
                        this.getShippingRate();
                    }
                  });
              }
            })
            .catch(error => {
              this.submit = false;
              if (error.response.status == 422) {
                this.server_errors = error.response.data.errors;
              }
            });
          }
        }

      },
      submitShippingRate(e) {
        e.preventDefault();
        this.saveShippingRate();
      },
      resetModal() {
          this.edit = false;
          this.ok_title = 'Save details';
          this.modal_title = 'Set delivery fee';
          this.server_errors = [];
          this.$nextTick(() => { this.$v.$reset() });
      },
      cancelShippingRate() {
        this.resetModal();
      }
    },
    created() {
      this.getShippingRate();
    }
  }
</script>