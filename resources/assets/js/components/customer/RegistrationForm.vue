<template>
<div class="row mb-5 justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header text-center ifg-card-header">
                Create an Account
            </div>
            <div class="card-body">
                <form method="POST" action="/register" ref="register" @submit.prevent="registerCustomer">
                <fieldset>
                    <legend class="mb-3">Your Account Info</legend>
                    <div class="form-group row">
                        <label for="cEmail" class="col-form-label col-md-4 text-right">Email</label>
                        <div class="col-md-8">
                             <input type="email" class="form-control" id="cEmail" 
                                placeholder="Enter your email"
                                v-model.trim="$v.email.$model"
                                :class="{'is-invalid' : $v.email.$error}"
                                name="email"
                                tabindex="1">
                            <div v-if="$v.email.$error">
                                <span class="error-feedback" v-if="!$v.email.required">Email is required</span>
                                <span class="error-feedback" v-if="!$v.email.custEmail">Please input a valid email</span>
                                <template v-if="$v.email.custEmail">
                                    <span class="error-feedback" v-if="!$v.email.isUniqueEmail">This email is already registered</span>    
                                </template>
                                
                            </div>

                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cPass" class="col-md-4 col-form-label text-right">Password</label>
                        <div class="col-md-8">
                             <div class="input-group">
                                <input :type="visiblePassword ? 'text' : 'password'" class="form-control"
                                    v-model.trim="$v.password.$model"
                                    :class="{'is-invalid' : $v.password.$error}" 
                                    tabindex="2" 
                                    name="password"
                                    placeholder="Enter you password"
                                    id="cPass">
                                <div class="input-group-append">
                                    <button class="btn btn-visible-pass" type="button" @click="visiblePass"><i class="fa" :class="visiblePassword ? 'fa-eye' : 'fa-eye-slash'" v-b-tooltip.hover :title="visiblePassword ? 'Hide password' : 'Show password'"></i></button>
                                </div>
                            </div>
                            <div v-if="$v.password.$error">
                                <span class="error-feedback" v-if="!$v.password.required">Password is required</span>
                                <span class="error-feedback" v-if="!$v.password.lettersAndNumbers">Password must have letters and numbers</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cConfirmPass"  class="col-md-4 col-form-label text-right">Confirm password</label>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input :type="visibleConfirmPassword ? 'text' : 'password'" class="form-control"
                                    v-model.trim="$v.password_confirmation.$model"
                                    :class="{'is-invalid' : $v.password_confirmation.$error}" 
                                    tabindex="3" 
                                    name="password_confirmation"
                                    id="cConfirmPass"
                                    placeholder="Re-enter your password" 
                                  >
                                <div class="input-group-append">
                                    <button class="btn btn-visible-pass" type="button" @click="visibleConfirmPass"><i class="fa":class="visibleConfirmPassword ? 'fa-eye' : 'fa-eye-slash'" v-b-tooltip.hover :title="visibleConfirmPassword ? 'Hide password' : 'Show password'"></i></button>
                                </div>
                            </div>
                            <div v-if="$v.password_confirmation.$error">
                                <span class="error-feedback" v-if="!$v.password_confirmation.sameAsPassword">Password must be identical</span>
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="mt-4">
                    <legend class="mb-3">Your Address</legend>
                    <div class="form-group row">
                        <label for="cFname"  class="col-md-4 col-form-label text-right">First Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control"
                                v-model.trim="$v.first_name.$model"
                                :class= "{'is-invalid':$v.first_name.$error}"
                                name="first_name"
                                tabindex="4"
                                id="cFname"
                                placeholder="Enter your firstname">
                            <div v-if="$v.first_name.$error">
                                <span class="error-feedback" v-if="!$v.first_name.required">First name is required</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cLname"  class="col-md-4 col-form-label text-right">Last Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control"
                                v-model.trim="$v.last_name.$model"
                                :class="{'is-invalid' : $v.last_name.$error}"
                                name="last_name"
                                tabindex="5"
                                id="cLname"
                                placeholder="Enter your lastname">
                            <div v-if="$v.last_name.$error">
                                <span class="error-feedback" v-if="!$v.last_name.required">Last name is required</span>
                            </div>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="addrStreet" class="col-sm-4 col-form-label text-right">Street</label>
                        <div class="col-sm-8">
                            <input type="text" name="street" id="addrStreet" class="form-control"
                                tabindex="6"
                                placeholder="Enter your street"
                                v-model.trim="$v.street.$model"
                                :class="{'is-invalid': $v.street.$error}">
                                <div v-if="$v.street.$error">
                                    <span class="error-feedback" v-if="!$v.street.required">Street is required</span>
                                </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addrBarangay" class="col-sm-4 col-form-label text-right">Barangay</label>
                        <div class="col-sm-8">
                            <input type="text" name="barangay" class="form-control"
                                tabindex="7"
                                v-model.trim="$v.barangay.$model"
                                placeholder="Enter your barangay"
                                :class="{'is-invalid': $v.barangay.$error}">
                            <div v-if="$v.barangay.$error">
                                <span class="error-feedback" v-if="!$v.barangay.required">Barangay is required</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addrMunicipality" class="col-sm-4 col-form-label text-right">Municipality</label>
                        <div class="col-sm-8">
                            <input type="text" name="municipality" class="form-control"
                                tabindex="8"
                                placeholder="Enter your municipality"
                                v-model.trim="$v.municipality.$model"
                                :class="{'is-invalid': $v.municipality.$error}">
                             <div v-if="$v.municipality.$error">
                                <span class="error-feedback" v-if="!$v.municipality.required">Municipality is required</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addrProvince" class="col-sm-4 col-form-label text-right">Province</label>
                        <div class="col-sm-8">
                            <input type="text" name="province" class="form-control"
                                tabindex="9"
                                placeholder="Enter your province"
                                v-model.trim="$v.province.$model"
                                :class="{'is-invalid': $v.province.$error}">
                            <div v-if="$v.province.$error">
                                <span class="error-feedback" v-if="!$v.province.required">Province is required</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addrZipCode" class="col-sm-4 col-form-label text-right">Zip Code</label>
                        <div class="col-sm-8">
                            <input type="text" name="zip_code" id="addrZipCode" class="form-control"
                            placeholder="Enter your zip code"
                            tabindex="10"
                            v-model.trim="$v.zip_code.$model"
                            :class="{'is-invalid': $v.zip_code.$error}">
                            <div v-if="$v.zip_code.$error">
                                <span class="error-feedback" v-if="!$v.zip_code.required">Zip code is required</span>
                                <template v-if="$v.zip_code.required">
                                    <span class="error-feedback" v-if="!$v.zip_code.digitsOnly">Zip code must be digits only</span>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addrMobileNo" class="col-sm-4 col-form-label text-right">Mobile No.</label>
                        <div class="col-sm-8">
                            <input type="text" name="mobile_no" id="addrMobileNo" class="form-control"
                                tabindex="11"
                                placeholder="Enter your mobile number"
                                v-model.trim="$v.mobile_no.$model"
                                :class="{'is-invalid': $v.mobile_no.$error}">
                                <div v-if="$v.mobile_no.$error">
                                    <span class="error-feedback" v-if="!$v.mobile_no.required">Mobile no. is required</span>
                                    <template v-if="$v.mobile_no.required">
                                        <span class="error-feedback" v-if="!$v.mobile_no.isUnique">This mobile no. is already registered</span>
                                    </template>
                                </div>
                        </div>
                    </div>
                </fieldset>
                <input type="hidden" name="_token" :value="csrf">
                <div class="col-md-8 offset-md-4">
                   <div class="clearfix">
                        <button type="submit" class="mt-3 mb-3 btn ifg-btn btn-dark float-left" :disabled="submit"><span v-if="submit"><i class="fa fa-refresh fa-spin"></i>&nbsp;</span>Submit</button>
                        <p class="mb-0 mt-4 mb-3 float-right">Registered customer? <a href="/login">Login here</a></p>
                   </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import { required, minLength, maxLength, sameAs, email, helpers } from 'vuelidate/lib/validators';
    const lettersAndNumbers = helpers.regex('lettersAndNumbers', /([A-Za-z]+[0-9]|[0-9]+[A-Za-z])[A-Za-z0-9]*/);   
    const lettersSpace = helpers.regex('lettersSpace', /^[a-zA-Z\s]*$/);
    // const streetPattern = helpers.regex('streetPattern', /^([A-Za-z0-9])[A-Za-z0-9\s.,'#]*$/);
    
    const custEmail = helpers.regex('email', /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/);

    const digitsOnly = helpers.regex('digitsOnly', /^[0-9]*$/);

    export default {
        props: ['old'],
       	data() {
       		return {
                email: '',
                first_name: '',
                last_name: '',
                street: '',
                province: '',
                municipality: '',
                barangay: '',
                zip_code: '',
                mobile_no: '',
                password: '',
                password_confirmation: '',
                registerClicked: false, 
                server_errors: [],
                has_error: false,
                csrf: document.head.querySelector('meta[name="csrf-token"]').content,
                submit: false,
                visiblePassword: false,
                visibleConfirmPassword: false,
       		}
       	},
        validations() {
            return {
                email: {
                    required,
                    custEmail,
                    maxLength: maxLength(50),
                    async isUniqueEmail(value) {
                        if (value === '') return true
                        const response = await fetch(`/api/customer-check-email/?email=${value}`)
                        return Boolean(await (response.json()) ? false : true)
                    }
                },
                password: {
                    required,
                    minLength: minLength(6),
                    lettersAndNumbers
                },
                password_confirmation: {
                    sameAsPassword: sameAs('password')
                },
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
                mobile_no: { 
                    required,
                    async isUnique(value) {
                        if (value === '') return true
                        const response = await fetch(`/api/customer-check-mobile/?mobile=${value}`)
                        return Boolean(await (response.json()) ? false : true)
                    }
                },
                street: { required },
                province: { required },
                municipality: { required },
                barangay: { required },
                zip_code: { required, digitsOnly },
            }
        },
       	methods: {
       		registerCustomer() {
                this.$v.$touch();
                if (!this.$v.$invalid) {

                    this.submit = true
                    this.$refs.register.submit();

                    document.querySelectorAll('.form-control')
                    .forEach(inputField => {
                        inputField.disabled = true;
                    });

                    this.registerClicked = true;
                }
       		},
            visiblePass() {
                this.visiblePassword = !this.visiblePassword;
            },
            visibleConfirmPass() {
                this.visibleConfirmPassword = !this.visibleConfirmPassword;
            }
        },
        created() {
            if (this.old) {
                this.email = this.old.email;
                this.first_name = this.old.first_name;
                this.last_name = this.old.last_name;
            }
           
        }
    }
</script>
