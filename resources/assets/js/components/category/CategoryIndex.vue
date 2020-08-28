<template>
<div class="row">
    <div class="col-md-12 col-sm-12">
        <h2 class="mt-4 mb-4">Categories</h2>
            <div class="mb-4">
              <button type="button" class="btn btn-primary" @click="addCategoryModal"><i class="fas fa-plus"></i> Add category</button>
            </div>
              <table class="table table-bordered table-striped table-hover">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <template v-if="loading">
                    <tr>
                      <td colspan="4" align="center">
                        <half-circle-spinner
                            :animation-duration="1000"
                            :size="30"
                            color="#ff1d5e"
                          />
                      </td>
                    </tr>
                  </template>
                  <template v-else>
                    <template v-if="!categories.length">
                      <tr>
                        <td colspan="4" align="center">No Categories</td>
                      </tr>
                    </template>
                    <template v-else>
                      <tr v-for="(category, index) in categories" :key="index">
                        <td>{{ category.id }}</td>
                        <td>{{ category.name }}</td>
                        <td>{{ (category.status === 1) ? 'Enabled' : 'Disabled' }}</td>
                        <td><button class="btn btn-sm btn-primary" @click="editCategory(category)"><i class="fas fa-edit"></i> Edit</button></td>
                      </tr>
                    </template>
                  </template>
                </tbody>
              </table>
        
        <!-- Modal Component -->
        <b-modal id="categoryModal"
                 ref="categoryModal"
                 :title="modal_title"
                 no-close-on-backdrop
                 no-close-on-esc
                 hide-header-close
                 :ok-title="ok_title"
                 :ok-disabled="submit"
                 @ok="submitCategory"
                 @cancel="cancelCategory"
                 @hidden="cancelCategory"
                 @shown="focusOnCategoryName">
            <template v-if="server_errors.length != 0">
              <div class="alert alert-danger">
                <ul class="mb-0">
                  <li v-for="(err,index) in server_errors" :key="index">{{ err[0] }}</li>
                </ul>
              </div>
            </template>
            <form @submit.stop.prevent="saveCategory">
              <b-form-group id=""
                          label="Category name:"
                          label-for="categoryName">
                <b-form-input id="categoryName"
                              type="text"
                              v-model.trim="$v.category_name.$model"
                              ref="categoryName"
                              :class="{'is-invalid': $v.category_name.$error}">
                </b-form-input>
                <div v-if="$v.category_name.$error">
                  <span class="error-feedback" v-if="!$v.category_name.required">Category name is required</span>
                  <span class="error-feedback" v-if="!$v.category_name.lettersOnly">Category name must contain letters and space only</span>
                  <span class="error-feedback" v-if="!$v.category_name.maxLength">Category name must have at most {{ $v.category_name.$params.maxLength.max }} letters</span>
                </div>
              </b-form-group>
              <div class="form-group">
                <label>Has variant:</label>
                <select class="form-control" v-model="has_variant">
                  <option value="0">No</option>
                  <option value="1">Yes</option>
                </select>
              </div>
              <b-form-group id=""
                          label="Status:"
                          label-for="categDisplay">
                <b-form-select v-model="status" id="categDisplay">
                  <option value="1">Enable</option>
                  <option value="0">Disable</option>
                </b-form-select>
              </b-form-group>
            </form>
          </b-modal>
    </div>
</div>
</template>

<script>
    import { required, minLength, maxLength, helpers } from 'vuelidate/lib/validators';
    import { HalfCircleSpinner } from 'epic-spinners';

    const lettersOnly = helpers.regex('alpha', /^[a-zA-Z\s]*$/);

    export default {
        props:['admin'],
        data() {
         		return {
         			category_name: '',
         			categ_id: '',
              has_variant: 0,
         			categories: [],
         			server_errors: [],
         			edit: false,
  						loading: false,
              submit: false,
              modal_title: 'Add category',
              ok_title: 'Create',
              status: 1,
         		}
  			},
  			components: {
  				HalfCircleSpinner
  			},
        validations() {
          return {
            category_name: {
              required,
              maxLength: maxLength(30),
              lettersOnly
            }
          }
        },
        methods: {
         	  getCategories() {
                this.loading = true;
                axios.get('/api/category/list')
                .then((response) => {
                  this.loading = false;
                  this.categories = response.data;
                })
                .catch((error) => {
                  this.loading = false;
                  console.log(error.response);
                });
            },
            saveCategory() {
              this.$v.$touch();

              if (!this.$v.$invalid) {
                this.submit = true;
                if (!this.edit) {
                  axios.post('/api/category', {
                    admin_id: this.admin.id,
                    name: this.category_name,
                    has_variant: this.has_variant,
                    status: this.status
                  })
                  .then((response) => {
                    this.submit = false;
                    if (response.data.success) {
                      Swal('Category has been created','', 'success')
                      .then((okay) => {
                        if (okay) {
                            this.$refs.categoryModal.hide();
                            this.resetModal();
                            this.getCategories();
                        }
                      });
                    }
                  })
                  .catch((error) => {
                    this.submit = false;
                    if (error.response.status == 422) {
                      this.server_errors = error.response.data.errors;
                    }
                  });
                } else {
                  axios.put('/api/category/'+this.categ_id, {
                    admin_id: this.admin.id,
                    name: this.category_name,
                    has_variant: this.has_variant,
                    status: this.status
                  })
                  .then((response) => {
                    this.submit = false;
                    if (response.data.success) {
                      Swal('Category has been updated','', 'success')
                      .then((okay) => {
                        if (okay) {
                            this.$refs.categoryModal.hide();
                            this.resetModal();
                            this.getCategories();
                        }
                      });
                    }
                  })
                  .catch((error) => {
                    this.submit = false;
                    if (error.response.status == 422) {
                      this.server_errors = error.response.data.errors;
                    }
                  });
                }
              }
            },
            submitCategory(evt) {
              evt.preventDefault();
              this.saveCategory();
            },
            cancelCategory() {
              this.resetModal();
            },
            resetModal() {
              this.category_name = '';
              this.edit = false;
              this.ok_title = 'Create';
              this.modal_title = 'Add category';
              this.has_variant = 0;
              this.server_errors = [];
              this.$nextTick(() => { this.$v.$reset() });
            },
            addCategoryModal(e) {
              this.$refs.categoryModal.show();
            },
            editCategory(category) {
                this.edit = true;
                this.categ_id = category.id;
                this.category_name = category.name;
                this.has_variant = category.has_variant;
                this.modal_title = 'Edit category';
                this.ok_title = 'Update';
                this.$refs.categoryModal.show();
            },
            focusOnCategoryName(e)
            {
              this.$refs.categoryName.focus();
            }
        },
        created() {
          this.getCategories();
        }
    }
</script>
