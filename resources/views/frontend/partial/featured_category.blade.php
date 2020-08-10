<h3 class="mb-3">Category</h3>
@if (count($categories) > 0)
	<ul id="category-list">
	@foreach ($categories as $categ)
		<li><a href="/featured-products/category/{{$categ->url}}">{{$categ->name}}</a></li>
	@endforeach
	</ul>
@else
	<p class="mt-4">There no categories.</p>
@endif