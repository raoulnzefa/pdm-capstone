<template>
	<div class="card mt-4 mb-4">
		<div class="card-header">
			<h2 class="mb-0">Company Details</h2>
		</div>
		<form @submit.prevent="saveCompanyDetails" method="POST" enctype="multipart/form-data">
		<div class="alert alert-danger" v-if="server_errors.length != 0">
			<ul class="mb-0 rm-bullets">
				<li v-for="(err,index) in server_errors" :key="index">{{ err[0] }}</li>
			</ul>
		</div>
		<div class="card-body pt-5">
			<div class="form-group row">
			    <label for="" class="col-sm-3 col-form-label">Logo:</label>
			    <div class="col-sm-5">
			    	<input type="file" name="logo" class="form-control"
			    		@change="onLogoChange"
			    		tabindex="1"
			    		:class="{'is-invalid': $v.companyDetails.logo.$error}">
			    	<div v-if="$v.companyDetails.logo.$error">
               	<span class="error-feedback" v-if="!$v.companyDetails.logo.required">Logo is required</span>
              	</div>
			    	<img :src="avatar" class="img-fluid img-thumbnail mt-2" width="300" height="250">
			    </div>
		  	</div>
		  	<div class="form-group row">
			    <label for="" class="col-sm-3 col-form-label">Company Image:</label>
			    <div class="col-sm-5">
			    	<input type="file" name="company_image" class="form-control"
			    		@change="onImageChange"
			    		tabindex="2"
			    		:class="{'is-invalid': $v.companyDetails.image.$error}"
			    		accept="image/png, image/jpeg">
			    	<div v-if="$v.companyDetails.image.$error">
               	<span class="error-feedback" v-if="!$v.companyDetails.image.required">Company Image is required</span>
              	</div>
					<img :src="avatar2" class="img-fluid img-thumbnail mt-2" width="300" height="250">
			    </div>
		  	</div>
		  	<div class="form-group row">
			    <label for="" class="col-sm-3 col-form-label">Company Name:</label>
			    <div class="col-sm-5">
			      <input type="text" class="form-control" id="" 
			      	v-model.trim="$v.companyDetails.name.$model"
			      	tabindex="3"
			      	:class="{'is-invalid': $v.companyDetails.name.$error}">
			      <div v-if="$v.companyDetails.name.$error">
               	<span class="error-feedback" v-if="!$v.companyDetails.name.required">Name is required</span>
              	</div>
			    </div>
		  	</div>
		  	<div class="form-group row">
			    <label for="" class="col-sm-3 col-form-label">Address:</label>
			    <div class="col-sm-5">
			      <input type="text" class="form-control" id=""
			      	tabindex="4"
			      	v-model.trim="$v.companyDetails.address.$model"
			      	:class="{'is-invalid': $v.companyDetails.address.$error}">
			      <div v-if="$v.companyDetails.address.$error">
               	<span class="error-feedback" v-if="!$v.companyDetails.address.required">Address is required</span>
              	</div>
			    </div>
		  	</div>
		  	<div class="form-group row">
			    <label for="" class="col-sm-3 col-form-label">Contact Number:</label>
			    <div class="col-sm-5">
			      <input type="text" class="form-control" id=""
			      	tabindex="4"
			      	v-model.trim="$v.companyDetails.contactNumber.$model"
			      	:class="{'is-invalid': $v.companyDetails.contactNumber.$error}">
			      <div v-if="$v.companyDetails.contactNumber.$error">
               	<span class="error-feedback" v-if="!$v.companyDetails.contactNumber.required">Contact Number is required</span>
              	</div>
			    </div>
		  	</div>
		  	<div class="form-group row">
			    <label for="" class="col-sm-3 col-form-label">TIN Number:</label>
			    <div class="col-sm-5">
			      <input type="text" class="form-control" id=""
			      	tabindex="6"
			      	v-model.trim="$v.companyDetails.tinNumber.$model"
			      	:class="{'is-invalid': $v.companyDetails.tinNumber.$error}">
			      <div v-if="$v.companyDetails.tinNumber.$error">
               	<span class="error-feedback" v-if="!$v.companyDetails.tinNumber.required">TIN Number is required</span>
               	<template v-if="$v.companyDetails.tinNumber.required">
               		<span class="error-feedback" v-if="!$v.companyDetails.tinNumber.numbersOnly">Please enter a numeric value only</span>
               	</template>
              	</div>
			    </div>
		  	</div>
		  	<div class="form-group row">
			   <label for="" class="col-sm-3 col-form-label">About Us:</label>
			   <div class="col-sm-9">
			   	<editor
				       api-key="bu1i2eie2y8vnn92dwnqamlwqn0up5l7tpeh8eop0vyyp0s8"
				       v-model="$v.companyDetails.aboutUs.$model"
				       :init="{
				         height: 500,
				         menubar: false,
				         plugins: [
				           'advlist autolink lists link image charmap print preview anchor',
				           'searchreplace visualblocks code fullscreen',
				           'insertdatetime media table paste code help wordcount'
				         ],
				         toolbar:
				           'undo redo | formatselect | bold italic backcolor | \
				           alignleft aligncenter alignright alignjustify | \
				           bullist numlist outdent indent | removeformat | help'
				       }"
				     />
			   	<div v-if="$v.companyDetails.aboutUs.$error">
               	<span class="error-feedback" v-if="!$v.companyDetails.aboutUs.required">About Us is required</span>
              	</div>
			   </div>
		  	</div>
		  	<div class="form-group row">
			   <label for="" class="col-sm-3 col-form-label">Terms and Conditions:</label>
			   <div class="col-sm-9">
			   	<editor
				       api-key="bu1i2eie2y8vnn92dwnqamlwqn0up5l7tpeh8eop0vyyp0s8"
				       v-model="$v.companyDetails.termsAndConditions.$model"
				       :init="{
				         height: 500,
				         menubar: false,
				         plugins: [
				           'advlist autolink lists link image charmap print preview anchor',
				           'searchreplace visualblocks code fullscreen',
				           'insertdatetime media table paste code help wordcount'
				         ],
				         toolbar:
				           'undo redo | formatselect | bold italic backcolor | \
				           alignleft aligncenter alignright alignjustify | \
				           bullist numlist outdent indent | removeformat | help'
				       }"
				     />
			   	<div v-if="$v.companyDetails.termsAndConditions.$error">
               	<span class="error-feedback" v-if="!$v.companyDetails.termsAndConditions.required">Terms and Conditions is required</span>
              	</div>
			   </div>
		  	</div>
		  	<div class="form-group row">
			   <label for="" class="col-sm-3 col-form-label">Return Policy:</label>
			   <div class="col-sm-9">
			   	<editor
				       api-key="bu1i2eie2y8vnn92dwnqamlwqn0up5l7tpeh8eop0vyyp0s8"
				       v-model="$v.companyDetails.returnPolicy.$model"
				       :init="{
				         height: 500,
				         menubar: false,
				         plugins: [
				           'advlist autolink lists link image charmap print preview anchor',
				           'searchreplace visualblocks code fullscreen',
				           'insertdatetime media table paste code help wordcount'
				         ],
				         toolbar:
				           'undo redo | formatselect | bold italic backcolor | \
				           alignleft aligncenter alignright alignjustify | \
				           bullist numlist outdent indent | removeformat | help'
				       }"
				     />
			   	<div v-if="$v.companyDetails.returnPolicy.$error">
               	<span class="error-feedback" v-if="!$v.companyDetails.returnPolicy.required">Return Policy is required</span>
              	</div>
			   </div>
		  	</div>
		  	<div class="form-group row">
			    <label for="" class="col-sm-3 col-form-label">Number of reserved days for Store pickup:</label>
			    <div class="col-sm-3">
			      <input type="text" class="form-control" id=""
			      	tabindex="10"
			      	v-model.trim="$v.companyDetails.reserveDays.$model"
			      	:class="{'is-invalid': $v.companyDetails.reserveDays.$error}">
			      <div v-if="$v.companyDetails.reserveDays.$error">
               	<span class="error-feedback" v-if="!$v.companyDetails.reserveDays.required">Number of reserved days is required</span>
               	<template v-if="$v.companyDetails.reserveDays.required">
               		<span class="error-feedback" v-if="!$v.companyDetails.reserveDays.numbersOnly">Please enter a numeric value only</span>
               	</template>
              	</div>
			    </div>
		  	</div>
		  	<div class="form-group row">
			    <label for="" class="col-sm-3 col-form-label">Number of processing days:</label>
			    <div class="col-sm-3">
			      <input type="text" class="form-control" id=""
			      	tabindex="11"
			      	v-model.trim="$v.companyDetails.numOfDeliveryDays.$model"
			      	:class="{'is-invalid': $v.companyDetails.numOfDeliveryDays.$error}">
			      <div v-if="$v.companyDetails.numOfDeliveryDays.$error">
               	<span class="error-feedback" v-if="!$v.companyDetails.numOfDeliveryDays.required">Number of processing days is required</span>
               	<template v-if="$v.companyDetails.numOfDeliveryDays.required">
               		<span class="error-feedback" v-if="!$v.companyDetails.numOfDeliveryDays.numbersOnly">Please enter a numeric value only</span>
               	</template>
              	</div>
			    </div>
		  	</div>
		  	<div class="form-group row">
			    <label for="" class="col-sm-3 col-form-label">Number of due payment days:</label>
			    <div class="col-sm-3">
			      <input type="text" class="form-control" id=""
			      	tabindex="12"
			      	v-model.trim="$v.companyDetails.numOfDuePaymentDays.$model"
			      	:class="{'is-invalid': $v.companyDetails.numOfDuePaymentDays.$error}">
			      <div v-if="$v.companyDetails.numOfDuePaymentDays.$error">
               	<span class="error-feedback" v-if="!$v.companyDetails.numOfDuePaymentDays.required">Number of due payment days is required</span>
               	<template v-if="$v.companyDetails.numOfDuePaymentDays.required">
               		<span class="error-feedback" v-if="!$v.companyDetails.numOfDuePaymentDays.numbersOnly">Please enter a numeric value only</span>
               	</template>
              	</div>
			    </div>
		  	</div>
		  	<div class="form-group row">
			    <label for="" class="col-sm-3 col-form-label">Number of days for follow up email:</label>
			    <div class="col-sm-3">
			      <input type="text" class="form-control" id=""
			      	tabindex="13"
			      	v-model.trim="$v.companyDetails.numOfFollowUpEmailDays.$model"
			      	:class="{'is-invalid': $v.companyDetails.numOfFollowUpEmailDays.$error}">
			      <div v-if="$v.companyDetails.numOfFollowUpEmailDays.$error">
               	<span class="error-feedback" v-if="!$v.companyDetails.numOfFollowUpEmailDays.required">Number of days for follow up is required</span>
               	<template v-if="$v.companyDetails.numOfFollowUpEmailDays.required">
               		<span class="error-feedback" v-if="!$v.companyDetails.numOfFollowUpEmailDays.numbersOnly">Please enter a numeric value only</span>
               	</template>
              	</div>
			    </div>
		  	</div>
		</div>
		<div class="card-footer">
			<button type="submit" class="btn btn-primary" :disabled="submitted">Save details</button>
		</div>
		</form>
	</div>
