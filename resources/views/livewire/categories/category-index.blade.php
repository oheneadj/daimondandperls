<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-[28px] font-semibold text-base-content leading-tight">
                {{ __('Manage Collections') }}
            </h1>
            <p class="text-[14px] text-base-content/50 mt-1">{{ __('Organize your catering packages into groups') }}</p>
        </div>
        <x-ui.button variant="primary" size="sm" wire:click="openCreateModal" class="shadow-sm">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </x-slot:icon>
            {{ __('New Collection') }}
        </x-ui.button>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-[#F96015]/10 flex items-center justify-center">
                @include('layouts.partials.icons.squares-2x2', ['class' => 'w-5 h-5 text-[#F96015]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['total']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Total Collections') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                @include('layouts.partials.icons.cake', ['class' => 'w-5 h-5 text-primary'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['packages']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Total Packages') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-error/10 flex items-center justify-center">
                @include('layouts.partials.icons.exclamation-triangle-solid', ['class' => 'w-5 h-5 text-error'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['empty']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('No Packages') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-success/10 flex items-center justify-center">
                @include('layouts.partials.icons.check-circle-solid', ['class' => 'w-5 h-5 text-success'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content truncate max-w-[120px]" title="{{ $stats['most_popular'] }}">{{ $stats['most_popular'] }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Largest Collection') }}</p>
            </div>
        </div>
    </div>

    @if($errors->has('error'))
        <x-ui.alert type="danger" class="shadow-sm">
            {{ $errors->first('error') }}
        </x-ui.alert>
    @endif

    {{-- Table --}}
    <x-ui.table search="search">
        <x-slot name="header">
            <x-ui.table.th sortable wire:click="sortBy('name')" :direction="$sortField === 'name' ? $sortDirection : null">{{ __('Name') }}</x-ui.table.th>
            <x-ui.table.th sortable wire:click="sortBy('slug')" :direction="$sortField === 'slug' ? $sortDirection : null">{{ __('Slug') }}</x-ui.table.th>
            <x-ui.table.th sortable align="center" wire:click="sortBy('packages_count')" :direction="$sortField === 'packages_count' ? $sortDirection : null">{{ __('Packages') }}</x-ui.table.th>
            <x-ui.table.th sortable wire:click="sortBy('created_at')" :direction="$sortField === 'created_at' ? $sortDirection : null">{{ __('Added On') }}</x-ui.table.th>
            <x-ui.table.th align="right">{{ __('Actions') }}</x-ui.table.th>
        </x-slot>

        @forelse($categories as $category)
            <x-ui.table.row wire:key="category-{{ $category->id }}">
                <x-ui.table.td>
                    <span class="text-[13px] font-semibold text-base-content">{{ $category->name }}</span>
                </x-ui.table.td>
                
                <x-ui.table.td>
                    <code class="font-mono text-[11px] bg-base-200 px-2 py-1 rounded border border-base-content/10 text-base-content/60">{{ $category->slug }}</code>
                </x-ui.table.td>
                
                <x-ui.table.td align="center">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-base-200 text-base-content/70 text-[11px] font-bold">
                        {{ $category->packages_count }}
                    </span>
                </x-ui.table.td>

                <x-ui.table.td>
                    <span class="text-[13px] text-base-content/60">{{ $category->created_at->format('M j, Y') }}</span>
                </x-ui.table.td>
                
                <x-ui.table.td align="right">
                    <div class="flex items-center justify-end gap-2">
                        <button wire:click="openEditModal({{ $category->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-[12px] font-bold hover:bg-primary/20 transition-colors">
                            @include('layouts.partials.icons.eye', ['class' => 'w-3.5 h-3.5'])
                            {{ __('Edit') }}
                        </button>
                        <button wire:click="confirmDelete({{ $category->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-error/10 text-error text-[12px] font-bold hover:bg-error/20 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            {{ __('Delete') }}
                        </button>
                    </div>
                </x-ui.table.td>
            </x-ui.table.row>
        @empty
            <x-ui.table.empty colspan="5" />
        @endforelse

        <x-slot name="pagination">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="text-[12px] text-base-content/40 font-medium">
                    {{ __('Navigate through your collections using the links') }}
                </div>
                <div class="flex items-center justify-end gap-2 text-[11px] font-bold uppercase tracking-widest">
                    {{ $categories->links(data: ['scrollTo' => false]) }}
                </div>
            </div>
        </x-slot>
    </x-ui.table>

    {{-- Form Modal --}}
    <x-ui.modal wire:model="showFormModal" :title="$editingCategoryId ? 'Edit Collection' : 'New Collection'">
        <div class="space-y-6">
            <div>
                <x-ui.input label="Collection Name" wire:model="name" placeholder="E.g. Breakfast, Wedding Packages..." required />
                <p class="text-[11px] text-base-content/40 mt-1.5">{{ __('The name helps users find what they want easily.') }}</p>
                @error('name') <span class="text-error text-[11px] font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <x-slot name="footer">
                <x-ui.button variant="ghost" @click="show = false" type="button">{{ __('Cancel') }}</x-ui.button>
                <x-ui.button variant="primary" wire:click="saveCategory">{{ $editingCategoryId ? 'Save Changes' : 'Create Collection' }}</x-ui.button>
            </x-slot>
        </div>
    </x-ui.modal>

    {{-- Delete Confirmation Modal --}}
    <x-ui.modal wire:model="showDeleteModal" title="Delete Collection" maxWidth="sm">
        <div class="space-y-4">
            <div class="flex items-center gap-4 text-error">
                <div class="w-12 h-12 rounded-full bg-error/10 flex items-center justify-center shrink-0">
                    @include('layouts.partials.icons.exclamation-triangle-solid', ['class' => 'w-6 h-6'])
                </div>
                <div>
                    <h4 class="font-bold text-base-content">Are you sure?</h4>
                    <p class="text-[13px] text-base-content/60">This action cannot be undone.</p>
                </div>
            </div>

            <x-slot name="footer">
                <x-ui.button variant="ghost" @click="show = false">{{ __('Keep it') }}</x-ui.button>
                <x-ui.button variant="error" wire:click="deleteCategory">{{ __('Yes, Delete') }}</x-ui.button>
            </x-slot>
        </div>
    </x-ui.modal>
</div>
