<div class="p-2 space-y-2 bg-gra-200 shadow">
    <h2 class="font-bold">{{ $component->street }} {{ $component->house_number }}, {{ $component->zipcode }} {{ $component->city }}</h2>
    @if (!empty($component->description_en ))
        <p class="text-sm h-20 overflow-scroll overflow-x-hidden">{{ $component->description_en }}</p>
    @else
        <p class="text-sm h-20 overflow-scroll overflow-x-hidden">No english description available</p>
    @endif
</div>
