<template>
<div class="row justify-content-center">
	<div class="col-md-8">
		<h2 class="mb-4">Change Password</h2>
		<div class="alert alert-danger" v-if="server_errors.length != 0">
            <ul class="rm-bullets">
                <li v-for="err in server_errors">{{ err[0] }}</li>
            </ul>
        </div>
		<div class="row">
			<div class="col-md-12">
				<form @submit.prevent="changePassword">
					<div class="form-group row">
                        <label for="current_pass" class="col-sm-4 col-form-label">Current Password:</label>
                        <div class="col-sm-8">
                            <input type="password" name="current_password" class="form-control" id="current_pass" :class="{'is-invalid': $v.current_password.$error}" tabindex="1" ref="curr_pass" v-model.trim="$v.current_password.$model">
                            <div v-if="$v.current_password.$error">
                            	<span class="error-feedback" v-if="!$v.current_password.required">Current password is required</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="new_pass" class="col-sm-4 col-form-label">New Password:</label>
                        <div class="col-sm-8">
                            <input type="password" name="new_password" class="form-control" id="new_pass" :class="{'is-invalid': $v.new_password.$error}" ref="new_pass" tabindex="2" v-model.trim="$v.new_password.$model">
                            <div v-if="$v.new_password.$error">
                            	<span class="error-feedback" v-if="!$v.new_password.required">New password is required</span>
                            	<span class="error-feedback d-block" v-if="!$v.new_password.lettersAndNumbers">New password must contain letters and numbers</span>
                            	<span class="error-feedback d-block" v-if="!$v.new_password.minLength">New password must have at least {{ $v.new_password.$params.minLength.min }} letters</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="confirm_pass" class="col-sm-4 col-form-label">Confirm Password:</label>
                        <div class="col-sm-8">
                            <input type="password" name="confirm_password" class="form-control" id="confirm_pass" :class="{'is-invalid': $v.confirm_password.$error}" tabindex="3" v-model.trim="$v.confirm_password.$model">
                            <div v-if="$v.confirm_password.$error">
                            	<span class="error-feedback" v-if="!$v.confirm_password.sameAsPassword">Password confirmation must be indentical</span>
                            </div>
                            
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-4 col-form-label"></label>
                        <div class="col-sm-8">
                            <button type="submit" class="btn btn-dark ifg-btn" :disabled="saving">Change Password</button>
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
	// password must contain letters and numbers
	const lettersAndNumbers = helpers.regex('lettersAndNumbers', /([A-Za-z]+[0-9]|[0-9]+[A-Za-z])[A-Za-z0-9]*/);

	export default {
		props: ['customer'],
		data() {
			return {
				current_password: '',
				new_password: '',
				confirm_password: '',
				server_errors: [],
				saving: false
			}
		},
		validations() {
			return {
				current_password: {
					required
				},
				new_password: {
					required,
					minLength: minLength(6),
					lettersAndNumbers
				},
				confirm_password: {
					required,
					sameAsPassword: sameAs('new_password')
				}
			}
		},
		methods: {
			changePassword() {
				this.$v.$touch();

				if (!this.$v.$invalid)
				{
					this.saving = true;

					axios.put('/api/customer/change-password/'+this.customer.id, {
						current_password: this.current_password,
						password: this.new_password,
						password_confirmation: this.confirm_password
					})
					.then((response) => {
						this.saving = false;
						
						if(response.data.success)
						{
							Swal('Password Updated', 'Password has been successfully updated.', 'success')
							.then((okay) => {
								if (okay)
								{
									
									this.server_errors = [];
									this.current_password = '';
									this.new_password = '';
									this.confirm_password = '';
									this.$nextTick(() => { this.$v.$reset() });
								}
							});
						}
						else
						{
							Swal('Invalid Current Password', response.data.message, 'error')
							.then((okay) => {
								if (okay)
								{
									this.$refs.curr_pass.focus();
									this.server_errors = [];
								}
							});
						}
					})
					.catch((error) => {
						if (error.response.status == 422)
						{
							this.server_errors = error.response.data.errors;
							this.saving = false;
						}
					});
				}
				
			}
		}
	}
</script>