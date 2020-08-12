<template>
<div class="row">
	<div class="col-md-12 col-sm-12">
	
		<h2 class="mt-4 mb-4">Bank account</h2>
		<button type="button" class="btn btn-primary mb-4" @click="addBankAccount"><i class="fa fa-plus"></i> Add bank account</button>
		<div>
			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>ID</th>
						<th>Bank</th>
						<th>Full name</th>
						<th>Account number</th>
						<th>Default</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<template v-if="loading">
						<tr>
							<td colspan="6" align="center">
								<half-circle-spinner
		                            :animation-duration="1000"
		                            :size="30"
		                            color="#ff1d5e"
		                          />
							</td>
						</tr>
					</template>
					<template v-else>
						<template v-if="!bank_accounts.length">
							<tr>
								<td colspan="6" align="center">No bank accounts.</td>
							</tr>
						</template>
						<template v-else>
							<tr v-for="(b_account, index) in bank_accounts" :key="index">
								<td>{{b_account.id}}</td>
								<td>{{b_account.bank_name}}</td>
								<td>{{b_account.first_name+' '+b_account.middle_initial+' '+b_account.last_name}}</td>
								<td>{{b_account.number}}</td>
								<td>
									<template v-if="b_account.active > 0">
										Activated
									</template>
									<template v-else>
										<a href="javascript:void(0);" @click="setAsDefault(b_account)">Set</a>
									</template>
								</td>
								<td>
									<button type="button" class="btn btn-sm btn-primary" @click="editBankAccount(b_account)"><i class="fa fa-edit"></i> Edit</button>
									<button type="button" class="btn btn-sm btn-danger" @click="deleteBankAccount(b_account.id)"><i class="fa fa-trash"></i> Delete</button>
								</td>
							</tr>
						</template>
					</template>
				</tbody>
			</table>
		</div>

		<!-- Modal Component -->
        <b-modal id="bankAccountModal"
                 ref="bankAccountModal"
                 :title="modal_title"
                 no-close-on-backdrop
                 no-close-on-esc
                 hide-header-close
                 :ok-title="ok_title"
                 :ok-disabled="submit"
                 @ok="submitBankAccount"
                 @cancel="cancelBankAccount"
                 @hidden="cancelBankAccount"
                 @shown="focusOnBankName">
            <template v-if="server_errors.length != 0">
              <div class="alert alert-danger">
                <ul class="">
                  <li v-for="(err,index) in server_errors" :key="index">{{ err[0] }}</li>
                </ul>
              </div>
            </template>
            <form @submit.stop.prevent="saveBankAccount">
              <b-form-group id=""
                          label="Bank name:"
                          label-for="bankName">
                <b-form-input id="bankName"
                              type="text"
                              tabindex="1"
                              ref="bank_Name"
                              v-model.trim="$v.bank_name.$model"
                              :class="{'is-invalid':$v.bank_name.$error}">
                </b-form-input>
                <div v-if="$v.bank_name.$error">
                	<span class="error-feedback" v-if="!$v.bank_name.required">Bank name is required</span>
                  
                </div>
              </b-form-group>
               <b-form-group id=""
                          label="Firstname:"
                          label-for="fName">
                <b-form-input id="fName"
                              type="text"
                              tabindex="2"
                              ref="first_Name"
                              v-model.trim="$v.first_name.$model"
                              :class="{'is-invalid':$v.first_name.$error}">
                </b-form-input>
                <div v-if="$v.first_name.$error">
                	<span class="error-feedback" v-if="!$v.first_name.required">Firstname is required</span>
                </div>
              </b-form-group>
              <b-form-group id=""
                          label="Middle initial:"
                          label-for="mInitial">
                <b-form-input id="mInitial"
                              type="text"
                              tabindex="3"
                              ref="Middle_Initial"
                              v-model.trim="$v.middle_initial.$model"
                              :class="{'is-invalid':$v.middle_initial.$error}">
                </b-form-input>
                <div v-if="$v.middle_initial.$error">
                	<span class="error-feedback" v-if="!$v.middle_initial.required">Middle initial is required</span>
                </div>
              </b-form-group>
               <b-form-group id=""
                          label="Lastname:"
                          label-for="lastName">
                <b-form-input id="lastName"
                              type="text"
                              tabindex="4"
                              ref="last_Name"
                              v-model.trim="$v.last_name.$model"
                              :class="{'is-invalid':$v.last_name.$error}">
                </b-form-input>
                <div v-if="$v.last_name.$error">
                	<span class="error-feedback" v-if="!$v.last_name.required">Lastname is required</span>
                </div>
              </b-form-group>
               <b-form-group id=""
                          label="Account Number:"
                          label-for="accountNumber">
                <b-form-input id="accountNumber"
                              type="text"
                              tabindex="5"
                              ref="account_Number"
                              v-model.trim="$v.account_no.$model"
                              :class="{'is-invalid':$v.account_no.$error}">
                </b-form-input>
                <div v-if="$v.account_no.$error">
                	<span class="error-feedback" v-if="!$v.account_no.required">Account number is required</span>
                </div>
              </b-form-group>
            </form>
          </b-modal>
	</div>
