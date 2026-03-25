@props([
    'search' => null, // Placeholder for search wire:model
    'filters' => null, // Slot for filter buttons
    'actions' => null, // Slot for global actions
    'header' => null, // thead tr content
    'footer' => null, // tfoot content
    'pagination' => null, // Slot for pagination links
])

<div {{ $attributes->merge(['class' => 'bg-white border border-base-content/5 shadow-sm rounded-lg flex flex-col overflow-hidden max-w-full']) }}>
    <!-- Table Toolbar -->
    @if($search !== null || $filters || $actions)
        <div class="px-4 py-4 bg-transparent border-b border-base-content/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex-1 max-w-xs">
                @if($search !== null)
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3 flex items-center text-base-content/60/40 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input 
                            wire:model.live.debounce.300ms="{{ $search }}" 
                            type="text" 
                            placeholder="{{ __('Search...') }}"
                            class="w-full pl-9 pr-4 py-2 bg-base-200 border border-base-content/10 rounded-lg  text-[13px] text-base-content placeholder:text-base-content/40 focus:border-dp-rose focus:ring-3 focus:ring-dp-rose-soft outline-none transition-all"
                        >
                    </div>
                @endif
            </div>
            
            <div class="flex items-center gap-3">
                @if($filters)
                    <div class="flex items-center gap-2">
                        {{ $filters }}
                    </div>
                @endif
                
                @if($actions)
                    <div class="flex items-center gap-2 @if($filters) border-l border-base-content/10 pl-3 @endif">
                        {{ $actions }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Table Container -->
    <div class="overflow-x-auto flex-1">
        <table class="w-full text-left border-collapse min-w-full">
            <thead class="bg-base-200-mid/50 border-b border-base-content/5 sticky top-0 z-10">
                <tr class="text-dp-xs text-base-content/60">
                    {{ $header }}
                </tr>
            </thead>
            <tbody class=" text-[13px] text-dp-text-body divide-y divide-base-content/5">
                {{ $slot }}
            </tbody>
            @if($footer)
                <tfoot class="bg-base-200/30 border-t border-base-content/10">
                    <tr class=" text-[11px] font-bold text-base-content/60">
                        {{ $footer }}
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>

    <!-- Pagination -->
    @if($pagination)
        <div class="px-6 py-4 bg-transparent border-t border-base-content/5">
            {{ $pagination }}
        </div>
    @endif
</div>
