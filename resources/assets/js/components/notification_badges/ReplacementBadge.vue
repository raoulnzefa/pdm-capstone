<template>
<span class="badge badge-danger badge-pill ml-auto" v-if="request_not_view">{{request_not_view}}</span>
</template>
<script>
	export default {
		data() {
			return {
				request_not_view: null
			}
		},
		methods: {
			getReplacementNotView() {
				axios.get('/api/request-not-view')
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
			this.getReplacementNotView();
			this.$bus.$on('update-replacement-badge', data => {
				if (data == true)
				{
					this.getReplacementNotView()
				}
			})
		}
	}
</script>