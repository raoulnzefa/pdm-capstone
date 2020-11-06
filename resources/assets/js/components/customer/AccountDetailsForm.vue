<template>
<div class="card">
    <form @submit.prevent="updateAccountDetails">
    <div class="card-header">
        <h3 class="mb-0">Account Details</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" v-if="server_errors.length != 0">
                    <ul class="rm-bullets">
                        <li v-for="err in server_errors">{{ err[0] }}</li>
                    </ul>
                </div>
                <br>
                <div class="form-group row">
                    <label for="firstname" class="col-sm-4 col-form-label text-right">First Name:</label>
                    <div class="col-sm-6">
                        <input type="text" name="first_name" class="form-control" :class="{'is-invalid': $v.first_name.$error}" id="firstname"tabindex="2" v-model.trim="$v.first_name.$model">
                        <div v-if="$v.first_name.$error">
                            <span class="error-feedback" v-if="!$v.first_name.required">First name is required</span>
                            <span class="error-feedback" v-if="!$v.first_name.maxLength">First name must have at most {{ $v.first_name.$params.maxLength.max }} letters</span>
                            <span class="error-feedback d-block" v-if="!$v.first_name.lettersSpace">Please input a valid value</span>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="lastname" class="col-sm-4 col-form-label text-right">Last Name:</label>
                    <div class="col-sm-6">
                        <input type="text" name="last_name" class="form-control" :class="{'is-invalid': $v.last_name.$error}" id="lastname" tabindex="3" v-model.trim="$v.last_name.$model">
                        <div v-if="$v.last_name.$error">
                            <span class="error-feedback" v-if="!$v.last_name.required">Last name is required</span>
                            <span class="error-feedback" v-if="!$v.last_name.maxLength">Last name must have at most {{ $v.last_name.$params.maxLength.max  }} letters</span>
                             <span class="error-feedback d-block" v-if="!$v.last_name.lettersSpace">Please input a valid value</span>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="email" class="col-sm-4 col-form-label text-right">Email:</label>
                    <div class="col-sm-6">
                        <input type="email" name="email" class="form-control" id="email" v-model="email" readonly="true">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="cust_new_pass" class="col-sm-4 col-form-label text-right">New Password:</label>
                    <div class="col-sm-6">
                        <input type="password" name="new_passcust_new_pass" class="form-control" id="cust_new_pass"
                        v-model.trim="$v.password.$model"
                        :class="{'is-invalid': $v.password.$error}">
                        <div v-if="$v.password.$error">
                            <span class="error-feedback" v-if="!$v.password.minLength">Password must have at least {{ $v.password.$params.minLength.min }} characters.</span>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="cust_confirm_new_pass" class="col-sm-4 col-form-label text-right">Confirm New Password:</label>
                    <div class="col-sm-6">
                        <input type="password" name="confirm_new_pass" class="form-control" id="cust_confirm_new_pass"
                        v-model.trim="$v.password_confirmation.$model"
                        :class="{'is-invalid': $v.password_confirmation.$error}">
                        <div v-if="$v.password_confirmation.$error">
                            <span class="error-feedback" v-if="!$v.password_confirmation.sameAsPassword">Password must be identical</span>
                        </div>
                    </div>
                </div>
              
            </div>
        </div>
    </div><!-- card-body-->
    <div class="card-footer clearfix">
        <button type="submit" class="btn btn-primary float-right" :disabled="AcUpClicked">Update Details</button>
    </div>
    </form>
</div>
</template>

<script>
    import { required, minLength, maxLength, sameAs, email, helpers } from 'vuelidate/lib/validators';
    const companyPattern = helpers.regex('companyPattern', /([A-Za-z])[A-Za-z0-9\s.]*$/);
    const mobileNumber = helpers.regex('mobileNumber', /^(09)\d{9}$/);
    const lettersSpace = helpers.regex('lettersSpace', /^[a-zA-Z\s]*$/);

    export default {
        props: ['cust'],
       	data() {
       		return {
                email: this.cust.email,
                first_name: this.cust.first_name,
                last_name: this.cust.last_name,
                password: '',
                password_confirmation: '',
                current_password: '',
                AcUpClicked: false, 
                server_errors: [],
                has_error: false,
       		}
       	},
        validations() {
            return {
                first_name: {
                    required,
                    maxLength: maxLength(20),
                    lettersSpace
                },
                last_name: {
                    required,
                    maxLength: maxLength(20),
                    lettersSpace
                },
                password: {
                    minLength: minLength(6),
                },
                password_confirmation: {
                    sameAsPassword: sameAs('password'),
                }
            }
        },
       	methods: {
       		updateAccountDetails() {
                this.$v.$touch();

   				if (!this.$v.$invalid)
   				{
                    this.AcUpClicked = true	

                    let form = new FormData()

                
                    form.append('first_name', this.first_name)
                    form.append('last_name', this.last_name)
                
                    form.append('password', this.password)
                    form.append('password_confirmation', this.password_confirmation)
                    

                    axios.post('/api/customer/'+this.cust.id, form)
                    .then(response => {
                        let res = response.data
                        if (res.success)
                        {
                            this.$bus.$emit('update-name', true)
                            Swal('Account details has been updated','','success')
                            .then(okay => {
                                if (okay.value)
                                {
                                    this.AcUpClicked = false
                                    this.password = ''
                                    this.password_confirmation = ''
                                    window.location.reload();
                                }
                            })
                        }
                        else
                        {
                            Swal('Update Details', res.message, 'warning')
                        }
                    })
                    .catch(error => {
                        if (error.response.status == 422)
                        {
                            this.server_errors = error.response.data.errors
                            this.has_error = true
                            this.AcUpClicked = false
                        }
                    })
   				}
       			
       		}
       	},
        created() {
           
        }
    }
</script>
