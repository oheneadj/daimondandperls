<div class="space-y-6 pb-10" x-data="{ reordering: false }">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-[28px] font-semibold text-base-content leading-tight">
                {{ __('All Packages') }}
            </h1>
            <p class="text-[14px] text-base-content/50 mt-1">{{ __('Collection of our premium catering packages.') }}</p>
        </div>
        
        <div class="flex items-center gap-3">
            <x-ui.button variant="outline" size="md" @click="reordering = !reordering" x-bind:class="reordering ? '!bg-neutral !text-white' : ''" title="{{ __('Reorder Packages') }}">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                    </svg>
                </x-slot:icon>
                <span x-text="reordering ? '{{ __('Finalize Order') }}' : '{{ __('Reorder Packages') }}'"></span>
            </x-ui.button>

            <x-ui.button variant="primary" size="md" href="{{ route('admin.manage-packages.create') }}" wire:navigate>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Add Package') }}
            </x-ui.button>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white border border-base-content/5 rounded-lg p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-[#F96015]/10 flex items-center justify-center">
                @include('layouts.partials.icons.clipboard-document-list', ['class' => 'w-5 h-5 text-[#F96015]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['total']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Total') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-lg p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-[#9ABC05]/10 flex items-center justify-center">
                @include('layouts.partials.icons.check-circle-solid', ['class' => 'w-5 h-5 text-[#9ABC05]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['active']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Active') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-lg p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-[#D52518]/10 flex items-center justify-center">
                @include('layouts.partials.icons.exclamation-triangle-solid', ['class' => 'w-5 h-5 text-[#D52518]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['inactive']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Inactive') }}</p>
            </div>
        </div>
        <div class="bg-white border border-base-content/5 rounded-lg p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-[#FFC926]/10 flex items-center justify-center">
                @include('layouts.partials.icons.information-circle-solid', ['class' => 'w-5 h-5 text-[#FFC926]'])
            </div>
            <div>
                <p class="text-[20px] font-bold text-base-content">{{ number_format($stats['categories']) }}</p>
                <p class="text-[10px] font-bold uppercase tracking-widest text-base-content/40">{{ __('Collections') }}</p>
            </div>
        </div>
    </div>

    @if($errors->has('error'))
        <x-ui.alert type="danger" class="shadow-sm">
            {{ $errors->first('error') }}
        </x-ui.alert>
    @endif

    {{-- Table --}}
    <x-ui.table search="search" x-data="{
        init() {
            if ({{ (empty($search) && empty($categoryId) && $status === 'all' && $sortField === 'sort_order') ? 'true' : 'false' }}) {
                Sortable.create(this.$refs.tbody, {
                    handle: '.drag-handle',
                    animation: 150,
                    onEnd: (e) => {
                        let items = Array.from(e.to.children).map(el => el.dataset.id);
                        $wire.call('reorder', items);
                    }
                });
            }
        }
    }">
        <x-slot name="filters">
            <div class="flex flex-wrap items-center gap-3">
                <!-- Category Filter -->
                <select wire:model.live="categoryId" class="bg-base-200 border-none text-[13px] rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#F96015]/30 outline-none transition-all font-medium">
                    <option value="">{{ __('All Collections') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>

                <!-- Status Filter -->
                <select wire:model.live="status" class="bg-base-200 border-none text-[13px] rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#F96015]/30 outline-none transition-all font-medium">
                    <option value="all">{{ __('All Status') }}</option>
                    <option value="active">{{ __('Active Only') }}</option>
                    <option value="inactive">{{ __('Inactive Only') }}</option>
                </select>
            </div>
        </x-slot>

        <x-slot name="header">
            <th x-show="reordering" class="w-12"></th>
            <x-ui.table.th>{{ __('Image') }}</x-ui.table.th>
            <x-ui.table.th sortable="name" :direction="$sortField === 'name' ? $sortDirection : null">{{ __('Package Details') }}</x-ui.table.th>
            <x-ui.table.th>{{ __('Collection') }}</x-ui.table.th>
            <x-ui.table.th sortable="price" :direction="$sortField === 'price' ? $sortDirection : null">{{ __('Price') }}</x-ui.table.th>
            <x-ui.table.th sortable="is_active" :direction="$sortField === 'is_active' ? $sortDirection : null" align="center">{{ __('Status') }}</x-ui.table.th>
            <x-ui.table.th align="right">{{ __('Actions') }}</x-ui.table.th>
        </x-slot>

        <tbody x-ref="tbody">
            @forelse($packages as $package)
                <x-ui.table.row wire:key="pkg-{{ $package->id }}" data-id="{{ $package->id }}" class="group border-b border-base-content/5 last:border-0">
                    <x-ui.table.td x-show="reordering" class="!px-2" style="display: none;">
                        <button class="drag-handle w-8 h-8 rounded-lg flex items-center justify-center text-base-content/60/30 hover:text-[#F96015] hover:bg-[#F96015]/10 cursor-grab active:cursor-grabbing transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </x-ui.table.td>
                    
                    <x-ui.table.td>
                        <div class="w-12 h-12 rounded-lg bg-base-200 overflow-hidden shadow-sm group-hover:shadow-md transition-shadow">
                            @if($package->image_path)
                                <img src="{{ Storage::url($package->image_path) }}" alt="{{ $package->name }} catering package — Diamonds &amp; Pearls Catering Accra" class="w-full h-full object-cover" loading="lazy" decoding="async" />
                            @else
                                <div class="w-full h-full flex items-center justify-center text-base-content/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </x-ui.table.td>
                    
                    <x-ui.table.td>
                        <div class="flex flex-col min-w-0">
                            <a href="{{ route('admin.manage-packages.edit', $package) }}" wire:navigate class="text-[13px] font-semibold text-base-content hover:text-[#F96015] mb-0.5 transition-colors">
                                {{ $package->name }}
                            </a>
                            <div class="text-[11px] text-base-content/60 truncate max-w-[200px]" title="{{ $package->serving_size }}">
                                {{ $package->serving_size ?? __('Bespoke implementation') }}
                            </div>
                        </div>
                    </x-ui.table.td>
                    
                    <x-ui.table.td>
                        @if($package->categories->isNotEmpty())
                            <span class="text-[11px] text-base-content/40 uppercase tracking-widest font-bold">{{ $package->categories->pluck('name')->join(', ') }}</span>
                        @else
                            <span class="text-[11px] text-base-content/30">{{ __('Uncategorized') }}</span>
                        @endif
                    </x-ui.table.td>
                    
                    <x-ui.table.td>
                        <span class="text-[14px] font-bold text-base-content">GHS {{ number_format($package->price, 2) }}</span>
                    </x-ui.table.td>
                    
                    <x-ui.table.td align="center">
                        @php
                            $statusColor = $package->is_active ? 'text-success' : 'text-base-content/40';
                            $statusBadge = $package->is_active ? 'Active' : 'Inactive';
                        @endphp
                        <div class="inline-flex items-center gap-1.5 {{ $statusColor }} text-[11px] font-bold uppercase tracking-wide cursor-pointer hover:opacity-80 transition-opacity" wire:click="toggleActive({{ $package->id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __($statusBadge) }}
                        </div>
                    </x-ui.table.td>
                    
                    <x-ui.table.td align="right">
                        <div class="flex items-center justify-end gap-2" x-show="!reordering">
                            <a href="{{ route('admin.manage-packages.edit', $package) }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-[#18542A]/10 text-[#18542A] text-[12px] font-bold hover:bg-[#18542A]/20 transition-colors" title="{{ __('Manage Package') }}">
                                @include('layouts.partials.icons.cog-6-tooth', ['class' => 'w-3.5 h-3.5'])
                                Manage
                            </a>
                            
                            <button wire:click="confirmDelete({{ $package->id }})" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-[#D52518]/10 text-[#D52518] hover:bg-[#D52518]/20 transition-colors" title="{{ __('Delete Package') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </x-ui.table.td>
                </x-ui.table.row>
            @empty
                <x-ui.table.empty colspan="7" />
            @endforelse
        </tbody>

        <x-slot name="pagination">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="text-[12px] text-base-content/40 font-medium">
                    {{ __('Click on the pagination links to navigate through the packages') }}
                </div>
                <div class="flex items-center justify-end gap-2">
                    {{ $packages?->links(data: ['scrollTo' => false]) }}
                </div>
            </div>
        </x-slot>
    </x-ui.table>

    <!-- Sortable.js -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    <!-- Delete Confirmation Modal -->
    <x-ui.modal wire:model="showDeleteModal" title="Confirm Deletion" icon="heroicon-o-exclamation-triangle" persistent>
        <div class="space-y-6">
            <p class="text-[14px] text-base-content leading-relaxed">
                {{ __('Are you sure you want to retire this culinary masterpiece? This action will remove the package from the active menu.') }}
            </p>
            <x-ui.alert type="warning" class="shadow-sm border border-warning/20">
                {{ __('Any existing bookings using this package will still retain its details.') }}
            </x-ui.alert>
        </div>
        
        <x-slot:footer>
            <x-ui.button variant="ghost" wire:click="$set('showDeleteModal', false)">{{ __('Cancel') }}</x-ui.button>
            <x-ui.button type="danger" variant="primary" wire:click="deletePackage" class="bg-[#D52518] border-[#D52518] hover:bg-[#b01e14]">
                {{ __('Retire Package') }}
            </x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>
