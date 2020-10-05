<template>
<div class="row">
    <div class="col-md-12">
				<div class="card mt-4 mb-4">
          <div class="card-header">
            <h3 class="mb-0">Edit Information</h3>
          </div>
          <form @submit.prevent="updateInformation">
          <div class="card-body">
            <div class="row">
            	<div class="col-md-4">
            		<template>
		            </template>
		            <template>
		            	<div class="alert alert-danger" v-if="server_errors.length != 0">
								<ul class="mb-0 pl-3">
									<li v-for="(err,index) in server_errors" :key="index">{{ err[0] }}</li>
								</ul>
							</div>
		                <div class="form-group">
		                  <label for="ufname">First name:</label>
		                  <input type="text" name="first_name" id="ufname" class="form-control"
		                        v-model.trim="$v.first_name.$model"
		                        :class="{'is-invalid': $v.first_name.$error}">
		                  <div v-if="$v.first_name.$error">
		                    <span class="error-feedback" v-if="!$v.first_name.required">First name is required</span>
		                  </div>
		                </div>
		                <div class="form-group">
		                  <label for="ulname">Last name:</label>
		                  <input type="text" name="last_name" id="ulname" class="form-control"
		                        v-model.trim="$v.last_name.$model"
		                        :class="{'is-invalid': $v.last_name.$error}">
		                  <div v-if="$v.last_name.$error">
		                    <span class="error-feedback" v-if="!$v.last_name.required">Last name is required</span>
		                  </div>
		                </div>
		                <div class="form-group">
		                  <label for="uname">Username:</label>
		                  <input type="text" name="username" id="uname" class="form-control" v-model="username" readonly="true">
		                </div>
		                <div class="form-group">
		                  <label for="unewpassword">New password:</label>
		                  <input type="password" name="new_password" id="unewpassword" class="form-control"
		                        v-model.trim="$v.new_password.$model"
		                        :class="{'is-invalid': $v.new_password.$error}">
		                  <div v-if="$v.new_password.$error">
		                    <span class="error-feedback" v-if="!$v.new_password.minLength">Password must have at least {{ $v.new_password.$params.minLength.min }} characters.</span>
		                  </div>
		                </div>
		                <div class="form-group">
		                  <label for="uconfirmnewpass">Confirm new password:</label>
		                  <input type="password" name="confirm_new_pass" id="uconfirmnewpass" class="form-control"
		                        v-model.trim="$v.confirm_new_pass.$model"
		                        :class="{'is-invalid': $v.confirm_new_pass.$error}">
		                  <div v-if="$v.confirm_new_pass.$error">
		                    <span class="error-feedback" v-if="!$v.confirm_new_pass.sameAsPassword">Password must be identical</span>
		                  </div>
		                </div>
		            </template>
            	</div>
            </div>
          </div>
          <div class="card-footer">
          	<input type="submit" value="Save" name="Update information" class="btn btn-primary" :disabled="disabledBtn">
            <a href="/admin/dashboard" class="btn btn-danger">Cancel</a>
          </div>
       	</form>
        </div><!-- card -->
       
    </div>
</div>
</template>

<script>
  import { required, sameAs, minLength } from 'vuelidate/lib/validators';
  import { HalfCircleSpinner } from 'epic-spinners';

  export default {
    props: ['admin'],
    data() {
      return {
        first_name: this.admin.first_name,
        last_name: this.admin.last_name,
        new_password: '',
        confirm_new_pass: '',
        disabledBtn: false,
        server_errors: []
      }
    },
    components: {
      HalfCircleSpinner
    },
    validations() {
      return {
        first_name: {
          required
        },
        last_name: {
          required
        },
        new_password: {
          minLength: minLength(6)
        },
        confirm_new_pass: {
          sameAsPassword: sameAs('new_password') 
        }
      }
    },
    methods: {
      updateInformation() {
        this.$v.$touch();

        if (!this.$v.$invalid) {
          this.disabledBtn = true;
          axios.put('/api/update-user-info/'+this.admin.id, {
            first_name: this.first_name,
            last_name: this.last_name,
            username: this.username,
            admin_id: this.admin.id,
            password: this.new_password,
            password_confirmation: this.confirm_new_pass,
          })
          .then(response => {
            this.disabledBtn = false;
             if (response.data.success) {
                 Swal('User information', 'User information has been saved', 'success')
                 .then((okay) => {
                    if (okay) {
                      window.location.reload();
                    }
                  });
              }
          })
          .catch(error => {
            this.disabledBtn = false;
            if (error.response.status == 422) {
              this.server_errors = error.response.data.errors;
            }
          });
        }
      }
    },
    computed: {
      username() {
          if (!this.first_name && !this.last_name)
          {
            return null;
          }

          return this.first_name.toLowerCase().split(' ').join('')+'.'+this.last_name.toLowerCase().split(' ').join('');
      }
    }
  }
</script>