</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';
	import { required, minLength, maxLength, helpers } from 'vuelidate/lib/validators';

	const lettersOnly = helpers.regex('lettersOnly', /^([A-Za-z])[A-Za-z\s]*$/);

	const numbersOnly = helpers.regex('numbersOnly', /^([0-9])[0-9]*$/);

	export default {
		components: { HalfCircleSpinner },
		props: ['admin'],
		data() {
			return {
				loading: false,
				bank_account_id: '',
				bank_accounts: [],
				server_errors: [],
				bank_name: '',
				first_name: '',
				middle_initial: '',
				last_name: '',
				account_no: '',
				edit: false,
				submit: false,
				modal_title: '',
				ok_title: '',
				active: ''
			}
		},
		validations() {
			return {
				bank_name: {
					required,
					maxLength: maxLength(45),
				},
				first_name: {
					required,
					maxLength: maxLength(20),
				},
				middle_initial: {
					required
				},
				last_name: {
					required,
					maxLength: maxLength(20),
				},
				account_no: {
					required,
					maxLength: maxLength(20)
				}
			}
		},
		methods: {
			addBankAccount(e) {
				this.modal_title = 'Add bank account';
				this.ok_title = 'Create';
				this.$refs.bankAccountModal.show();
			},
			submitBankAccount(evt) {
				evt.preventDefault();
				this.saveBankAccount();
			},
			saveBankAccount() {
				this.$v.$touch();

				if (!this.$v.$invalid)
				{
					this.submit = true;

					if (!this.edit)
					{
						// save
						this.save_clicked = true;
						axios.post('/api/bank-account', {
							admin_id: this.admin.id,
							bank_name: this.bank_name,
							first_name: this.first_name,
							middle_initial: this.middle_initial,
							last_name: this.last_name,
							account_number: this.account_no
						})
						.then((response) => {
							this.submit = false;
							if (response.data.success)
							{
								Swal('Bank account has been created','', 'success');
								this.resetBankAccount();
								this.$refs.bankAccountModal.hide();
								this.getBankAccounts();
							}
						})
						.catch((error) => {
							this.submit = false;
							if (error.response.status == 422) {
		                    	this.server_errors = error.response.data.errors;
		                    }
						});
					}
					else
					{
						
						axios.put('/api/bank-account/'+this.bank_account_id, {
							admin_id: this.admin.id,
							bank_name: this.bank_name,
							first_name: this.first_name,
							middle_initial: this.middle_initial,
							last_name: this.last_name,
							account_number: this.account_no
						})
						.then((response) => {
							this.submit = false;
							if (response.data.success)
							{
								Swal('Bank account has been updated','', 'success');
								
								this.resetBankAccount();
								this.$refs.bankAccountModal.hide();
								this.getBankAccounts();
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
			cancelBankAccount(e) {
				this.resetBankAccount();
				this.$refs.bankAccountModal.hide();
			},
			resetBankAccount() {
				this.modal_title = '';
				this.ok_title = '';
				this.bank_name = '';
				this.first_name = '';
				this.last_name = '';
				this.middle_initial = '';
				this.account_no = '';
				this.server_errors = [];
				this.$nextTick(() => { this.$v.$reset() });
				this.edit = false;
				this.submit = false;
			},
			focusOnBankName(e) {
				this.$refs.bank_Name.focus();
			},
			getBankAccounts() {
				this.loading = true;
				axios.get('/api/bank-accounts')
				.then((response) => {
					this.loading = false;
					this.bank_accounts = response.data;
				})
				.catch((error) => {
					this.loading = false;
					console.log(error.response);
				});
			},
			editBankAccount(bankAccount) {
				this.edit = true;
				this.modal_title = 'Edit bank account';
				this.ok_title = 'Update';
				this.bank_account_id = bankAccount.id;
				this.bank_name = bankAccount.bank_name;
				this.first_name = bankAccount.first_name;
				const initial = bankAccount.middle_initial;
				this.middle_initial = initial.replace(/\./g,""); // remove dot
				this.last_name = bankAccount.last_name;
				this.account_no = bankAccount.number;
				this.server_errors = [];
				this.$nextTick(() => { this.$v.$reset() });
				this.submit = false;
				this.$refs.bankAccountModal.show();
			},
			deleteBankAccount(bank_account_id) {
				Swal({
				  title: 'Are you sure you want to delete this bank account?',
				  text: '',
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonText: 'Yes',
				  cancelButtonText: 'No'
				}).then((result) => {
				  if (result.value) {
					axios.delete('/api/bank-account/'+bank_account_id+'/'+this.admin.id)
					.then(response => {
						if (response.data.success) {
							this.getBankAccounts();
						}

					})
					.catch(error => {
						console.log(error.response)
					})
				  }
				});
			},
			setAsDefault(b_account) {
				axios.put('/api/bank-account/default/'+b_account.id)
				.then((response) => {
					if (response.data.success) {
						Swal('Bank account has been set as default','','success');
						this.getBankAccounts();
					}
				})
				.catch((error) => {
					console.log(error.response);
				});
			}
		},
		created() {
			this.getBankAccounts();
		}
	}
</script>