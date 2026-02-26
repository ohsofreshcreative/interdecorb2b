<li class="category-item">
	<div class="flex items-center justify-between">
		<button data-category-id="{{ $category->term_id }}" class="category-filter-btn">
			{{ $category->name }}
		</button>
		@if (!empty($category->children))
		<button class="category-toggle-btn p-2 rounded hover:bg-gray-200">
			
			<svg class="h-4 w-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
			</svg>
		</button>
		@endif
	</div>

	@if (!empty($category->children))
	<ul class="subcategory-list pl-4 mt-1 hidden">
		@foreach ($category->children as $child_category)
		@include('partials.category-filter-item', ['category' => $child_category])
		@endforeach
	</ul>
	@endif
</li>