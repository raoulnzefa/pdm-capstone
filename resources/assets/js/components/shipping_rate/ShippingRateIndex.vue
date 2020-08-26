<template>
<div class="row">
    <div class="col-md-12">
        <h2 class="mt-4 mb-4">Shipping rate</h2>
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
          <div class="mb-4">
            <button type="button" class="btn btn-primary" @click="openShippingRateModal"><span v-if="!shipping_rates.length"><i class="fa fa-plus"></i> Set shipping rate</span><span v-else><span class="fa fa-edit"></span> Edit shipping rate</span></button>
          </div>
          <div>
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Manila rate</th>
                  <th>Province rate</th>
                  <th>Discount %</th>
                  <th>Qty</th>
                </tr>
              </thead>
              <tbody>
                <template v-if="!shipping_rates.length">
                  <tr>
                    <td colspan="4" class="text-center">No shipping rate.</td>
                  </tr>
                </template>
                <template v-else>
                    <tr v-for="(shipping_rate, index) in shipping_rates" :key="index">
                      <td>{{ formatMoney(shipping_rate.manila_rate) }}</td>
                      <td>{{ formatMoney(shipping_rate.province_rate) }}</td>
                      <td>{{ shipping_rate.discount_percentage }}</td>
                      <td>{{ shipping_rate.order_quantity }}</td>
                    </tr>
                </template>
              </tbody>
            </table>
          </div>
        </template>
          
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
             @hidden="cancelShippingRate"
             @shown="focusOnInput">
          <form @submit.stop.prevent="saveShippingRate">
            <div class="form-group">
              <label for="shipManilaRate">Manila rate:</label>
              <input type="text" name="manila_rate" id="shipManilaRate" class="form-control"
                    v-model.trim="$v.manila_rate.$model"
                    ref="refsManilaInput"
                    tabindex="1" 
                    placeholder="Enter manila rate" 
                    :class="{'is-invalid': $v.manila_rate.$error}">
              <div v-if="$v.manila_rate.$error">
                <span class="error-feedback" v-if="!$v.manila_rate.required">Please enter a value</span>
                <template v-if="$v.manila_rate.required">
                  <span class="error-feedback" v-if="!$v.manila_rate.moneyRegex">Please enter a valid value</span>
                  <template v-if="$v.manila_rate.moneyRegex">
                    <span class="error-feedback" v-if="!$v.manila_rate.decimal">Please enter a valid value</span>
                  </template>
                </template>
              </div>
            </div>
            <div class="form-group">
              <label for="shipProvinceRate">Province rate:</label>
              <input type="text" name="province_rate" id="shipProvinceRate" class="form-control"
                    v-model.trim="$v.province_rate.$model"
                    placeholder="Enter province rate" 
                    tabindex="2" 
                    :class="{'is-invalid': $v.province_rate.$error}">
                  <div v-if="$v.province_rate.$error">
                    <span class="error-feedback" v-if="!$v.province_rate.required">Please enter a value</span>
                    <template v-if="$v.manila_rate.required">
                       <span class="error-feedback" v-if="!$v.province_rate.moneyRegex">Please enter a valid value</span>
                      <template v-if="$v.province_rate.moneyRegex">
                         <span class="error-feedback" v-if="!$v.province_rate.decimal">Please enter a valid value</span>
                      </template>
                    </template>
                   
                  </div>
            </div>
            <div class="form-group">
              <label>Discount %:</label>
              <select class="form-control" 
                v-model.trim="$v.discount.$model"
                :class="{'is-invalid': $v.discount.$error}"
                tabindex="3">
                <option value="" disabled>Select a discount discount</option>
                <option v-for="(disc, index) in discounts" :key="index" :value="disc">{{disc}}</option>
              </select>
              <div v-if="$v.discount.$error">
                <span class="error-feedback" v-if="!$v.discount.required">Please select a discount percent</span>
              </div>
            </div>     
             <div class="form-group">
              <label>Ordered Qty.:</label>
              <input type="text" class="form-control"
                v-model.trim="$v.qty.$model"
                :class="{'is-invalid': $v.qty.$error}"
                tabindex="4"
                placeholder="Enter a quantity">
              <div v-if="$v.qty.$error">
                <span class="error-feedback" v-if="!$v.qty.required">Please enter a quantity</span>
                <template v-if="$v.qty.required">
                   <span class="error-feedback" v-if="!$v.qty.numbersOnly">Please enter a valid value</span>
                </template>
              </div>
            </div>            
          </form>  
        </b-modal>
    </div>
</div>
</template>

<script>
  import { required, minLength, maxLength, helpers,decimal } from 'vuelidate/lib/validators';
  import { HalfCircleSpinner } from 'epic-spinners';
  
  const moneyRegex = helpers.regex('moneyRegex', /^[1-9][0-9.]*$/);
  const numbersOnly = helpers.regex('numbersOnly', /^([1-9])[0-9]*$/);

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
        discount: '',
        qty: '',
        edit: false,
        server_errors: [],
        shipping_rate_id: '',
        discounts: [5,10,15,20,25,30,35,40,45,50,55,60,65,70,75,80,85,90,95,100]
      }
    },
    components: {
      HalfCircleSpinner
    },
    validations() {
      return {
        manila_rate: {
          required,
          moneyRegex,
          decimal
        },
        province_rate: {
          required,
          moneyRegex,
          decimal
        },
        discount: {
          required
        },
        qty: {
          required,
          numbersOnly
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
          this.ok_title = 'Update';
          this.modal_title = 'Edit delivery rate';
          const data = this.shipping_rates.find(x => x.id == 1);
          this.manila_rate = parseFloat(data.manila_rate);
          this.province_rate = parseFloat(data.province_rate);
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
              discount: this.discount,
              quantity: this.qty,
              admin_id: this.admin.id
            })
            .then(response => {
              this.submit = false;
              if (response.data.success) {
                 Swal('Shipping rate has been created', '', 'success')
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
              discount: this.discount,
              quantity: this.qty,
              admin_id: this.admin.id
            })
            .then(response => {
              this.submit = false;
              if (response.data.success) {
                 Swal('Shipping rate has been updated','', 'success')
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
          this.ok_title = 'Create';
          this.modal_title = 'Set delivery fee';
          this.server_errors = [];
          this.$nextTick(() => { this.$v.$reset() });
      },
      cancelShippingRate() {
        this.resetModal();
      },
      focusOnInput() {
        this.$refs.refsManilaInput.focus();
      },
      formatMoney(value) {
         return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(value);
      }
    },
    created() {
      this.getShippingRate();
    }
  }
</script>