<template>
<div class="row justify-content-center">
	<div class="col-md-12">
		<div class="card mb-5">
			<div class="card-body">
				<!-- <div class="clearfix mb-4">
					<h2 class="float-left">Sales Report</h2>
					<button type="button" class="btn btn-dark float-right" v-if="sales_product.length > 0" v-print="'#printSalesReport'"><i class="fa fa-print"></i>&nbsp;Print</button>
				</div> -->
				
				<div>
					<b-tabs>
				  	<b-tab title="Monthly Sales" active>
				  		<br>
				    	<monthly-sales-component :admin="admin"></monthly-sales-component>
				  	</b-tab>
				  	<b-tab title="Yearly Sales">
				  		<br>
				    	<yearly-sales-component :admin="admin"></yearly-sales-component>
				  	</b-tab>
				  	<b-tab title="Return" >
				    	<br>
				    	<sales-return-report :admin="admin"></sales-return-report>
				  	</b-tab>
				</b-tabs>
					
				</div>
			</div><!-- card-body -->
		</div><!-- card -->
		
	</div>
</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		props: ['admin']
	}
</script>