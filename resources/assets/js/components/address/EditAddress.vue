<template>
<div class="card">
    <div class="card-header">
        <h3 class="mb-0">Edit Address</h3>
    </div>
    <form method="post" @submit.prevent="updateAddress">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-danger" v-if="server_errors.length != 0">
                    <ul class="mb-0 pl-2 rm-bullets">
                        <li v-for="err in server_errors">{{ err[0] }}</li>
                    </ul>
                </div>
                <br>
                    <div class="form-group row">
                        <label for="addrFname" class="col-sm-4 col-form-label text-right">First Name:</label>
                        <div class="col-sm-6">
                            <input type="text" name="firstname" id="addrFname" class="form-control"
                                tabindex="1"
                                placeholder="Enter your firstname"
                                v-model.trim="$v.firstname.$model"
                                :class="{'is-invalid': $v.firstname.$error}">
                                <div v-if="$v.firstname.$error">
                                    <span class="error-feedback" v-if="!$v.firstname.required">First Name is required</span>
                                </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addrFname" class="col-sm-4 col-form-label text-right">Last Name:</label>
                        <div class="col-sm-6">
                            <input type="text" name="lastname" id="addrLname" class="form-control"
                                tabindex="2"
                                placeholder="Enter your lastname"
                                v-model.trim="$v.lastname.$model"
                                :class="{'is-invalid': $v.lastname.$error}">
                                <div v-if="$v.lastname.$error">
                                    <span class="error-feedback" v-if="!$v.lastname.required">Last Name is required</span>
                                </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addrStreetEdit" class="col-sm-4 col-form-label text-right">Street:</label>
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
                        <label for="addrBarangay" class="col-sm-4 col-form-label text-right">Barangay:</label>
                        <div class="col-sm-6">
                            <input type="text" name="barangay" id="addrBarangay" class="form-control"
                                v-model.trim="$v.barangay.$model"
                                :class="{'is-invalid': $v.barangay.$error}"
                                placeholder="Enter your barangay">
                            <div v-if="$v.barangay.$error">
                                <span class="error-feedback" v-if="!$v.barangay.required">Barangay is required</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addrMunicipality" class="col-sm-4 col-form-label text-right">Municipality:</label>
                        <div class="col-sm-6">
                            <input type="text" name="municipality" id="addrMunicipality" class="form-control"
                                v-model.trim="$v.municipality.$model"
                                :class="{'is-invalid': $v.municipality.$error}"
                                placeholder="Enter your municipality">
                            <div v-if="$v.municipality.$error">
                                <span class="error-feedback" v-if="!$v.municipality.required">Municipality is required</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addrProvince" class="col-sm-4 col-form-label text-right">Province:</label>
                        <div class="col-sm-6">
                            <input type="text" name="province" id="addrProvince" class="form-control"
                                v-model.trim="$v.province.$model"
                                :class="{'is-invalid': $v.province.$error}"
                                placeholder="Enter your province">
                            <div v-if="$v.province.$error">
                                <span class="error-feedback" v-if="!$v.province.required">Province is required</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="addrZipCodeEdit" class="col-sm-4 col-form-label text-right">Zip Code:</label>
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
        <button type="submit" class="btn btn-primary float-right" :disabled="isSubmitted">Update address</button>
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
            'address',
            'customer',
        ],
        data() {
            return {
                firstname: this.address.firstname,
                lastname: this.address.lastname,
                street: this.address.street,
                province: this.address.province,
                municipality: this.address.municipality,
                barangay: this.address.barangay,
                zip_code: this.address.zip_code,
                mobile_no: this.address.mobile_no,
                isSubmitted: false,
                server_errors: [],
                province_list: [],
                municipality_list: [],
                barangay_list: [],
            }
        },
        validations() {
            return {
                firstname: { required },
                lastname: { required },
                street: { required },
                province: { required },
                municipality: { required },
                barangay: { required },
                zip_code: { required, digitsOnly },
                mobile_no: { required },
            }
        },
        methods: {
            updateAddress() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    this.isSubmitted = true;

                    axios.put('/api/customer-address-update/'+this.address.id, {
                        customer_id: this.customer.id,
                        firstname: this.firstname,
                        lastname: this.lastname,
                        street: this.street,
                        province: this.province,
                        municipality: this.municipality,
                        barangay: this.barangay,
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
            }
        }
    }
</script>