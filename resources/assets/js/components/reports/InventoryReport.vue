<template>
<div class="row justify-content-center">
	<div class="col-md-10">
		<div class="card mb-5">
			<div class="card-body">
				<div class="clearfix mb-4">
					<h2 class="float-left">Inventory Report</h2>
				</div>

				<b-tabs>
				  	<b-tab title="Stocks" active>
				  		<br>
				    	<stocks-component :admin="admin"></stocks-component>
				  	</b-tab>
				  	<b-tab title="Critical Level" >
				    	<br>
				    	<critical-level-component :admin="admin"></critical-level-component>
				  	</b-tab>
				  	<b-tab title="Out of Stock" >
				    	<br>
				    	<out-of-stock-component :admin="admin"></out-of-stock-component>
				  	</b-tab>
				</b-tabs>

				<div>
					
				</div><!-- div table -->
				
			</div>
		</div><!-- card -->
	</div>
</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		props:['admin'],
		data() {
			return {
				loading: false,
				inventory_list: [],
				stocks: 0,
				bckend_date: ''
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getInventoryList() {
				this.loading = true;
				axios.get('/api/inventory-report')
				.then((response) => {
					this.loading = false;
					this.inventory_list = response.data.inventory;
					this.stocks = response.data.stocks;
					this.bckend_date = response.data.date_now;
				})
				.catch((error) => {
					this.loading = false;
					console.log(error.response);
				});
			}
		},
		created() {
			//this.getInventoryList();
		}

	}
</script>