</template>

<script>
	import { required, minLength, maxLength, helpers } from 'vuelidate/lib/validators';
   import { HalfCircleSpinner } from 'epic-spinners';
   import Editor from '@tinymce/tinymce-vue';

   const numbersOnly = helpers.regex('numbersOnly', /^([1-9])[0-9]*$/);
   const fileSizeValidation = (value, vm) =>  {
	  	if (!value) {
	   	return true;
	  	}
	  	let file = value;
	  	return (file.size < 65338);
	};

	const isValidFileSize = (options = {}) => {
	  return helpers.withParams(options, value => {
	    if (!value) {
	      return true
	    }
	    const fileSizeinKb = value.size / 1024
	    const size = Math.round(fileSizeinKb * 100) / 100 // convert up to 2 decimal place
	    return size <= options.maxFileSizeInB / 1024
	  })
	}
	export default {
		props: ['admin'],
		data() {
			return {
				avatar: '/images/default-thumbnail.jpg',
				avatar2: '/images/default-thumbnail.jpg',
				companyDetails: {
					logo: '',
					image: '',
					name: '',
					address: '',
					contactNumber: '',
					tinNumber: '',
					aboutUs: '',
					termsAndConditions: '',
					returnPolicy: '',
					reserveDays: '',
					numOfDeliveryDays: '',
					numOfDuePaymentDays: '',
					numOfFollowUpEmailDays: ''
				},
				compandyDetailsId: '',
				hasCompanyDetails: false,
				server_errors: [],
				submitted: false,
				placeHolderTxt: '',
					
				
			}
		},
		components: {
			'editor': Editor,
			HalfCircleSpinner
		},
		validations() {
			if (!this.hasCompanyDetails) {
				return {
				companyDetails: {
						logo: { required },
						image: { required },
						name: { required },
						address: { required },
						contactNumber: { required },
						tinNumber: { required, numbersOnly },
						aboutUs: { required },
						termsAndConditions: { required },
						returnPolicy: { required },
						reserveDays: { required, numbersOnly }, 
						numOfDeliveryDays: { required, numbersOnly },
						numOfDuePaymentDays: { required, numbersOnly },
						numOfFollowUpEmailDays: { required, numbersOnly },
					}
				}
			} else {
				return {
					companyDetails: {
						logo: { },
						image: { },
						name: { required },
						address: { required },
						contactNumber: { required },
						tinNumber: { required, numbersOnly },
						aboutUs: { required },
						termsAndConditions: { required },
						returnPolicy: { required },
						reserveDays: { required, numbersOnly },
						numOfDeliveryDays: { required, numbersOnly },
						numOfDuePaymentDays: { required, numbersOnly },
						numOfFollowUpEmailDays: { required, numbersOnly },
					}
				}
			}
		},
		methods: {
			onLogoChange(e) {
				let files = e.target.files || e.dataTransfer.files
				if (!files.length)
					return;
				
				this.companyDetails.logo = files[0]
				this.createLogo(this.companyDetails.logo)
				//e.target.value = '';
			},
			createLogo(file) {
				let reader = new FileReader()
	
				reader.onload = (e)	=> {
					this.avatar = e.target.result
				}
				reader.readAsDataURL(file)
			},
			onImageChange(e) {
				let files = e.target.files || e.dataTransfer.files
				if (!files.length)
					return;
				
				this.companyDetails.image = files[0]
				this.createImage(this.companyDetails.image)
				//e.target.value = '';
			},
			createImage(file) {
				let reader = new FileReader()
	
				reader.onload = (e)	=> {
					this.avatar2 = e.target.result
				}
				reader.readAsDataURL(file)
			},
			getCompanyDetails() {
				this.hasCompanyDetails = false;

				axios.get('/api/company-details/get')
				.then(response => {
					const companyData = response.data.company_details;
					if (Object.keys(companyData).length > 0) {
						this.hasCompanyDetails = true;
						this.avatar = companyData.logo_url;
						this.avatar2 = companyData.image_url;
						this.companyDetails.name = companyData.name;
						this.companyDetails.address = companyData.address;
						this.companyDetails.contactNumber = companyData.contact_number;
						this.companyDetails.tinNumber = companyData.tin_number;
						this.companyDetails.aboutUs = companyData.about_us;
						this.companyDetails.termsAndConditions = companyData.terms_and_conditions;
						this.companyDetails.returnPolicy = companyData.return_policy;
						this.companyDetails.reserveDays = companyData.reserved_days;
						this.companyDetails.numOfDeliveryDays = companyData.delivery_days;
						this.companyDetails.numOfDuePaymentDays = companyData.due_payment_days;
						this.companyDetails.numOfFollowUpEmailDays = companyData.follow_up_days;
						this.compandyDetailsId = companyData.id;
					}
				})
				.catch(error => {
					this.hasCompanyDetails = false;
					console.log(error.response);
				});
			},
			saveCompanyDetails(e) {
				e.preventDefault();

				this.$v.companyDetails.$touch();

				if (!this.$v.companyDetails.$invalid) {
					// set form data
					const form = new FormData();

					form.append('logo', this.companyDetails.logo);
					form.append('company_image', this.companyDetails.image);
					form.append('name', this.companyDetails.name);
					form.append('address', this.companyDetails.address);
					form.append('contact_number', this.companyDetails.contactNumber);
					form.append('tin_number', this.companyDetails.tinNumber);
					form.append('about_us', this.companyDetails.aboutUs);
					form.append('terms_and_conditions', this.companyDetails.termsAndConditions);
					form.append('return_policy', this.companyDetails.returnPolicy);
					form.append('reserved_days', this.companyDetails.reserveDays);
					form.append('num_processing_days', this.companyDetails.numOfDeliveryDays);
					form.append('num_due_payment_days', this.companyDetails.numOfDuePaymentDays);
					form.append('num_follow_up_email', this.companyDetails.numOfFollowUpEmailDays);
					form.append('admin_id', this.admin.id);

					this.submitted = true;

					if (!this.hasCompanyDetails) {
						axios.post('/api/company-details/save', form)
						.then(response => {
							this.submitted = false;
							if (response.data.success) {
								Swal('Company details saved.', '', 'success').
								then(result => {
									if (result) {
										window.location.reload();
									}
								})
							}
						})
						.catch(error => { 
							this.submitted = false;
							if(error.response.status == 422) {
								this.server_errors = error.response.data.errors;
							}
						});
					} else {
						axios.post(`/api/company-details/update/${this.compandyDetailsId}`, form)
						.then(response => {
							this.submitted = false;
							if (response.data.success) {
								Swal('Company details saved.', '', 'success')
								.then(result => {
									if (result) {
										window.location.reload();
									}
								})
							}
						})
						.catch(error => { 
							this.submitted = false;
							if(error.response.status == 422) {
								this.server_errors = error.response.data.errors;
							}
						});
					}
				}
			}
		},
		mounted() {
			this.getCompanyDetails();
		}
	}
</script>
