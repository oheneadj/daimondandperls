<div class="space-y-10 pb-10">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class=" text-[36px] font-semibold text-base-content leading-tight">
                {{ __('Culinary Collections') }}
            </h1>
            <p class=" text-base-content/60 font-normal mt-2">
                {{ __('Organize your portfolio into logical, thematic groupings.') }}
            </p>
        </div>
        
        <x-ui.button variant="primary" size="sm" href="{{ route('admin.categories.create') }}" wire:navigate class="shadow-sm" title="{{ __('Define New Collection') }}">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </x-slot:icon>
            {{ __('Define New Collection') }}
        </x-ui.button>
    </div>

    <!-- Toolbar -->
    <x-ui.card class="bg-base-200 border-base-content/10/50">
        <div class="relative w-full md:max-w-md">
            <span class="absolute inset-y-0 left-4 flex items-center text-base-content/60/50 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <x-ui.input wire:model.live.debounce.300ms="search" placeholder="Search collections..." class="pl-12 rounded-full border-base-content/10/60" />
        </div>
    </x-ui.card>

    @if($errors->has('error'))
        <x-ui.alert type="danger" class="shadow-sm">
            {{ $errors->first('error') }}
        </x-ui.alert>
    @endif

    <!-- Collections Table -->
    <x-ui.card padding="none" class="overflow-hidden shadow-sm">
        <x-ui.table>
            <x-slot:header>
                <x-ui.table.header>{{ __('Collection Name') }}</x-ui.table.header>
                <x-ui.table.header>{{ __('Reference Slug') }}</x-ui.table.header>
                <x-ui.table.header>{{ __('Package Count') }}</x-ui.table.header>
                <x-ui.table.header>{{ __('Creation Date') }}</x-ui.table.header>
                <x-ui.table.header class="text-right">{{ __('Actions') }}</x-ui.table.header>
            </x-slot:header>
            
            <tbody>
                @forelse($categories as $category)
                    <x-ui.table.row wire:key="category-{{ $category->id }}" class="group">
                        <x-ui.table.cell>
                            <span class="text-[13px] font-semibold text-base-content group-hover:text-primary transition-colors">{{ $category->name }}</span>
                        </x-ui.table.cell>
                        
                        <x-ui.table.cell>
                            <code class="font-mono text-[11px] bg-base-200-mid px-2 py-1 rounded border border-base-content/10/40 text-base-content/60">{{ $category->slug }}</code>
                        </x-ui.table.cell>
                        
                        <x-ui.table.cell>
                            <x-ui.badge variant="ghost" class="bg-base-200-mid/50 border-base-content/10/30 font-bold">
                                {{ $category->packages()->count() }} {{ __('Packages') }}
                            </x-ui.badge>
                        </x-ui.table.cell>
                        
                        <x-ui.table.cell>
                            <span class="text-[13px] text-base-content/60">{{ $category->created_at->format('M j, Y') }}</span>
                        </x-ui.table.cell>
                        
                        <x-ui.table.cell class="text-right">
                            <div class="flex justify-end gap-2">
                                <x-ui.button variant="ghost" size="icon-sm" href="{{ route('admin.categories.edit', $category) }}" wire:navigate title="{{ __('Edit') }}">
                                    <x-slot:icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </x-slot:icon>
                                </x-ui.button>
                                <x-ui.button variant="ghost" size="icon-sm" wire:click="deleteCategory({{ $category->id }})" wire:confirm="{{ __('Are you sure you want to retire this collection?') }}" title="{{ __('Delete') }}">
                                    <x-slot:icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </x-slot:icon>
                                </x-ui.button>
                            </div>
                        </x-ui.table.cell>
                    </x-ui.table.row>
                @empty
                    <x-ui.table.row>
                        <x-ui.table.cell colspan="5" class="text-center py-24">
                            <div class="flex flex-col items-center justify-center space-y-4">
                                <div class="w-20 h-20 rounded-full bg-base-200-mid flex items-center justify-center text-base-content/60/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <div class="space-y-1">
                                    <h3 class=" text-[22px] font-semibold text-base-content">{{ __('No Collections Found') }}</h3>
                                    <p class=" text-[14px] text-base-content/60 font-medium max-w-xs mx-auto">
                                        @if($search)
                                            {{ __('No thematic groupings match your current search.') }}
                                        @else
                                            {{ __('Begin organizing your portfolio by defining your first collection.') }}
                                        @endif
                                    </p>
                                </div>
                                @if($search)
                                    <x-ui.button variant="outline" size="sm" wire:click="$set('search', '')" class="mt-4">
                                        {{ __('Reset Search') }}
                                    </x-ui.button>
                                @endif
                            </div>
                        </x-ui.table.cell>
                    </x-ui.table.row>
                @endforelse
            </tbody>
        </x-ui.table>
    </x-ui.card>
    
    @if($categories->hasPages())
        <div class="mt-10 py-6 border-t border-base-content/10 flex justify-center">
            <div class="text-[11px] font-bold uppercase tracking-[0.2em] text-base-content/60">
                {{ $categories->links() }}
            </div>
        </div>
    @endif
</div>
