@props([
    'icon' => 'magnifying-glass',
    'title' => __('No records found'),
    'description' => __('Try adjusting your filters or search terms to find what you looking for.'),
    'colspan' => 1,
])

<tr>
    <td colspan="{{ $colspan }}" class="py-24 text-center">
        <div class="flex flex-col items-center justify-center space-y-4">
            <div class="size-20 bg-base-200-mid/50 rounded-full flex items-center justify-center text-base-content/60/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    @if($icon === 'magnifying-glass')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    @endif
                </svg>
            </div>
            <div class="space-y-1">
                <h3 class="text-dp-2xl text-base-content  font-bold">{{ $title }}</h3>
                <p class="text-dp-sm text-base-content/60 max-w-xs mx-auto italic ">
                    {{ $description }}
                </p>
            </div>
        </div>
    </td>
</tr>
