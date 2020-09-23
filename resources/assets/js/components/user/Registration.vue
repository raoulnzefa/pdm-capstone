<template>
<div class="row justify-content-center mb-5">
    <div class="col-md-5">
        <div class="card card-login mt-3">
            <form method="POST" ref="adminRegistrationRefs" @submit.prevent="register">
            <input type="hidden" name="_token" :value="csrf">
            <div class="card-header">Register Account</div>
            <div class="card-body pt-3">
                <div class="alert alert-danger" v-if="server_errors.length != 0">
                    <ul>
                        <li v-for="err in server_errors">{{ err[0] }}</li>
                    </ul>
                </div>
                <div class="form-group">
                    <label>Firstname:</label>
                    <input type="text" class="form-control" tabindex="1" 
                        placeholder="Enter firstname"
                        v-model.trim="$v.firstname.$model"
                        :class="{'is-invalid' : $v.firstname.$error}">
                    <div v-if="$v.firstname.$error">
                        <span class="error-feedback" v-if="!$v.firstname.required">Firstname is required</span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Lastname:</label>
                    <input type="text" class="form-control" tabindex="2" 
                        placeholder="Enter lastname"
                        v-model.trim="$v.lastname.$model"
                        :class="{'is-invalid' : $v.lastname.$error}">
                    <div v-if="$v.lastname.$error">
                        <span class="error-feedback" v-if="!$v.lastname.required">Lastname is required</span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" class="form-control" v-model="username" readonly>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input :type="show_password ? 'text' : 'password'" 
                        class="form-control" 
                        tabindex="3" 
                        placeholder="Enter password"
                        v-model.trim="$v.password.$model"
                        :class="{'is-invalid' : $v.password.$error}">
                    <div v-if="$v.password.$error">
                        <span class="error-feedback" v-if="!$v.password.required">Password is required</span>
                        <span class="error-feedback" v-if="!$v.password.lettersAndNumbers">Password must have letters and numbers</span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Confirm Password:</label>
                    <input :type="show_password ? 'text' : 'password'" 
                        class="form-control" 
                        tabindex="4" 
                        placeholder="Re-enter password"
                        v-model.trim="$v.password_confirmation.$model"
                        :class="{'is-invalid' : $v.password_confirmation.$error}">
                    <div v-if="$v.password_confirmation.$error">
                        <span class="error-feedback" v-if="!$v.password_confirmation.required">Password confirmation is required</span>
                        <template v-if="$v.password_confirmation.required">
                            <span class="error-feedback" v-if="!$v.password_confirmation.sameAsPassword">Password must be identical</span>
                        </template>
                    </div>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="show_pass" v-model="show_password" id="showPas" @change="showPassword" value="true">

                    <label class="form-check-label" for="showPas">
                       Show Password
                    </label>
                </div>
            </div><!-- card-body  -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary" :disabled="submitted">Register</button>
            </div>
            </form>
        </div> 
    </div>
</div>
</template>

<script>
    import { required, minLength, maxLength, sameAs, helpers } from 'vuelidate/lib/validators';

    const lettersAndNumbers = helpers.regex('lettersAndNumbers', /([A-Za-z]+[0-9]|[0-9]+[A-Za-z])[A-Za-z0-9]*/);
    export default {
        data() {
            return {
                csrf: document.head.querySelector('meta[name="csrf-token"]').content,
                submitted: false,
                firstname: '',
                lastname: '',
                password: '',
                password_confirmation: '',
                show_password: false,
                server_errors: [],
            }
        },
        validations() {
            return {
                firstname: { required },
                lastname: { required },
                password: {
                    required,
                    minLength: minLength(6),
                    lettersAndNumbers,
                },
                password_confirmation: {
                    required,
                    sameAsPassword: sameAs('password'),
                },
            }
        },
        methods: {
            showPassword(e) {
                this.show_password = this.show_password;
            },
            register() {
                this.$v.$touch();

                if (!this.$v.$invalid) {
                    this.submitted = true;
                    axios.post('/api/admin/register', {
                        first_name: this.firstname,
                        last_name: this.lastname,
                        username: this.username,
                        password: this.password,
                        password_confirmation: this.password_confirmation,
                    })
                    .then(response => {
                        this.submitted = false;
                        if (response.data.success) {
                            window.location.href = '/admin/registration/success';
                        }
                    })
                    .catch(error => {
                        if (error.response.status == 422)
                        {
                            this.server_errors = error.response.data.errors
                            this.submitted = false;
                        }

                    })
                }
            },
            testHREF() {
                window.location.href = '/admin/registration/success';
            }
        },
        computed: {
            username() {
                if (!this.firstname && !this.lastname)
                {
                    return null;
                }

                return this.firstname.toLowerCase().split(' ').join('')+'.'+this.lastname.toLowerCase().split(' ').join();
            }
        },
    }
</script>