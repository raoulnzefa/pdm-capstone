<template>
	<div class="row justify-content-center">
		<div class="col-md-10">
			<div class="card mb-3">
				<div class="card-body">
					<div class="row">
						<div class="col-md-4">
							<h2 class="float-left">User Logs</h2>
						</div>
						<div class="col-md-8">
							<form class="form-inline justify-content-end" @submit.prevent="searchUserLogs">
								<label class="mr-2" for="">From:</label>
								<date-picker v-model="from_date" :config="options" class="mb-2 mr-sm-2"></date-picker>
								<label class="mr-2" for="">To:</label>
								<date-picker v-model="to_date" :config="options" class="mb-2 mr-sm-2"></date-picker>
								<button type="submit" class="btn btn-primary mb-2 mr-1">Search</button>
							</form>
						</div>
					</div><!-- row -->
				</div>
			</div><!-- row -->
			<template v-if="on_search">
				<template v-if="loading">
					<center class>
						<div class="mt-4">
							<half-circle-spinner
	                            :animation-duration="1000"
	                            :size="30"
	                            color="#ff1d5e"
	                          />
						</div>
					</center>
				</template>
				<template v-else>
					<template v-if="!user_logs.length">
						<center>
							<h3 class="text-danger mt-4">No user logs</h3>
						</center>
					</template>
					<template v-else>
						<table class="table table-bordered table-condensed bg-white">
							<thead>
								<tr>
									<th>No.</th>
									<th>Admin ID</th>
									<th>Action</th>
									<th>Created</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(u_log, index) in user_logs" :key="index">
									<td>{{u_log.id}}</td>
									<td>{{u_log.admin_id}}</td>
									<td>{{u_log.action}}</td>
									<td>{{u_log.log_created}}</td>
								</tr>
							</tbody>
						</table>
					</template>
				</template>
			</template>
		</div>
	</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		data() {
			return {
				loading: true,
				from_date: '',
				to_date: '',
				options: {
					format: 'YYYY-MM-DD',
					useCurrent: false,
					viewMode: 'days'
				},
				user_logs: [],
				on_search: false
			}
		},
		components: { HalfCircleSpinner },
		methods: {
			searchUserLogs() {
				this.on_search = true;
				this.loading = true;

				axios.post('/api/search-user-logs', {
					from_date: this.from_date,
					to_date: this.to_date
				})
				.then((response) => {
					this.loading = false;
					this.user_logs = response.data;
				})
				.catch((error) => {
					this.loading = false;
					console.log(error.response);
				});
			}
		}
	}
</script>