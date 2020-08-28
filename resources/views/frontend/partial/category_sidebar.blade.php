<h3 class="ifg-header mb-3">Category</h3>
@if (count($categories) > 0)
	<ul id="category-list">
	@foreach ($categories as $categ)
		<li class="mb-1"><a href="/products?ct={{$categ->url}}">{{$categ->name}}</a></li>
	@endforeach
	</ul>
@else
	<div class="alert alert-warning">
		No categories.
	</div>
@endif