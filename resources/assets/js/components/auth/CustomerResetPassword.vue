<template>
<div>
	<form @submit.prevent="submitResetPassword" ref="resetPass" class="mt-4" method="POST" action="/password/reset">
        <input type="hidden" name="_token" :value="csrf">
        <input type="hidden" name="token" :value="token">
        <div class="form-group row">
            <label for="email" class="col-sm-4 col-form-label">Email:</label>
            <div class="col-sm-8">
                <input type="email" name="email" class="form-control" v-model.trim="$v.email.$model" :class="{'is-invalid' : $v.email.$error}" placeholder="Enter your email" tabindex="1">
                <div v-if="$v.email.$error">
                	<span class="error-feedback d-block" v-if="!$v.email.required">Please enter an email address</span>
	    			<span class="error-feedback d-block" v-if="!$v.email.email">Please use a valid email address, such as user@example.com </span>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="pass" class="col-sm-4 col-form-label">Password:</label>
            <div class="col-sm-8">
                <input type="password" name="password" id="pass" class="form-control" :class="{'is-invalid' : $v.password.$error}" v-model.trim="$v.password.$model" placeholder="Enter your password" tabindex="2">
                <div v-if="$v.password.$error">
                	<span class="error-feedback d-block" v-if="!$v.password.required">Please enter a password</span>
	    			<span class="error-feedback d-block" v-if="!$v.password.minLength">Password must be at least {{ $v.password.$params.minLength.min }} characters</span>
	    			<template v-if="$v.password.minLength">			
	    				<span class="error-feedback d-block" v-if="!$v.password.lettersAndNumbers">Password must contain letters and numbers</span>
	    			</template>
                </div>
            </div>
            
        </div>
        <div class="form-group row">
            <label for="confirm-pass" class="col-sm-4 col-form-label">Confirm Password:</label>
            <div class="col-sm-8">
                <input type="password" id="confirm-pass" name="password_confirmation" class="form-control" :class="{'is-invalid' : $v.password_confirmation.$error}" v-model.trim="$v.password_confirmation.$model" placeholder="Re-enter your password" tabindex="3">
                 <div v-if="$v.password_confirmation.$error">
	    			<span class="error-feedback d-block" v-if="!$v.password.sameAs">Password and password confirmation are not match</span>
                </div>
            </div>
            
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-4 col-form-label"></label>
            <div class="col-sm-8">
                <button type="submit" class="btn btn-dark ifg-btn" :disabled="submit">Reset Password</button>
            </div>
        </div>
    </form>
</div>
</template>
<script>
	import { required, minLength, maxLength, sameAs, email, helpers } from 'vuelidate/lib/validators';

	const lettersAndNumbers = helpers.regex('lettersAndNumbers', /([A-Za-z]+[0-9]|[0-9]+[A-Za-z])[A-Za-z0-9]*/);

	export default {
		props: ['token'],
		data() {
			return {
				csrf: document.head.querySelector('meta[name="csrf-token"]').content,
				submit: false,
				email: '',
				password: '',
				password_confirmation: ''
			}
		},
		validations() {
			return {
				email: {
					required,
					email
				},
				password: {
					required,
					minLength: minLength(6),
					lettersAndNumbers
				},
				password_confirmation: {
					sameAs: sameAs('password')
				}
			}
		},
		methods: {
			submitResetPassword() {
				this.$v.$touch()

				if (!this.$v.$invalid)
				{
					this.submit = true;
					this.$refs.resetPass.submit();
				}
			}
		},
		created() {

		}
	}
</script>