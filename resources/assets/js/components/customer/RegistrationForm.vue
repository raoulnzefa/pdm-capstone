<template>
<div class="row mb-5 justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header text-center ifg-card-header">
                Create Account
            </div>
            <div class="card-body">
                <form method="POST" action="/register" ref="register" @submit.prevent="registerCustomer">
                <div class="form-group">
                    <label for="cEmail" class="ifg-label">Email:</label>
                    <input type="email" class="form-control" id="cEmail" 
                        placeholder="Enter your email"
                        v-model.trim="$v.email.$model"
                        :class="{'is-invalid' : $v.email.$error}"
                        name="email"
                        tabindex="1">
                    <div v-if="$v.email.$error">
                        <span class="error-feedback" v-if="!$v.email.required">Email is required</span>
                        <span class="error-feedback" v-if="!$v.email.custEmail">Please input a valid email</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="cPass" class="ifg-label">Password:</label>
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
                <div class="form-group">
                    <label for="cConfirmPass"  class="ifg-label">Confirm password</label>
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
                        <span class="error-feedback" v-if="!$v.password.sameAsPassword">Password must be identical</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="cFname"  class="ifg-label">Firstname:</label>
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
                <div class="form-group">
                    <label for="cLname"  class="ifg-label">Lastname:</label>
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
                <input type="hidden" name="_token" :value="csrf">
                <div>
                    <button type="submit" class="mt-4 mb-4 btn ifg-btn btn-dark btn-block" :disabled="submit"><span v-if="submit"><i class="fa fa-spinner fa-spin"></i>&nbsp;</span>Submit</button>
                </div>
                <div>
                    <center>
                        <p>Registered customer? <a href="/login">Login here</a></p>
                    </center>
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

    export default {
        props: ['old'],
       	data() {
       		return {
                email: '',
                first_name: '',
                last_name: '',
                street: '',
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
                    maxLength: maxLength(50)
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
                }
            }
        },
       	methods: {
       		registerCustomer() {
                this.$v.$touch();
                if (!this.$v.$invalid) {
                    this.submit = true
                    this.$refs.register.submit();
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
