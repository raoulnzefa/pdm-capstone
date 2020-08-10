<template>
<span class="badge badge-danger" v-if="return_request_not_view">{{return_request_not_view}}</span>	
</template>
<script>
	export default {
		data() {
			return {
				return_request_not_view: null
			}
		},
		methods: {
			getReturnRequestNotView() {
				axios.get('/api/return/request/not-view')
				.then((response) => {
					if (response.data.count > 0)
					{
						this.return_request_not_view = response.data.count;
					}
					else
					{
						this.return_request_not_view = null;
					}
				})
				.catch((error) => {
					console.log(error.response.status);
				});
			},
		},
		created() {
			this.getReturnRequestNotView();
			this.$bus.$on('update-return-badge', data => {
				if (data == true)
				{
					this.getReturnRequestNotView()
				}
			})
		}
	}
</script>