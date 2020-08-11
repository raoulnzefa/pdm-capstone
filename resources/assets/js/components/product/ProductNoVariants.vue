<template>
	<div class="row">
		<div class="col-md-12">
			<div class="nav">
				<h2 class="mt-4 mb-4">Product no variants</h2>
			</div>
			<div class="row mb-4">
				<div class="col-md-6 clearfix">
					
				</div>
				<div class="col-md-6">
					
					
				</div>
			</div>
			<table class="table table-bordered table-striped table-hovered table-condensed">
				<thead>
					<tr>
						<th>Product #</th>
						<th width="40%">Product</th>
						<th>Category</th>
						<th>Price</th>
						<th>Status</th>
						<th width="18%">Action</th>
					</tr>
				</thead>
				<tbody>
					<template v-if="loading">
						<tr>
							<td colspan="5" align="center">
								<half-circle-spinner
	                                :animation-duration="1000"
	                                :size="30"
	                                color="#ff1d5e"
	                              />
							</td>
						</tr>
					</template>
					<template v-else>
						<template v-if="!product_no_variants.length">
							<tr>
								<td colspan="7" align="center">No Products.</td>
							</tr>
						</template>
						<template  v-else>
							
						</template>
					</template>
				</tbody>
			</table>
			
		</div>
	</div>
</template>

<script>
	import { required, minLength, maxLength, helpers } from 'vuelidate/lib/validators';
	import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		props: ['admin'],
		data() {
			return {
				product_no_variants: [],
				loading: false,

			}
		},
		components: {
			HalfCircleSpinner
		}
	}
</script>