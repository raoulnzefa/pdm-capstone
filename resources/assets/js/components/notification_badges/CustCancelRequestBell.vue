<template>
<span v-if="has_notif">
	<h5 class="float-right mb-0"><i class="fa fa-bell text-danger"></i></h5>
</span>
</template>
<script>
	export default {
		data() {
			return {
				has_notif: false
			}
		},
		methods: {
			cancellationNotif() {
				axios.get('/api/cancellation-notif')
				.then((response) => {
					this.has_notif = response.data;
				})
				.catch((error) => {
					console.log(error.response);
				});
			}
		},
		created() {
			this.cancellationNotif();
		}
	}
</script>