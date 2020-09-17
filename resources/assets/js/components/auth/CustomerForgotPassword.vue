<template>
	<div>
		 <form @submit.prevent="submitForgotPassword" ref="forgotPass" class="mt-5" method="POST" action="/password/email">
            <input type="hidden" name="_token" :value="csrf">
            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label">Email:</label>
                <div class="col-sm-10">
                    <input type="email" name="email" class="form-control" v-model.trim="$v.email.$model" :class="{'is-invalid' : $v.email.$error}" placeholder="Enter your email">
                    <div v-if="$v.email.$error">
                    	<span class="error-feedback d-block" v-if="!$v.email.required">Please enter an email address</span>
		    			<span class="error-feedback d-block" v-if="!$v.email.email">Please use a valid email address, such as user@example.com </span>
                    </div>
                </div>
                
            </div>
            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary" :disabled="submit">Reset Password</button>
                </div>
            </div>
        </form>
	</div>
</template>
<script>
	import { required, minLength, maxLength, sameAs, email, helpers } from 'vuelidate/lib/validators';

	export default {
		props: ['old_email'],
		data() {
			return {
				email: '',
				csrf: document.head.querySelector('meta[name="csrf-token"]').content,
				submit: false
			}
		},
		validations() {
			return {
				email: {
					required,
					email
				}
			}
		},
		methods: {
			submitForgotPassword() {
				this.$v.$touch();

				if(!this.$v.$invalid)
				{
					this.submit = true;
					this.$refs.forgotPass.submit();
				}
			}
		},
		created() {
			if (this.old_email) {
				this.email = this.old_email;
			}
		}
	}
</script>