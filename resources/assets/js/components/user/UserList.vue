<template>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div>
            <h2>Users</h2>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <button type="button" class="btn btn-dark" @click="showAddUserModal"><i class="fa fa-plus"></i> Add user</button>
                </div>
                <div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="loading">
                                <tr>
                                    <td colspan="5" align="center">
                                        <half-circle-spinner
                                            :animation-duration="1000"
                                            :size="30"
                                            color="#ff1d5e"
                                          />
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <template v-if="!users.length">
                                    <tr>
                                        <td colspan="5" align="center">
                                            No users.
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr v-for="(user, index) in users" :key="index">
                                        <td>{{ user.id }}</td>
                                        <td>{{user.username}}</td>
                                        <td>{{user.role}}</td>
                                        <td>{{user.status}}</td>
                                        <td>
                                            <a href="javascript:void(0);" class="btn btn-sm btn-dark" title="Edit User Information" @click="editUser(user.id)" v-show="user.id != admin.id"><i class="fa fa-edit"></i> Edit</a>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- card -->
    </div>    
    <!-- Create user Modal Component -->
        <b-modal id="addUserModal"
                 ref="addUserModal"
                 :title="modal_title"
                 no-close-on-backdrop
                 no-close-on-esc
                 :ok-title="ok_title"
                 :ok-disabled="submitBtn"
                 :cancel-disabled="submitBtn"
                 @ok="submitUser"
                 @cancel="cancelCreate"
                 @hidden="cancelCreate">
            <template v-if="server_errors.length != 0">
              <div class="alert alert-danger">
                <ul class="">
                  <li v-for="(err,index) in server_errors" :key="index" class="">{{ err[0] }}</li>
                </ul>
              </div>
            </template>
            <form @submit.stop.prevent="saveUser">
              <b-form-group id=""
                          label="First Name:"
                          label-for="firstName">
                <b-form-input id="firstName"
                              type="text"
                              v-model.trim="$v.first_name.$model"
                              :class="{'is-invalid': $v.first_name.$error}"
                              :readonly="edit">
                </b-form-input>
                <div v-if="$v.first_name.$error">
                  <span class="error-feedback" v-if="!$v.first_name.required">First name is required</span>
                  <span class="error-feedback d-block" v-if="!$v.first_name.maxLength">First name cannot be at most {{ $v.first_name.$params.maxLength.max }}</span>
                  <span class="error-feedback d-block" v-if="!$v.first_name.lettersSpace">First name is invalid</span>
                </div>
              </b-form-group>
              <b-form-group id=""
                          label="Last Name:"
                          label-for="lastName">
                <b-form-input id="lastName"
                              type="text"
                              v-model.trim="$v.last_name.$model"
                              :class="{'is-invalid': $v.last_name.$error}"
                              :readonly="edit">
                </b-form-input>
                <div v-if="$v.last_name.$error">
                  <span class="error-feedback" v-if="!$v.last_name.required">Last name is required</span>
                </div>
              </b-form-group>
              <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" :value="username" readonly>
              </div>
               <div class="form-group">
                <label for="username">Role:</label>
                <select class="form-control" name="role"
                      v-model="role">
                  <option value="Administrator">Administrator</option>
                  <option value="Staff">Staff</option>
                </select>
              </div>
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="isAdmin" v-model="status">
                <label class="form-check-label" for="isAdmin">Lock user</label>
              </div>
             </form>   
          </b-modal>
      <!-- /create user modal -->
</div>
</template>
<script>
    import { required, minLength, maxLength, sameAs, helpers } from 'vuelidate/lib/validators';
    import { HalfCircleSpinner } from 'epic-spinners'

    const lettersAndNumbers = helpers.regex('lettersAndNumbers', /([A-Za-z]+[0-9]|[0-9]+[A-Za-z])[A-Za-z0-9]*/);
    const lettersSpace = helpers.regex('lettersSpace', /^[a-zA-Z\s]*$/);

    export default {
        props: ['admin'],
        data() {
            return {
                users: [],
                modal_title: 'Add user',
                ok_title: 'Save',
                submit: false,
                is_admin: false,
                loading: false,
                admin_id: this.admin.id,
                server_errors: [],
                first_name: '',
                last_name: '',
                status: '',
                password: '',
                password_confirmation: '',
                edit: false,
                userID: '',
                role: '',
                submitBtn: false,
                status: false,
                user_id: '',
            }
        },
        components: {
            HalfCircleSpinner
        },
        validations() {
            if (!this.edit) {
                return {
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
            }
            else
            {
              return {
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
            }
        },
        methods: {
            userList() {
                this.loading = true;

                axios.get('/api/admin/list')
                .then(response => {
                    this.loading = false;
                    this.users = response.data;
                })
                .catch(error => {
                    this.loading = false;
                    console.log(error.response);
                })
            },
            showAddUserModal() {
              this.$refs.addUserModal.show();
            },
            submitUser(e) {
              e.preventDefault();
              this.saveUser();
            },
            saveUser() {
              this.$v.$touch();

              if (!this.$v.$invalid) {
                this.submitBtn = true;
                if (!this.edit) {
                  axios.post('/api/admin/create', {
                    first_name: this.first_name,
                    last_name: this.last_name,
                    username: this.username,
                    role: this.role,
                    status: this.status,
                    admin_id: this.admin.id,
                  })
                  .then(response => {
                    this.submitBtn = false;
                    if (response.data.success) {
                      Swal('Add User', 'User has been saved', 'success')
                      .then((okay) => {
                        if (okay) {
                            this.$refs.addUserModal.hide();
                            this.cancelCreate();
                            this.userList();
                        }
                      });
                    }
                  })
                  .catch(error => {
                    this.submitBtn = false
                    if (error.response.status == 422) {
                      this.server_errors = error.response.data.errors;
                    }
                  });
                } else {
                  //edit user
                  axios.post('/api/admin/update/'+this.user_id, {
                    first_name: this.first_name,
                    last_name: this.last_name,
                    username: this.username,
                    role: this.role,
                    status: this.status,
                    admin_id: this.admin.id,
                  })
                  .then(response => {
                    this.submitBtn = false;
                    if (response.data.success) {
                      Swal('Edit User', 'User has been updated', 'success')
                      .then((okay) => {
                        if (okay) {
                            this.$refs.addUserModal.hide();
                            this.cancelCreate();
                            this.userList();
                        }
                      });
                    }
                  })
                  .catch(error => {
                    this.submitBtn = false
                    if (error.response.status == 422) {
                      this.server_errors = error.response.data.errors;
                    }
                  });
                }
              }

            },
            cancelCreate() {
              this.first_name = '';
              this.last_name = '';
              this.server_errors = [];
              this.role = false;
              this.edit = false;
              this.submitBtn = false;
              this.modal_title = 'Add user';
              this.ok_title = 'Save';
              this.$nextTick(() => { this.$v.$reset() });
            },
            editUser(id) {
              this.ok_title = 'Update';
              this.modal_title = 'Edit user';
              this.edit = true;
              const user = this.users.find(x => x.id == id);
              this.user_id = user.id;
              this.first_name = user.first_name;
              this.last_name = user.last_name;
              this.role = user.role;
              this.status = (user.status === 'Active') ? false : true;
              this.$refs.addUserModal.show();

            }
        },    
        computed: {
          username() {
              if (!this.first_name && !this.last_name)
              {
                return null;
              }

              return this.first_name.toLowerCase().split(' ').join('')+'.'+this.last_name.toLowerCase().split(' ').join();
          }
        },
        created() {
            this.userList()
        },

    }
</script>
