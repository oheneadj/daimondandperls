@props(['align' => 'left', 'sortable' => null, 'direction' => null])

<th {{ $attributes->merge(['class' => "px-5 py-3.5 text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60" . ($align === 'right' ? ' text-right' : '') . ($sortable ? ' cursor-pointer hover:text-base-content transition-colors group select-none' : '')]) }}
    @if($sortable) wire:click="sortBy('{{ $sortable }}')" @endif
>
    <div class="flex items-center gap-1.5 {{ $align === 'right' ? 'justify-end' : '' }}">
        {{ $slot }}
        @if($sortable)
            <div class="flex flex-col opacity-40 group-hover:opacity-100 transition-opacity {{ $direction ? '!opacity-100' : '' }}">
                @if($direction === 'asc')
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7" /></svg>
                @elseif($direction === 'desc')
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-base-content/50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg>
                @endif
            </div>
        @endif
    </div>
</th>
