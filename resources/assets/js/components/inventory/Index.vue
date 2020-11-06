<template>
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<h2 class="mb-4 mt-4">Inventory</h2>
			<div class="row mb-4">
	        <div class="col-md-3">
	          <b-form-select v-model="filter_by" @change="filterInventory">
	            <option value="in_stock">All stocks</option>
	            <option value="critical_level">Critical level</option>
	            <option value="out_of_stock">Out of stock</option>
	            <option value="defective_products">Defective products</option>
	          </b-form-select>
	        </div>
	        <div class="col-md-5">
	        <template v-if="!loading">
	        		<form ref="reportForm" target="_blank" method="POST" class="hide-when-defective" action="/admin/report/inventory" v-if="inventories.length">
		        		<input type="hidden" name="_token" :value="csrf">
		        		<input type="hidden" name="report_type" :value="filter_by">
		        		<input type="hidden" name="admin_id" :value="admin.id">
		        		<button type="submit" class="btn btn-primary" :disable="generateClicked">Generate report</button>
		        	</form>
		        	<form ref="defectiveReportForm" target="_blank" method="POST" id="defective-report" action="/admin/report/defective-products">
		        		<input type="hidden" name="_token" :value="csrf">
		        		<input type="hidden" name="admin_id" :value="admin.id">
		        		<button type="submit" class="btn btn-primary" :disable="generateClicked">Generate report</button>
		        	</form>
	        </template>
	        </div>
	        <div class="col-md-4">
	          <template v-if="!loading">
	            <div class="input-group hide-when-defective" v-if="inventories.length">
	              <input type="text" class="form-control float-right" placeholder="Search inventory number" v-model="search_val">
	              <div class="input-group-append">
	                <button class="btn btn-secondary" type="button" @click="searchProduct" :disable="on_search"><i class="fa fa-search"></i> Search</button>
	                <div class="input-group-append" v-if="on_search">
	                  <button class="btn btn-outline-danger" type="button" @click="clearSearch">Clear</button>
	                </div>
	              </div>
	            </div>
	          </template>
	        </div>
	      </div>
			<template v-if="filter_by !== 'defective_products'">
				<table class="table table-bordered table-striped table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th width="32%">Product</th>
							<th>Category</th>
							<th>Quantity</th>
							<th width="10%">Crit. level</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<template v-if="loading">
							<tr>
								<td align="center" colspan="6">
									<half-circle-spinner
	                             :animation-duration="1000"
	                             :size="30"
	                             color="#ff1d5e"
	                           />
								</td>
							</tr>
						</template>
						<template v-else>
							<template v-if="!inventories.length">
								<tr>
									<td colspan="6" align="center" v-if="filter_by === 'in_stock'">No products.</td>
									<td colspan="6" align="center" v-if="filter_by === 'critical_level'">No critical levels.</td>
									<td colspan="6" align="center" v-if="filter_by === 'out_of_stock'">No out of stock.</td>
								</tr>
							</template>
							<template v-else>
								<tr v-for="(inventory, index) in inventories" :key="index" :class="inventory.inventory_stock <= inventory.inventory_critical_level ? 'bg-danger text-light' : ''">
									<td class="align-middle">{{ inventory.number  }}</td>
									<td class="align-middle">
										<div class="media">
		                          	<img :src="inventory.product.product_image_url" class="media-object mr-2" width="20%" height="13%">
		                           <div class="media-body pt-4">
		                              <span v-if="inventory.product_with_variant">{{inventory.product.product_name+' '+inventory.product_with_variant.variant_value}}</span>
		                              <span v-else="inventory.product_no_variant">{{inventory.product.product_name }}</span>
		                           </div>
		                       </div>
									</td>
									<td class="align-middle">{{ inventory.product.category.name}}</td>
									<td class="text-success font-weight-bold align-middle">{{ inventory.inventory_stock }}</td>
									<td class="align-middle">{{ inventory.inventory_critical_level }}</td>
									<td class="align-middle">
										<inventory-add-stock :admin="admin" :inventory="inventory"></inventory-add-stock>
									</td>
								</tr>
							</template>
						</template>
					</tbody>
				</table>
				<template v-if="!loading">
					<div class="row" v-if="filter_by === 'in_stock'">
		            <div class="col-md-8">
		              <nav>
		                <ul class="pagination">
		                  <li :class="{disabled:!pagination.first_link}" class="page-item"><a href="javascript:void(0);" @click="getInventory(pagination.first_link)" class="page-link">&laquo;</a></li>
		                  <li :class="{disabled:!pagination.prev_link}" class="page-item"><a href="javascript:void(0);" @click="getInventory(pagination.prev_link)" class="page-link">Prev</a></li>
		                  <li v-for="n in pagination.last_page" v-bind:key="n" :class="{active: pagination.current_page == n}" class="page-item"><a href="javascript:void(0);" @click="getInventory(pagination.path_page + n)" class="page-link">{{ n }}</a></li>
		                  <li :class="{disabled:!pagination.next_link}" class="page-item"><a href="javascript:void(0);" @click="getInventory(pagination.next_link)" class="page-link">Next</a></li>
		                  <li :class="{disabled:!pagination.last_link}" class="page-item"><a href="javascript:void(0);" @click="getInventory(pagination.last_link)" class="page-link">&raquo;</a></li>
		                </ul>
		              </nav>
		            </div>
		            <div class="col-md-4">
		              <p class="text-right">Showing {{ pagination.from_page }} to {{ pagination.to_page }} of {{ pagination.total_page }} {{ (pagination.total_page > 1) ? 'entries' : 'entry'}}</p>
		            </div>
		          </div>
				</template>
			</template>
			<template v-else><!-- defective products here -->
				<table class="table table-hover table-striped table-bordered">
					<thead>
						<tr>
							<th>ID</th>
							<th>Inventory ID</th>
							<th>Product</th>
							<th>Replaced Qty.</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<template v-if="defective_loading">
							<tr>
								<td align="center" colspan="5">
									<half-circle-spinner
                             :animation-duration="1000"
                             :size="30"
                             color="#ff1d5e"
                           />
								</td>
							</tr>
						</template>
						<template v-else>
							<template v-if="defectiveProducts.length">
								<tr v-for="(item, index) in defectiveProducts" :key="index">
									<td>{{item.id}}</td>
									<td>{{item.inventory_number}}</td>
									<td>{{item.inventory.product.product_name}}</td>
									<td>{{item.replacement_request.quantity}}</td>
									<td><button class="btn btn-sm btn-primary">View</button></td>
								</tr>
							</template>
							<template v-else>
								<tr>
									<td align="center" colspan="5">No defective products.</td>
								</tr>
							</template>
						</template>
					</tbody>
				</table>
			</template>
		</div>
	</div>
