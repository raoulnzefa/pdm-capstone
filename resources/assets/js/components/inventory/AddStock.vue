<template>
	<div>
		<button class="btn btn-primary btn-sm" v-b-tooltip.hover title="Add stock" @click="showStockModal"><i class="fas fa-plus"></i> Stock</button>
		<!-- Modal Component -->
		    <b-modal id="stockModal"
		             ref="stockModal"
		             title="Add stock"
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
		      		<label>Product name:</label>
		      		<input type="text" name="" readonly class="form-control" :value="prod_name">
		      	</div>
		      	<div class="form-group">
		      		<label>Current stock:</label>
		      		<input type="text" name="" readonly class="form-control" :value="inventory.inventory_stock">
		      	</div>
		        <b-form-group 
                          label="Quantity to add:"
                          label-for="">
	                <b-form-input id=""
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
	props: ['admin', 'inventory'],
	data() {
		return {
			isBtnClicked: false,
			quantity: 1,
			prod_name: this.inventory.product.product_name,
			addStockBtn: false,
			modal_title: 'Add stock'
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
		},
		cancelAddStock() {
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
				axios.put('/api/inventory/add-stock/'+this.inventory.number, {
					inventory_stock: this.quantity,
					admin_id: this.admin.id
				})
				.then((response) => {
					this.addStockBtn = false;
					if (response.data.success)
					{
						Swal('New stock has been added','', 'success')
						.then((okay) => {
							if (okay)
							{
								this.cancelAddStock();
								this.$refs.stockModal.hide();
								this.$bus.$emit('refreshInventoryTable', true);
								this.$bus.$emit('update-inventory-badge', true);
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
		focusOnQuantity()
		{
			this.$refs.addQty.focus();
		},
	}

}
</script>