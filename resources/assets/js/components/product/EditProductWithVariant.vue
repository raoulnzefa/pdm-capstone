<template>
	<div>
		<button class="btn btn-primary btn-sm float-left mr-1" @click="editProductCatalog"><i class="fas fa-edit"></i> Edit</button>

		<b-modal 
			title="Edit product"
			ref="refsProductCatalogModal"
			size="lg"
			no-close-on-esc
			no-close-on-backdrop
			hide-header-close
			ok-title="Update"
			@ok="submitProductCatalog"
			@cancel="cancelProductCatalog"
			@shown="focusOnProdName"
			:ok-disabled="isBtnClicked"
			:cancel-disabled="isBtnClicked">
			<form @submit.stop.prevent="saveProductCatalog">
				<div class="alert alert-danger" v-if="server_errors.length != 0">
					<ul class="mb-0 rm-bullets">
						<li v-for="(err,index) in server_errors" :key="index">{{ err[0] }}</li>
					</ul>
				</div>
				<div class="form-group row">
			   	<label class="col-sm-3 col-form-label">Product name:</label>
			   	<div class="col-sm-9">
			   		<input type="text" class="form-control" 
			   			placeholder="Enter product name"
			   			tabindex="1"
			   			v-model.trim="$v.product_name.$model"
			   			:class="{'is-invalid': $v.product_name.$error}"
			   			ref="catalogProductName">
			   		<div v-if="$v.product_name.$error">
	                	<span class="error-feedback" v-if="!$v.product_name.required">Product name is required</span>
	                </div>
			   	</div>
			  	</div>
			  	<div class="form-group row">
			   	<label class="col-sm-3 col-form-label">Category:</label>
			   	<div class="col-sm-9">
			   		<select class="form-control"
			   			tabindex="2"
			   			v-model.trim="$v.category.$model"
			   			:class="{'is-invalid': $v.category.$error }">
			   			<option value="" selected disabled>Select category</option>
			   			<option v-for="(category, index) in categories" :value="category.id">{{ category.name }}</option>
			   		</select>
			   		<div v-if="$v.category.$error">
							<span class="error-feedback" v-if="!$v.category.required">Category is required</span>	
						</div>
			   	</div>
			  	</div>
			  	<div class="form-group row">
			   	<label class="col-sm-3 col-form-label">Description:</label>
			   	<div class="col-sm-9">
			   		<textarea class="form-control" rows="4"
			   			placeholder="Enter description"
			   			tabindex="3"
			   			v-model.trim="$v.description.$model"
			   			:class="{'is-invalid': $v.description.$error }"
			   			></textarea>
			   		<div v-if="$v.description.$error">
							<span class="error-feedback" v-if="!$v.description.required">Description is required</span>	
						</div>
			   	</div>
			  	</div>

			   <div class="form-group row">
			   	<label class="col-sm-3 col-form-label">Status:</label>
			   	<div class="col-sm-9">
			   	<select class="form-control" tabindex="4" v-model="status">
			   			<option value="1">Enable</option>
			   			<option value="0">Disable</option>
			   		</select>
			   	</div>
			  	</div>
			  	<div class="form-group row">
					<label class="col-sm-3 col-form-label">Product image:</label>
					<div class="col-sm-4">
						<input type="file" name="product_image" ref="image" @change="onProductImageChange" hidden tabindex="5">
						<a href="javascript:void(0)" @click="openDialog">
							<img :src="avatar" class="img-fluid img-thumbnail">
						</a>
					</div>
				</div>
			</form>
			<template slot="modal-ok">
            <i class="fas fa-sync-alt fa-spin" v-if="isBtnClicked"></i> Update
         </template>
	  	</b-modal>
	</div>
</template>
<script>
	import { required, minLength, maxLength, helpers } from 'vuelidate/lib/validators';

	export default {
		props: ['admin', 'product'],
		data() {
			return {
				product_name: '',
				category: '',
				description: '',
				status: '',
				image: '',
				avatar: '/images/default-thumbnail.jpg',
				isBtnClicked: false,
				prodNumber: '',
				server_errors: [],
				categories: [],
			}
		},
		validations() {
			return {
				product_name: { required },
				category: { required },
				description: { required },
			}
		},
		methods: {
			openDialog() {
				this.$refs.image.click()
			},
			onProductImageChange(e) {
				let files = e.target.files || e.dataTransfer.files
				if (!files.length)
					return;
				// this.creteImage(files[0])
				this.image = files[0]
				this.createImage(this.image)
			},
			createImage(file) {
				let reader = new FileReader()

				let vm = this
				reader.onload = (e)	=> {
					vm.avatar = e.target.result
				}
				reader.readAsDataURL(file)
			},
			getCategory() {
				axios.get('/api/category/list')
				.then((response) => {
					this.categories = response.data;
				})
				.catch((error) => {
					console.log(error.response);
				});
			},
			editProductCatalog() {
				this.prodNumber = this.product.number;
				this.product_name = this.product.product_name;
				this.category = this.product.category_id;
				this.description = this.product.product_description;
				this.status = this.product.product_status;
				this.avatar = this.product.product_image_url;
				this.getCategory();
				this.$refs.refsProductCatalogModal.show();
			},
			cancelProductCatalog() {
				this.product_name = '';
				this.description = '';
				this.category = '';
				this.status = '';
				this.prodNumber = '';
				this.isBtnClicked = false;
				this.server_errors = [];
				this.categories = [];
				this.$nextTick(() => { this.$v.$reset() });
			},
			focusOnProdName() {
				this.$refs.catalogProductName.focus();
			},
			saveProductCatalog() {
				this.$v.$touch();

				if (!this.$v.$invalid) {
					this.isBtnClicked  = true;

					let form = new FormData();
					form.append('admin_id', this.admin.id);
					form.append('product_name', this.product_name);
					form.append('product_description', this.description);
					form.append('category', this.category);
					form.append('product_status', this.status);
					
					if (this.image) {
						form.append('product_image', this.image);
					}

					axios.post('/api/product/catalog/'+this.prodNumber, form)
					.then(response => {
						if (response.data.success) {
							Swal('Product has been updated.', '', 'success');
							this.$refs.refsProductCatalogModal.hide();
							this.$nextTick(() => { this.$v.$reset() });
							this.$bus.$emit('refreshTable', true);
						}
						this.isBtnClicked = false;
					})
					.catch(error => {
						this.isBtnClicked = false;
						if(error.response.status == 422) {
							this.server_errors = error.response.data.errors;
						}
					});
				}
			},
			submitProductCatalog(e) {
				e.preventDefault();
				this.saveProductCatalog();
			}
		}
	}
</script>