</template>
<script>
	import { HalfCircleSpinner } from 'epic-spinners'

	export default {
		props:['admin'],
		data() {
			return {
				inventories: [],
				generateClicked: false,
				csrf: document.head.querySelector('meta[name="csrf-token"]').content,
				loading: false,
				defective_loading: false,
				search_val: null,
				pagination: [],
				filter_inventory: null,
				on_search: false,
				filter_by: 'in_stock',
				defectiveProducts: [],
			}
		},
		components: {
			HalfCircleSpinner
		},
		methods: {
			getInventory(pagi) {
				this.loading = true;
				this.generateClicked = true;

				if (this.search_val)
				{
					pagi = '/api/inventory/get/?search='+this.search_val;
					//this.getFilteredInventory(pagi);
					
				}
				else if (this.filter_by)
				{
					pagi = pagi || '/api/inventory/get/?filterBy='+this.filter_by;
					//this.getFilteredInventory(pagi);
				}
				else
				{
					pagi = pagi || '/api/inventory/get';
				}

				axios.get(pagi)
				.then(response => {
					this.loading = false;
					this.generateClicked = false;
					this.inventories = response.data.data;
					this.pagination = {
	       				current_page: response.data.current_page,
	       				last_page: response.data.last_page,
	       				from_page: response.data.from,
	       				to_page: response.data.to,
	       				total_page: response.data.total,
	       				path_page: response.data.path+'?page=',
	       				first_link: response.data.first_page_url,
	       				last_link: response.data.last_page_url,
	       				prev_link: response.data.prev_page_url,
	       				next_link: response.data.next_page_url
	       			};
				})
				.catch(error => {
					this.loading = false;
					this.generateClicked = false;
					console.log(error.response);
				});

			},
			getDefectiveProducts() {
				this.defective_loading = true;

				axios.get('/api/get-defective-products')
				.then(response => {
					this.defectiveProducts = response.data;
					this.defective_loading = false;
				})
				.catch(error => {
					this.defective_loading = false;
					console.log(error.response);
				});
			},
			searchProduct() {
				this.on_search = true;

				if (this.search_val) {
					this.getInventory();
				}

			},
			clearSearch() {
				this.on_search = false;
				this.search_val = null;
				this.getInventory();
			},
			filterInventory(e) {
				this.filter_by = e;
				
				if (this.filter_by !== 'defective_products') {
					this.getInventory();
				} else {
					const hideItems = document.querySelectorAll('.hide-when-defective');
					hideItems.forEach((item) => {
						item.hidden = true;
					})
					const defectiveReportBtn = document.getElementById('defective-report');
					defectiveReportBtn.style.display = 'block';
					this.getDefectiveProducts();
				}

			},
			generateReport() {
				alert('report');
			},
			getAllStocks() {
				axios.get('/api/inventory/get')
				.then(response => {
					this.loading = false;
					this.generateClicked = false;
					this.inventories = response.data.data;
					this.pagination = {
	       				current_page: response.data.current_page,
	       				last_page: response.data.last_page,
	       				from_page: response.data.from,
	       				to_page: response.data.to,
	       				total_page: response.data.total,
	       				path_page: response.data.path+'?page=',
	       				first_link: response.data.first_page_url,
	       				last_link: response.data.last_page_url,
	       				prev_link: response.data.prev_page_url,
	       				next_link: response.data.next_page_url
	       			};
				})
				.catch(error => {
					this.loading = false;
					this.generateClicked = false;
					console.log(error.response);
				});
			},
			getFilteredInventory(pagi) {
				axios.get(pagi)
				.then(response => {
					this.loading = false;
					this.generateClicked = false;
					this.inventories = response.data;
				})
				.catch(error => {
					this.loading = false;
					this.generateClicked = false;
					console.log(error.response);
				});
			},
		},
		mounted() {
			this.getInventory();
			this.$bus.$on('refreshInventoryTable', data => {
				if (data === true) {
					this.getInventory();
				}
			});
		}
	}
</script>