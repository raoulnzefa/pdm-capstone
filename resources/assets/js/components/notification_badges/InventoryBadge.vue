<template>
<span class="badge badge-danger badge-pill ml-auto" v-if="inventoryAlert">{{inventoryAlert}}</span>
</template>
<script>
	export default {
		data() {
			return {
				inventoryAlert: null
			}
		},
		methods: {
			getInventoryAlert() {
				axios.get('/api/inventory/alert')
				.then((response) => {
					if (response.data.count > 0)
					{
						this.inventoryAlert = response.data.count;
					}
					else
					{
						this.inventoryAlert = null;
					}
				})
				.catch((error) => {
					console.log(error.response.status);
				});
			},
		},
		created() {
			this.getInventoryAlert();
			this.$bus.$on('update-inventory-badge', data => {
				if (data == true)
				{
					this.getInventoryAlert()
				}
			})
		}
	}
</script>