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
	    			<span class="error-feedback d-block" v-if="!$v.password.sameAs">Password and password confirmation are not match</span>
                </div>
            </div>
            
        </div>
        <div class="form-group row">
            <label for="" class="col-sm-4 col-form-label"></label>
            <div class="col-sm-8">
                <button type="submit" class="btn btn-primary" :disabled="submit">Reset Password</button>
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
				password_confirmation: '',
				visiblePassword: false,
            visibleConfirmPassword: false,
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
			},
			visiblePass() {
             this.visiblePassword = !this.visiblePassword;
         },
         visibleConfirmPass() {
             this.visibleConfirmPassword = !this.visibleConfirmPassword;
         },
		},
		created() {

		}
	}
</script>