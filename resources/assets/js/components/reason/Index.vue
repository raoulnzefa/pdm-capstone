<template>
<div class="row">
	<div class="col-md-12">
		<h2 class="mt-4 mb-4">Reasons</h2>
		
		<div class="mb-4">
			<button class="btn btn-primary" @click="addReason"><i class="fa fa-plus"></i> Add reason</button>
		</div>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Reason</th>
					<th>Type</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<template v-if="reason_loading">
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
					<template v-if="!reasons.length">
						<tr>
							<td colspan="4" align="center">No reasons</td>
						</tr>
					</template>
					<template v-else>
						<tr v-for="(reason, index) in reasons" :key="index">
							<td>{{ reason.id }}</td>
							<td>{{ reason.title }}</td>
							<td>{{ reason.type }}</td>
							<td><button class="btn btn-sm btn-primary" @click="editReason(reason)"><i class="fa fa-edit"></i> Edit</button></td>
						</tr>
					</template>
				</template>
			</tbody>
		</table>
		<!-- Modal Component -->
        <b-modal id="reasonModal"
                 ref="reasonModal"
                 :title="modal_title"
                 no-close-on-backdrop
                 no-close-on-esc
                 hide-header-close
                 :ok-title="ok_title"
                 :ok-disabled="submit_reason"
                 @ok="submitReason"
                 @cancel="cancelReason"
                 @hidden="cancelReason">
            <template v-if="server_errors.length != 0">
              <div class="alert alert-danger">
                <ul class="mb-0">
                  <li v-for="(err,index) in server_errors" :key="index">{{ err[0] }}</li>
                </ul>
              </div>
            </template>
            <form @submit.stop.prevent="saveReason">
            	<b-form-group id=""
                          label="Type:"
                          label-for="reasonType">
	                <b-form-select v-model.trim="$v.reason_type.$model" id="reasonType" :class="{'is-invalid':$v.reason_type.$error}" tabindex="1">
				      	<option :value="null" disabled>Please select an option</option>
				      	<option value="Cancellation">Cancellation</option>
				      	<option value="Return">Return</option>
				    </b-form-select>
                <div v-if="$v.reason_type.$error">
                  <span class="error-feedback" v-if="!$v.reason_type.required">Reason type is required</span>
                </div>
              	</b-form-group>
              	<b-form-group id=""
                          label="Reason:"
                          label-for="reasonTitle">
                <b-form-input id="reasonTitle"
                              type="text"
                              tabindex="2"
                              v-model.trim="$v.reason.$model"
                              placeholder="Enter reason"
                              :class="{'is-invalid': $v.reason.$error}">
                </b-form-input>
                <div v-if="$v.reason.$error">
                  <span class="error-feedback" v-if="!$v.reason.required">Reason is required</span>
                  <span class="error-feedback" v-if="!$v.reason.lettersOnly">Reason must be letters only</span>
                  <template v-if="$v.reason.lettersOnly">
                  	<span class="error-feedback d-block" v-if="!$v.reason.maxLength">Reason may not be greater than {{ $v.reason.$params.maxLength.max }} characters</span>	
                  </template>
                  
                </div>
              </b-form-group>
            </form>
          </b-modal>
	</div>
</div>
</template>

<script>
	import { required, minLength, maxLength, helpers } from 'vuelidate/lib/validators';
    import { HalfCircleSpinner } from 'epic-spinners';

    const lettersOnly = helpers.regex('alpha', /^[a-zA-Z\s.]*$/);

	export default {
		props: ['admin'],
		data() {
			return {
				reason: '',
				reason_type: null,
				server_errors: [],
				edit_reason: false,
				modal_title: '',
				ok_title: '',
				submit_reason: false,
				reason_id: '',
				reasons: [],
				reason_loading: false
			}
		},
		validations() {
			return {
				reason: {
					required,
					lettersOnly,
					maxLength: maxLength(200)
				},
				reason_type: {
					required
				}
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			addReason() {
				this.edit_reason = false;
				this.modal_title = 'Add reason';
				this.ok_title = 'Create';
				this.$refs.reasonModal.show();
			},
			submitReason(evt) {
				evt.preventDefault();
				this.saveReason();
			},
			saveReason() {
				this.$v.$touch();
				if (!this.$v.$invalid) {

					this.submit_reason = true;

					if (!this.edit_reason) {

						axios.post('/api/reason', {
							admin_id: this.admin.id,
							title: this.reason,
							type: this.reason_type
						})
						.then((response) => {
							this.submit_reason = false;
							if (response.data.success) {
								Swal('Reason has been created','', 'success')
								.then((okay) => {
									if (okay) {
										this.reason = '';
										this.reason_type = null;
										this.server_errors = [];
										this.$nextTick(() => { this.$v.$reset() });
										this.$refs.reasonModal.hide();
										this.getReasons();
									}
								})
							}
						})
						.catch((error) => {
							this.submit_reason = false;

							if (error.response.status == 422) {
								this.server_errors = error.response.data.errors;
							}
						});
					} else {
						axios.put('/api/reason/'+this.reason_id, {
							admin_id: this.admin.id,
							title: this.reason,
							type: this.reason_type
						})
						.then((response) => {
							this.submit_reason = false;
							if (response.data.success) {
								Swal('Reason has been updated','', 'success')
								.then((okay) => {
									if (okay) {
										this.reason = '';
										this.reason_id = '';
										this.server_errors = [];
										this.$nextTick(() => { this.$v.$reset() });
										this.$refs.reasonModal.hide();
										this.getReasons();
									}
								});
							}
						})
						.catch((error) => {
							this.submit_reason = false;
							if (error.response.status == 422) {
								this.server_errors = error.response.data.errors;
							}
						})
					}
				}
			},
			cancelReason() {
				this.edit_reason = false;
				this.reason = '';
				this.reason_type = null;
				this.reason_id = '';
				this.server_errors = [];
				this.$nextTick(() => { this.$v.$reset() });
			},
			editReason(reason) {
				this.edit_reason = true;
				this.modal_title = 'Edit reason';
				this.ok_title = 'Update';
				this.reason = reason.title;
				this.reason_type = reason.type;
				this.reason_id = reason.id;
				this.$refs.reasonModal.show();
			},
			getReasons() {
				this.reason_loading = true;
				axios.get('/api/reason/list')
				.then((response) => {
					this.reason_loading = false;
					this.reasons = response.data;
				})
				.catch((error) => {
					this.reason_loading = false;
					console.log(error.response);
				});
			}
		},
		created() {
			this.getReasons();
		}
	}
</script>	