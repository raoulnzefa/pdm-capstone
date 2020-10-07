<template>
	<div class="row">
		<div class="col-md-12">
			<h2 class="mb-4 mt-4">Replacements</h2>
			<table class="table table-bordered table-hover table-striped">
				<thead>
					<tr>
						<th></th>
						<th>ID</th>
						<th>Order No.</th>
						<th width="50%">Product</th>
						<th>Qty.</th>
						<th>Status</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<template v-if="loading">
						<tr>
							<td colspan="7" class="text-center">
								<center>
									<half-circle-spinner
	                         :animation-duration="1000"
	                         :size="30"
	                         color="#ff1d5e"
	                       />
								</center>
							</td>
						</tr>
					</template>
					<template v-else>
						<template v-if="replacements.length">
							<tr v-for="(item, index) in replacements" :key="index">
								<td class="align-middle"><span style="font-size: 20px;" :class="item.viewed > 0 ? 'text-secondary' : 'text-danger'"><i class="fa fa-exclamation-circle"></i></span></td>
								<td class="align-middle">{{item.id}}</td>
								<td class="align-middle">{{item.order_number}}</td>
								<td class="align-middle">
									<div class="media">
		                        <img :src="item.inventory.product.product_image_url" class="media-object mr-2" width="18%" height="12%" alt="product image">
		                        <div class="media-body pt-4">
		                           <span class="d-block">{{ item.product_name }}</span>
		                        </div>
		                     </div>
								</td>
								<td class="align-middle">{{item.quantity}}</td>
								<td class="align-middle">
									<span class="badge badge-success" style="font-size: 14px;" v-if="item.status === 'Replaced'">{{item.status}}</span>
									<span class="badge badge-primary" style="font-size: 14px;" v-if="item.status === 'Approved'">{{item.status}}</span>
									<span class="badge badge-danger" style="font-size: 14px;" v-if="item.status === 'Declined'">{{item.status}}</span>
									<span class="badge badge-warning" style="font-size: 14px;" v-if="item.status === 'Pending'">{{item.status}}</span>
								</td>
								<td class="align-middle text-center"><a :href="'/admin/replacement/'+item.id" class="btn btn-sm btn-primary">Details</a></td>
							</tr>
						</template>
						<template v-else>
							<tr>
								<td class="text-center" colspan="7">No replacements.</td>
							</tr>
						</template>
					</template>
				</tbody>
			</table>
		</div>
	</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners';

	export default {
		props: ['admin'],
		data() {
			return {
				replacements: [],
				loading: false,
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getCustomerReplacements() {
				this.loading =  true;
				axios.get('/api/replacements')
				.then(response => {
					this.loading = false;
					this.replacements = response.data;
				})
				.catch(error => {
					this.loading = false;
				});
			}
		},
		mounted() {
			this.getCustomerReplacements();
		}
	}
</script>