<template>
<span class="badge badge-danger badge-pill" v-if="statusUpdate">{{statusUpdate}}</span>
</template>
<script>
	export default {
		props: ['customer'],
		data() {
			return {
				statusUpdate: null
			}
		},
		methods: {
			getReplacementStatusUpdate() {
				axios.get('/api/request-status-update/'+this.customer.id)
				.then((response) => {
					if (response.data.count > 0)
					{
						this.statusUpdate = response.data.count;
					}
					else
					{
						this.statusUpdate = null;
					}
				})
				.catch((error) => {
					console.log(error.response.status);
				});
			},
		},
		created() {
			this.getReplacementStatusUpdate();
			this.$bus.$on('replacement-status-badge', data => {
				if (data == true)
				{
					this.getReplacementStatusUpdate()
				}
			})
		}
	}
</script>