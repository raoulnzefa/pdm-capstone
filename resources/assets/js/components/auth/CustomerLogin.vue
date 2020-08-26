<template>
<div class="row justify-content-center">
	<div class="col-md-6">
		<div class="card">
			<div class="card-header text-center ifg-card-header">
				Login form
			</div>
			<div class="card-body">
				<form @submit.prevent="customerLogin" ref="custLogin" action="/login" method="POST">
				<input type="hidden" name="_token" :value="csrf">	
					<div class="form-group">
						<label class="ifg-label" for="email">Email:</label>
					    <input type="email" name="email" 
					    	class="form-control" :class="{'is-invalid' : $v.email.$error }" 
					    	id="email"
					    	v-model.trim="$v.email.$model"
					    	placeholder="Enter your email"
					    	tabindex="1">
					    <div v-if="$v.email.$error">
					    	<span class="error-feedback d-block" v-if="!$v.email.required">Please enter an email address</span>
					    	<span class="error-feedback d-block" v-if="!$v.email.email">Please use a valid email address, such as user@example.com </span>
					    </div>
					</div>
					<div class="form-group">
						<label class="ifg-label" for="password">Password:</label>
						<div class="input-group">
                     <input :type="visiblePassword ? 'text' : 'password'" class="form-control"
                         v-model.trim="$v.password.$model"
                         :class="{'is-invalid' : $v.password.$error}" 
                         tabindex="2" 
                         name="password"
                         placeholder="Enter you password"
                         id="cPass">
                   	<div class="input-group-append">
	                     <button class="btn btn-visible-pass"
	                         type="button" @click="visiblePass"><i class="fa" :class="visiblePassword ? 'fa-eye' : 'fa-eye-slash'" v-b-tooltip.hover :title="visiblePassword ? 'Hide password' : 'Show password'"></i></button>
                     </div>
                 </div>
					   <div v-if="$v.password.$error">
					    	<span class="error-feedback d-block" v-if="!$v.password.required">Please enter a password</span>
					    </div>
					</div>
					<div class="form-group">
						<button type="submit" class="mt-4 btn btn-block btn-dark ifg-btn" :disabled="submit">LOGIN</button>
					</div>
					<div>
						 <center>
						 		<p class="mb-2 mt-4">New customer? <a :href="create_account">Create account</a></p>
						 		<a href="/password/reset" class="d-block">Forgot your password?</a>
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

	export default {
		props: ['old_email','create_account'],
		data() {
			return {
				email: '',
				password: '',
				csrf: document.head.querySelector('meta[name="csrf-token"]').content,
				submit: false,
				visiblePassword: false,
			}
		},
		validations() {
			return {
				email: {
					required,
					email
				},
				password: {
					required
				}
			}
		},
		methods: {
			visiblePass() {
            this.visiblePassword = !this.visiblePassword;
         },
			customerLogin() {
				this.$v.$touch();

				if (!this.$v.$invalid)
				{
					this.submit = true;
					this.$refs.custLogin.submit();
				}
			}
		},
		created() {
			if (this.old_email)
			{
				this.email = this.old_email;
			}
		}
	}
</script>