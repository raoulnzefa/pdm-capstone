<template>
<div class="card">
    <div class="card-header">
        <h3 class="mb-0">Create Address</h3>
    </div>
    <form method="post" @submit.prevent="saveAddress">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" v-if="server_errors.length != 0">
                    <ul class="mb-0 pl-2">
                        <li v-for="err in server_errors">{{ err[0] }}</li>
                    </ul>
                </div>
                <br>
                
                    <div class="form-group row">
                        <label for="addrFname" class="col-sm-4 col-form-label text-right">Firstname:</label>
                        <div class="col-sm-6">
                            <input type="text" name="firstname" id="addrFname" class="form-control"
                                tabindex="1"
                                placeholder="Enter your firstname"
                                v-model.trim="$v.firstname.$model"
                                :class="{'is-invalid': $v.firstname.$error}">
                                <div v-if="$v.firstname.$error">
                                    <span class="error-feedback" v-if="!$v.firstname.required">Firstname is required</span>
                                </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addrFname" class="col-sm-4 col-form-label text-right">Lastname:</label>
                        <div class="col-sm-6">
                            <input type="text" name="lastname" id="addrLname" class="form-control"
                                tabindex="2"
                                placeholder="Enter your lastname"
                                v-model.trim="$v.lastname.$model"
                                :class="{'is-invalid': $v.lastname.$error}">
                                <div v-if="$v.lastname.$error">
                                    <span class="error-feedback" v-if="!$v.lastname.required">Lastname is required</span>
                                </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addrStreet" class="col-sm-4 col-form-label text-right">Street:</label>
                        <div class="col-sm-6">
                            <input type="text" name="street" id="addrStreet" class="form-control"
                                tabindex="1"
                                placeholder="Enter your street"
                                v-model.trim="$v.street.$model"
                                :class="{'is-invalid': $v.street.$error}">
                                <div v-if="$v.street.$error">
                                    <span class="error-feedback" v-if="!$v.street.required">Street is required</span>
                                </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addrProvince" class="col-sm-4 col-form-label text-right">Province:</label>
                        <div class="col-sm-6">
                            <select class="form-control" id="addrProvince"
                                tabindex="2"
                                v-model.trim="$v.province_id.$model"
                                :class="{'is-invalid': $v.province_id.$error}"
                                @change="getMunicipalities"> 
                                <option value="">Select a province</option>
                                <option v-for="(prov, index) in provinceList" :key="index" :value="prov.province_id">{{prov.name}}</option>
                            </select>
                            <div v-if="$v.province_id.$error">
                                <span class="error-feedback" v-if="!$v.province_id.required">Province is required</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addrMunicipality" class="col-sm-4 col-form-label text-right">Municipality:</label>
                        <div class="col-sm-6">
                            <select class="form-control" id="addrMunicipality"
                                tabindex="3"
                                v-model.trim="$v.municipality_id.$model"
                                :class="{'is-invalid': $v.municipality_id.$error}"
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
                        <label for="addrBarangay" class="col-sm-4 col-form-label text-right">Barangay:</label>
                        <div class="col-sm-6">
                             <select class="form-control" id="addrBarangay"
                                tabindex="4"
                                v-model.trim="$v.barangay_id.$model"
                                :class="{'is-invalid': $v.barangay_id.$error}">
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
                        <label for="addrZipCode" class="col-sm-4 col-form-label text-right">Zip Code:</label>
                        <div class="col-sm-6">
                            <input type="text" name="zip_code" id="addrZipCode" class="form-control"
                            placeholder="Enter your zip code"
                            tabindex="5"
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
                        <label for="addrMobileNo" class="col-sm-4 col-form-label text-right">Mobile no.:</label>
                        <div class="col-sm-6">
                            <input type="text" name="mobile_no" id="addrMobileNo" class="form-control"
                                tabindex="9"
                                placeholder="Enter your mobile number"
                                v-model.trim="$v.mobile_no.$model"
                                :class="{'is-invalid': $v.mobile_no.$error}">
                                <div v-if="$v.mobile_no.$error">
                                    <span class="error-feedback" v-if="!$v.mobile_no.required">Mobile no. is required</span>
                                </div>
                        </div>
                    </div>
                    <br>
                   
            </div>
        </div>
    </div>
    <div class="card-footer clearfix">
        <button type="submit" class="btn btn-primary float-right" :disabled="btnClicked">Save address</button>
        <a href="/my-account/addresses" class="btn btn-danger float-right mr-2">Cancel</a>
    </div>
    </form>
</div>
</template>

<script>
    import { required, minLength, maxLength, helpers, numeric } from 'vuelidate/lib/validators';

    const digitsOnly = helpers.regex('digitsOnly', /^[0-9]*$/);

    export default {
        props: [
            'customer',
        ],
        data() {
            return {
                firstname: this.customer.first_name,
                lastname: this.customer.last_name,
                street: '',
                province_id: '',
                municipality_id: '',
                barangay_id: '',
                province_name: '',
                municipality_name: '',
                barangay_name: '',
                zip_code: '',
                mobile_no: '',
                btnClicked: false,
                server_errors: [],
                municipality_list: [],
                barangay_list: [],
                province_list: [],
            }
        },
        validations() {
            return {
                firstname: { required },
                lastname: { required },
                mobile_no: { required },
                street: { required },
                province_id: { required },
                municipality_id: { required },
                barangay_id: { required },
                zip_code: { required, digitsOnly },
            }
        },
        methods: {
            saveAddress() {
                this.$v.$touch();


                if (!this.$v.$invalid) {

                    this.btnClicked = true;

                    let brgy = this.barangay_list.find(x => x.id == this.barangay_id);

                    this.barangay_name = brgy.name;

                    axios.post('/api/customer-address-save', {
                        firstname: this.firstname,
                        lastname: this.lastname,
                        mobile_no: this.mobile_no,
                        street: this.street,
                        province: this.province_name,
                        municipality: this.municipality_name,
                        barangay: this.barangay_name,
                        province_id: this.province_id,
                        municipality_id: this.municipality_id,
                        barangay_id: this.barangay_id,
                        zip_code: this.zip_code,
                        customer_id: this.customer.id,
                    })
                    .then(res => {
                        if (res.data.success) {
                             Swal('Address has been created', '', 'success')
                            .then(okay => {
                                if (okay.value)
                                {
                                    this.$nextTick(() => { this.$v.$reset() });
                                    this.btnClicked = false
                                    this.street = '';
                                    this.province_id = '';
                                    this.municipality_id = '';
                                    this.barangay_id = '';
                                    this.zip_code = '';
                                    window.location.href = res.data.redirect_back;
                                }
                            })
                        }
                    })
                    .catch(error => {
                        if (error.response.status == 422)
                        {
                            this.server_errors = error.response.data.errors
                            this.btnClicked = false
                        }
                    });
                }
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