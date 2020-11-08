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
                        <label for="cEmail" class="col-form-label col-md-4 ifg-label">Email:</label>
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
                        <label for="cPass" class="col-md-4 col-form-label ifg-label">Password:</label>
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
                        <label for="cConfirmPass"  class="col-md-4 col-form-label ifg-label">Confirm password:</label>
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
                        <label for="cFname"  class="col-md-4 col-form-label ifg-label">Firstname:</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control"
                                v-model.trim="$v.first_name.$model"
                                :class= "{'is-invalid':$v.first_name.$error}"
                                name="first_name"
                                tabindex="4"
                                id="cFname"
                                placeholder="Enter your firstname">
                            <div v-if="$v.first_name.$error">
                                <span class="error-feedback" v-if="!$v.first_name.required">Firstname is required</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cLname"  class="col-md-4 col-form-label ifg-label">Lastname:</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control"
                                v-model.trim="$v.last_name.$model"
                                :class="{'is-invalid' : $v.last_name.$error}"
                                name="last_name"
                                tabindex="5"
                                id="cLname"
                                placeholder="Enter your lastname">
                            <div v-if="$v.last_name.$error">
                                <span class="error-feedback" v-if="!$v.last_name.required">Lastname is required</span>
                            </div>
                        </div>
                    </div>
                     <div class="form-group row">
                        <label for="addrStreet" class="col-sm-4 col-form-label ifg-label">Street:</label>
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
                        <label for="addrProvince" class="col-sm-4 col-form-label ifg-label">Province:</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="addrProvince"
                                tabindex="7"
                                v-model.trim="$v.province_id.$model"
                                :class="{'is-invalid': $v.province_id.$error}"
                                @change="getMunicipalities"
                                name="province"> 
                                <option value="">Select a province</option>
                                <option v-for="(prov, index) in provinceList" :key="index" :value="prov.province_id">{{prov.name}}</option>
                            </select>
                            <div v-if="$v.province_id.$error">
                                <span class="error-feedback" v-if="!$v.province_id.required">Province is required</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addrMunicipality" class="col-sm-4 col-form-label ifg-label">Municipality:</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="addrMunicipality"
                                tabindex="8"
                                v-model.trim="$v.municipality_id.$model"
                                :class="{'is-invalid': $v.municipality_id.$error}"
                                name="municipality"
                                @change="getBarangays">
                                <option value="" v-if="!municipality_list.length">Select a province first</option>
                                <option value="" v-else>Select a municipality</option>
                                <option v-for="(muni, index) in municipality_list" :key="index" :value="muni.city_id">{{muni.name}}</option>
                            </select>
                            <div v-if="$v.municipality_id.$error">
                                <span class="error-feedback" v-if="!$v.municipality_id.required">Municipality is required</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addrBarangay" class="col-sm-4 col-form-label ifg-label">Barangay:</label>
                        <div class="col-sm-8">
                             <select class="form-control" id="addrBarangay"
                                tabindex="9"
                                v-model.trim="$v.barangay_id.$model"
                                :class="{'is-invalid': $v.barangay_id.$error}"
                                name="barangay"
                                @change="getBarangayName">
                                <option value="" v-if="!barangay_list.length">Select a municipality first</option>
                                <option value="" v-else>Select a barangay</option>
                                <option v-for="(barang, index) in barangay_list" :key="index" :value="barang.id">{{barang.name}}</option>
                            </select>
                            <div v-if="$v.barangay_id.$error">
                                <span class="error-feedback" v-if="!$v.barangay_id.required">Barangay is required</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addrZipCode" class="col-sm-4 col-form-label ifg-label">Zip Code:</label>
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
                        <label for="addrMobileNo" class="col-sm-4 col-form-label ifg-label">Mobile no.:</label>
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
                <input type="hidden" name="barangay_name" :value="barangay_name">
                <input type="hidden" name="municipality_name" :value="municipality_name">
                <input type="hidden" name="province_name" :value="province_name">
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
                province_id: '',
                municipality_id: '',
                barangay_id: '',
                province_name: '',
                municipality_name: '',
                barangay_name: '',
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
                municipality_list: [],
                barangay_list: [],
                province_list: [],
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
                province_id: { required },
                municipality_id: { required },
                barangay_id: { required },
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
            },
            getProvinces() {
                axios.get('/api/address/provinces')
                .then(response => {
                    this.province_list = response.data;
                })
                .catch(error => {
                    console.log(error.response);
                });
            },
            getMunicipalities(e) {
                this.municipality_id = '';
                this.barangay_id = '';
                this.barangay_name = '';
                this.barangay_list = [];

                if (this.province_id) {
                    let prv = this.province_list.find(x => x.province_id == this.province_id);

                    this.province_name = prv.name;

                    axios.get('/api/address/cities/'+this.province_id)
                    .then(response => {
                        this.municipality_list = response.data;
                    })
                    .catch(error => {
                        console.log(error.response)
                    });
                } else {
                    this.municipality_list = [];
                    this.province_id = '';
                    this.province_name = '';
                }
            },
            getBarangays(e) {
                this.barangay_id = '';

                if (this.municipality_id) {

                    let city = this.municipality_list.find(x => x.city_id == this.municipality_id);

                    this.municipality_name = city.name;

                    axios.get('/api/address/barangays/'+this.municipality_id)
                    .then(response => {
                        this.barangay_list = response.data;
                    })
                    .catch(error => {
                        console.log(error.response)
                    });


                } else {
                    this.barangay_list = [];
                    this.municipality_id = '';
                    this.barangay_id = '';
                    this.barangay_name = '';
                }
            },
            getBarangayName() {
                const brgy = this.barangay_list.find(brgy => brgy.id === this.barangay_id);
                this.barangay_name = brgy.name;
            }

        },
        created() {
            if (this.old) {
                this.email = this.old.email;
                this.first_name = this.old.first_name;
                this.last_name = this.old.last_name;
            }
           
        },
        computed: {
            provinceList() {
                let items = this.province_list.sort((a,b) => (a.name > b.name) ? 1 : -1);
                return items;
            },
        },
        mounted() {
            this.getProvinces();
        }
    }
</script>
