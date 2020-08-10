<template>
<span>
	<template v-if="loading">
		<center><half-circle-spinner
          :animation-duration="1000"
          :size="20"
          color="#ff1d5e"
        /></center>
   </template>
   <template v-else>
    	<i class="fa fa-fw fa-user nav-icon"></i> {{ adminName }}
   </template>
</span>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		props: ['admin'],
		data() {
			return {
				adminName: '',
				loading: false,
			}
		},
		components: {
  			HalfCircleSpinner
  		},
		methods: {
			updateUserName() {
				this.loading = true;
				axios.get('/api/admin/name/'+this.admin.id)
				.then(response => {
					this.loading = false;
					let res = response.data
					
					this.adminName = res.first_name+' '+res.last_name
				})
				.catch(error => {
					this.loading = false;
					console.log(error.response)
				})
			}
		},
		created() {
			this.updateUserName()
			this.$bus.on('update-username', this.updateUserName)
		}
	}
</script>