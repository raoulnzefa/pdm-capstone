<template>
<div class="row justify-content-center">
	<div class="col-md-8">
		<a :href="previous_url" class="mb-2 text-secondary d-block"><i class="fa fa-chevron-left"></i>&nbsp;Back</a>
		<div class="card">
			<div class="card-body">
				
			</div><!-- card-body -->
		</div><!-- card -->
	</div>
</div>
</template>
<script>
import { required, minLength, maxLength, alphaNum } from 'vuelidate/lib/validators';
import { HalfCircleSpinner } from 'epic-spinners'

export default {
	props: ['id', 'previous_url', 'order_num', 'admin'],

}
</script>
