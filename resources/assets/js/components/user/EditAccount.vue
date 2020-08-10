<template>
<div class="row justify-content-center">
    <div class="col-md-5">
    	<a href="/admin/users" class="d-block mb-3 text-secondary"><i class="fa fa-chevron-left"></i>&nbsp;Back</a>
    	<div class="card">
				<div class="card-body">
					<h2>You Information</h2>
					<div class="row">
						<div class="col-md-12">
							<div class="mt-3">
								<form @submit.prevent="editAccount">
										<div class="form-group row">
											<label for="a_u_email" class="col-sm-3 col-form-label">Email:</label>
											<div class="col-sm-9">
												<input type="email" class="form-control" :class="{'is-invalid':errors.has('Email')}" id="a_u_email" placeholder="" v-model="user.email" v-validate="'required|email'" name="Email">
												<span class="invalid-feedback">{{ errors.first('Email') }}</span>
											</div>
										</div>
										<div class="form-group row mt-3">
												<label for="a_u_fname" class="col-sm-3 col-form-label">First Name:</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" :class="{'is-invalid': errors.has('First Name')}" id="a_u_fname" placeholder="" v-model="user.first_name" name="First Name" v-validate="'required|alpha_spaces'">
													<span class="invalid-feedback">{{ errors.first('First Name') }}</span>
												</div>
											</div>
										<div class="form-group row">
												<label for="a_u_lname" class="col-sm-3 col-form-label">Last Name:</label>
												<div class="col-sm-9">
													<input type="text" class="form-control" :class="{'is-invalid':errors.has('Last Name')}" id="a_u_lname" placeholder="" v-model="user.last_name" name="Last Name" v-validate="'required|alpha_spaces'">
													<span class="invalid-feedback">{{ errors.first('Last Name') }}</span>
												</div>
										</div>
										<div class="form-group row mt-4">
												<label for="" class="col-sm-3 col-form-label"></label>
												<div class="col-sm-9">
													<button type="submit" class="btn btn-dark" :disabled="userUpdate"><span v-if="!userUpdate">Update info</span><span v-else>Updating...</span></button>
													<a :href="'/admin/user/change-password/'+admin.id" class="btn btn-dark" :disabled="userUpdate">Change password</a>
												</div>
										</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
    </div>
</div>
</template>

<script>
    export default {
    	props: ['admin'],
       	data() {
       		return {
       			user: {
				email: this.admin.email,
					first_name: this.admin.first_name,
					last_name: this.admin.last_name,
					admin_id: this.admin.id
				},
       			server_errors: [],
				has_error: false,
				userUpdate: false   
       		}
       	},
       	methods: {
       		editAccount() {
       			this.$validator.validate()
       			.then(result => {
       				if (result)
       				{
						this.userUpdate = true;

       					axios.put('/api/admin/edit-account/'+this.admin.id, this.user)
       					.then(response => {
       						let res = response.data

       						if (res.success)
       						{
								this.userUpdate = false;
       							Swal('Edit Account', res.message, 'success')
       							.then(okay => {
       								if (okay.value)
       								{
       									//update displayed username
       									this.$bus.emit('update-username')

       									this.server_errors = []
       									this.has_error = false
       									this.$validator.reset()
       								}
       							})
       						}
       					})
       					.catch(error => {
       						if (error.response.status == 422)
    							{
									this.userUpdate = false;
    								this.server_errors = error.response.data.errors;
    								this.has_error = true;
    							}
       					})
       				}
       			})
       		}
       	}
    }
</script>
