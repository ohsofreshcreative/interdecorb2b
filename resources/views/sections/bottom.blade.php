@php
  $tiles = get_field('r_bottom', 'option'); 
  
  $section_id = get_field('section_id', 'option');
  $section_class = get_field('section_class', 'option');
  $gridClass = 'grid-cols-1 md:grid-cols-4'; 
@endphp

@if ($tiles)
  <section 
    @if($section_id) id="{{ $section_id }}" @endif 
    class="b-bottom-section bg-white -smt py-26 {{ $section_class }}"
  >
    <div class="__wrapper c-main">
		<div class="grid {{ $gridClass }} gap-8">
		
		  @foreach ($tiles as $item)
			<div data-gsap-element="card" class="__card relative radius border border-primary p-6">
			  @if (!empty($item['image']['url']))
				<img class="mb-4" src="{{ $item['image']['url'] }}" alt="{{ $item['image']['alt'] ?? '' }}" />
			  @endif
			  @if (!empty($item['title']))
				<b class="!text-body mb-4">{{ $item['title'] }}</b>
			  @endif
			  @if (!empty($item['text']))
				<p class="!text-body">{{ $item['text'] }}</p>
			  @endif
			</div>
		  @endforeach
		</div>
	</div>
  </section>
@endif