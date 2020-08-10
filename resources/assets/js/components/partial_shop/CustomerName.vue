<template>
<a class="nav-link text-light" href="/my-account"><i class="fa fa-user"></i>&nbsp;{{ full_name }}</a>
</template>

<script>
	export default {
		props: ['customer'],
		data() {
			return {
				full_name: ''
			}
		},
		methods: {
			customerName() {
				axios.get('/api/customer/name/'+this.customer.id)
				.then(response => {
					let data = response.data

					if (data.success)
					{
						this.full_name = data.cust.first_name+' '+data.cust.last_name
					}
				})
			}
		},
		created() {
			this.customerName()

			this.$bus.$on('update-name', data => {
				if (data == true)
				{
					this.customerName()
				}
			})
		}

	}
</script>