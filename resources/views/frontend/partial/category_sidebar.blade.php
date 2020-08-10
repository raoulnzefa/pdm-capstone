<h3 class="ifg-header mb-3">Category</h3>
@if (count($categories) > 0)
	<ul id="category-list">
	@foreach ($categories as $categ)
		<li><a href="/products/category/{{$categ->url}}">{{$categ->name}}</a></li>
	@endforeach
	</ul>
@else
	<div class="alert alert-warning">
		There no categories.
	</div>
@endif