<template>
<div class="row justify-content-center">
    <div class="col-md-12">
        <h2 class="mb-4">Edit Address</h2>
        <div class="alert alert-danger" v-if="server_errors.length != 0">
            <ul>
                <li v-for="err in server_errors">{{ err[0] }}</li>
            </ul>
        </div>
        <br>
        <form method="post" @submit.prevent="updateAddress">
            <div class="form-group row">
                <label for="addrFname" class="col-sm-3 col-form-label text-right">Firstname:</label>
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
                <label for="addrFname" class="col-sm-3 col-form-label text-right">Lastname:</label>
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
                <label for="addrStreetEdit" class="col-sm-3 col-form-label text-right">Street:</label>
                <div class="col-sm-6">
                    <input type="text" name="street" id="addrStreetEdit" class="form-control"
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
                <label for="addrProvinceEdit" class="col-sm-3 col-form-label text-right">Province:</label>
                <div class="col-sm-6">
                    <select class="form-control" id="addrProvinceEdit"
                        tabindex="2"
                        v-model.trim="$v.province_id.$model"
                        :class="{'is-invalid': $v.province_id.$error}"
                        @change="getMunicipalities"> 
                        <option value="">Select a province</option>
                        <option v-for="(prov, index) in provinces" :key="index" :value="prov.id">{{prov.name}}</option>
                    </select>
                    <div v-if="$v.province_id.$error">
                        <span class="error-feedback" v-if="!$v.province_id.required">Province is required</span>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="addrMunicipalityEdit" class="col-sm-3 col-form-label text-right">Municipality:</label>
                <div class="col-sm-6">
                    <select class="form-control" id="addrMunicipalityEdit"
                        tabindex="3"
                        v-model.trim="$v.municipality_id.$model"
                        :class="{'is-invalid': $v.municipality_id.$error}"
                        @change="getBarangays">
                        <option value="">Select a province first</option>
                        <option v-for="(muni, index) in municipality_list" :key="index" :value="muni.id">{{muni.name}}</option>
                    </select>
                    <div v-if="$v.municipality_id.$error">
                        <span class="error-feedback" v-if="!$v.municipality_id.required">Municipality is required</span>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="addrBarangayEdit" class="col-sm-3 col-form-label text-right">Barangay:</label>
                <div class="col-sm-6">
                     <select class="form-control" id="addrBarangayEdit"
                     tabindex="4"
                     v-model.trim="$v.barangay_id.$model"
                     :class="{'is-invalid': $v.barangay_id.$error}">
                        <option value="">Select a municipality first</option>
                        <option v-for="(barang, index) in barangay_list" :key="index" :value="barang.id">{{barang.name}}</option>
                    </select>
                    <div v-if="$v.barangay_id.$error">
                        <span class="error-feedback" v-if="!$v.barangay_id.required">Barangay is required</span>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="addrZipCodeEdit" class="col-sm-3 col-form-label text-right">Zip Code:</label>
                <div class="col-sm-6">
                    <input type="text" name="zip_code" id="addrZipCodeEdit" class="form-control"
                    tabindex="5"
                    v-model.trim="$v.zip_code.$model"
                    :class="{'is-invalid': $v.zip_code.$error}"
                    placeholder="Enter you zip code">
                    <div v-if="$v.zip_code.$error">
                        <span class="error-feedback" v-if="!$v.zip_code.required">Zip code is required</span>
                        <template v-if="$v.zip_code.required">
                            <span class="error-feedback" v-if="!$v.zip_code.digitsOnly">Zip code must be digits only</span>
                        </template>
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label for="addrMobileNo" class="col-sm-3 col-form-label text-right">Mobile no.:</label>
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
            <div class="form-group row">
                <label class="col-sm-3 col-form-label"></label>
                <div class="col-sm-6">
                    <button type="submit" class="btn btn-dark ifg-btn" :disabled="isSubmitted">Update address</button>
                </div>
            </div>
        </form>
    </div>
</div>

</template>

<script>
    import { required, minLength, maxLength, helpers, numeric } from 'vuelidate/lib/validators';

    const digitsOnly = helpers.regex('digitsOnly', /^[0-9]*$/);

    export default {
        props: [
            'address',
            'provinces',
            'municipalities',
            'barangays',
            'customer',
        ],
        data() {
            return {
                firstname: this.address.firstname,
                lastname: this.address.lastname,
                street: this.address.street,
                province_id: this.address.province_id,
                municipality_id: this.address.municipality_id,
                barangay_id: this.address.barangay_id,
                zip_code: this.address.zip_code,
                mobile_no: this.address.mobile_no,
                isSubmitted: false,
                server_errors: [],
                province_list: this.provinces,
                municipality_list: this.municipalities,
                barangay_list: this.barangays,
            }
        },
        validations() {
            return {
                firstname: { required },
                lastname: { required },
                street: { required },
                province_id: { required },
                municipality_id: { required },
                barangay_id: { required },
                zip_code: { required, digitsOnly },
                mobile_no: { required },
            }
        },
        methods: {
            updateAddress() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    this.isSubmitted = true;
                    axios.put('/api/address/update/'+this.address.id, {
                        customer_id: this.customer.id,
                        firstname: this.firstname,
                        lastname: this.lastname,
                        street: this.street,
                        province: this.province_id,
                        municipality: this.municipality_id,
                        barangay: this.barangay_id,
                        zip_code: this.zip_code,
                        mobile_no: this.mobile_no
                    })
                    .then(res => {
                         if (res.data.success) {
                             Swal('Address has been updated', '', 'success')
                            .then(okay => {
                                if (okay.value)
                                {
                                    this.$nextTick(() => { this.$v.$reset() });
                                    this.isSubmitted = false
                                    window.location.href = res.data.redirect_back;
                                }
                            })
                        }
                    })
                    .catch(error => {
                        if (error.response.status == 422)
                        {
                            this.server_errors = error.response.data.errors
                            this.isSubmitted = false
                        }
                    });
                }
            },
            getMunicipalities() {
                this.municipality_id = '';
                this.municipality_list = [];

                if (this.province_id) {
                    this.municipality_list = this.municipalities.filter(x => x.province_id == this.province_id);
                } else {
                    this.municipality_list = [];
                    this.province_id = '';
                }
            },
            getBarangays() {
                this.barangay_id = '';
                this.barangay_list = [];

                if (this.municipality_id) {
                    this.barangay_list = this.barangays.filter(x => x.municipality_id == this.municipality_id);
                } else {
                    this.barangay_list = [];
                    this.municipality_id = '';
                }
            }
        }
    }
</script>