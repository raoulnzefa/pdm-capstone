<template>
<span class="badge badge-danger" v-if="request_not_view">{{request_not_view}}</span>	
</template>
<script>
	export default {
		data() {
			return {
				request_not_view: null
			}
		},
		methods: {
			getRequestNotView() {
				axios.get('/api/requests/not-view')
				.then((response) => {
					if (response.data.count > 0)
					{
						this.request_not_view = response.data.count;
					}
					else
					{
						this.request_not_view = null;
					}
				})
				.catch((error) => {
					console.log(error.response.status);
				});
			},
		},
		created() {
			this.getRequestNotView();
			this.$bus.$on('update-cancel-badge', data => {
				if (data == true)
				{
					this.getRequestNotView()
				}
			})
			this.$bus.$on('update-return-badge', data => {
				if (data == true)
				{
					this.getRequestNotView()
				}
			})
		}
	}
</script>