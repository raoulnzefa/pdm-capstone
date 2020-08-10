<template>
<span class="badge badge-danger" v-if="order_not_view">{{order_not_view}}</span>
</template>
<script>
	export default {
		data() {
			return {
				order_not_view: null
			}
		},
		methods: {
			getOrderNotView() {
				axios.get('/api/order/not-view')
				.then((response) => {
					if (response.data.count > 0)
					{
						this.order_not_view = response.data.count;
					}
					else
					{
						this.order_not_view = null;
					}
				})
				.catch((error) => {
					console.log(error.response.status);
				});
			},
		},
		created() {
			this.getOrderNotView();
			this.$bus.$on('update-badge', data => {
				if (data == true)
				{
					this.getOrderNotView()
				}
			})
		}
	}
</script>