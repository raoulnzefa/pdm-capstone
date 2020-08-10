<template>
	<div>
		<button class="btn btn-primary btn-sm" @click="showStockModal"><i class="fas fa-plus"></i> Stock</button>
		<!-- Modal Component -->
		    <b-modal id="stockModal"
		             ref="stockModal"
		             :title="modal_title"
		             no-close-on-backdrop
		             no-close-on-esc
		             centered
		             no-fade
		             ok-title="Save"
		             :ok-disabled="addStockBtn"
		             :cancel-disabled="addStockBtn"
		             @ok="submitAddStock"
		             @cancel="cancelAddStock"
		             @hidden="cancelAddStock"
		             @shown="focusOnQuantity"
		             >
		      <form @submit.prevent="saveAddStock">
		      	<div class="form-group">
		      		<label>Current stock:</label>
		      		<input type="text" name="" readonly class="form-control" :value="product.product_stock">
		      	</div>
		        <b-form-group id=""
                          label="Quantity to add:"
                          label-for="qtyToAdd">
	                <b-form-input id="qtyToAdd"
	                              type="text"
	                              ref="addQty"
	                              v-model.trim="$v.quantity.$model"
	                              :class="{'is-invalid':$v.quantity.$error}">
	                </b-form-input>
	                <div v-if="$v.quantity.$error">
	                	<span class="error-feedback" v-if="!$v.quantity.required">Quantity is required</span>
	                	<template v-if="$v.quantity.required">
	                		<span class="error-feedback" v-if="!$v.quantity.numbersOnly">Please enter a valid value</span>
	                	</template>	
	                </div>
                </b-form-group>
		      </form>
		      <div slot="modal-ok">
		      	<span v-if="addStockBtn"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>Save
		      </div>
		    </b-modal>
	</div>
</template>

<script>
import { required, minLength, maxLength, maxValue, minValue, helpers, numeric } from 'vuelidate/lib/validators';
const numbersOnly = helpers.regex('numbersOnly', /^([1-9])[0-9]*$/);

export default {
	props: ['admin', 'product'],
	data() {
		return {
			isBtnClicked: false,
			quantity: 1,
			prod_name: this.product.product_name,
			addStockBtn: false,
			modal_title: 'Add stock '+this.product.product_name
		}
	},
	validations() {
		return {
			quantity: { 
				required,
				numbersOnly
			}
		}
	},
	methods: {
		showStockModal() {
			this.$refs.stockModal.show();
			this.adjust_stock = false;
		},
		cancelAddStock() {
			this.adjust_stock = false
			this.addStockBtn = false;
			this.quantity = 1;
			this.server_error = [];
			this.$nextTick(() => { this.$v.$reset() });
		},
		saveAddStock() {
			this.$v.$touch();

			if (!this.$v.$invalid)
			{
				this.addStockBtn = true;
				axios.put('/api/product/add-stock/'+this.product.number, {
					quantity: this.quantity,
					admin_id: this.admin.id
				})
				.then((response) => {
					this.addStockBtn = false;
					if (response.data.success)
					{
						Swal('Quantity has been added','', 'success')
						.then((okay) => {
							if (okay)
							{
								this.$refs.stockModal.hide();
								this.$bus.$emit('refreshTable', true)
							}
						});
					}
				})
				.catch((error) => {
					this.addStockBtn = false;
					if (error.response.status == 422)
					{
						this.server_error = error.response.data.errors;
					}
				});
			}
		},
		submitAddStock(evt)
		{
			evt.preventDefault();
			this.saveAddStock();
		},
		focusOnQuantity(e)
		{
			if (!this.adjust_stock)
			{
				this.$refs.addQty.focus();
			}
			else
			{
				this.$refs.adjustQty.focus();
			}
			
		},
	}

}
</script>