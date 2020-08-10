<template>
<div>
	<div class="col-md-4">
		<div class="form-group">
			<label>First Name:</label>
			<input type="text" name="" class="form-control" readonly v-model="cust.first_name">
		</div>
	</div>
	<div class="col-md-4">
		
	</div>
</div>
</template>
<script>
	export default {
		props: ['cust']
	}
</script>