<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-[28px] font-semibold text-base-content leading-tight">Booking Windows</h1>
            <p class="text-[14px] text-base-content/50 mt-1">Define weekly delivery schedules and assign them to packages.</p>
        </div>
        <x-ui.button variant="primary" size="sm" wire:click="openCreateModal" class="shadow-sm">
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </x-slot:icon>
            New Window
        </x-ui.button>
    </div>

    {{-- Table --}}
    <x-ui.table search="search">
        <x-slot name="header">
            <x-ui.table.th>Name</x-ui.table.th>
            <x-ui.table.th>Delivery Day</x-ui.table.th>
            <x-ui.table.th>Cutoff</x-ui.table.th>
            <x-ui.table.th align="center">Packages</x-ui.table.th>
            <x-ui.table.th align="right">Actions</x-ui.table.th>
        </x-slot>

        @forelse($windows as $window)
            <x-ui.table.row wire:key="window-{{ $window->id }}">
                <x-ui.table.td>
                    <span class="text-[13px] font-semibold text-base-content">{{ $window->name }}</span>
                </x-ui.table.td>

                <x-ui.table.td>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-primary/10 text-primary text-[11px] font-bold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $dayLabels[$window->delivery_day] ?? '—' }}
                    </span>
                </x-ui.table.td>

                <x-ui.table.td>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-[12px] font-semibold text-base-content">{{ $dayLabels[$window->cutoff_day] ?? '—' }}</span>
                        <span class="text-[11px] text-base-content/40">at {{ substr($window->cutoff_time, 0, 5) }}</span>
                    </div>
                </x-ui.table.td>

                <x-ui.table.td align="center">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-base-200 text-base-content/70 text-[11px] font-bold">
                        {{ $window->packages_count }}
                    </span>
                </x-ui.table.td>

                <x-ui.table.td align="right">
                    <div class="flex items-center justify-end gap-2">
                        <button wire:click="openEditModal({{ $window->id }})"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-primary/10 text-primary text-[12px] font-bold hover:bg-primary/20 transition-colors">
                            @include('layouts.partials.icons.eye', ['class' => 'w-3.5 h-3.5'])
                            Edit
                        </button>
                        <button wire:click="confirmDelete({{ $window->id }})"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-error/10 text-error text-[12px] font-bold hover:bg-error/20 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </div>
                </x-ui.table.td>
            </x-ui.table.row>
        @empty
            <x-ui.table.empty colspan="5" />
        @endforelse

        <x-slot name="pagination">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="text-[12px] text-base-content/40 font-medium">Navigate through your booking windows</div>
                <div class="flex items-center justify-end gap-2 text-[11px] font-bold uppercase tracking-widest">
                    {{ $windows->links(data: ['scrollTo' => false]) }}
                </div>
            </div>
        </x-slot>
    </x-ui.table>

    {{-- Form Modal --}}
    <x-ui.modal wire:model="showFormModal" :title="$editingWindowId ? 'Edit Booking Window' : 'New Booking Window'">
        <div class="space-y-5">
            <div>
                <x-ui.input label="Window Name" wire:model="name" placeholder="E.g. Friday Lunch, Tuesday Delivery…" required />
                @error('name') <span class="text-error text-[11px] font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[12px] font-semibold text-base-content/70 block mb-1.5">Delivery Day</label>
                    <select wire:model.live="delivery_day" class="select select-sm w-full border border-base-content/15 rounded-lg bg-base-100">
                        <option value="">Select day…</option>
                        @foreach($dayLabels as $iso => $label)
                            <option value="{{ $iso }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('delivery_day') <span class="text-error text-[11px] font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-[12px] font-semibold text-base-content/70 block mb-1.5">Order Cutoff Day</label>
                    <select wire:model.live="cutoff_day" class="select select-sm w-full border border-base-content/15 rounded-lg bg-base-100">
                        <option value="">Select day…</option>
                        @foreach($dayLabels as $iso => $label)
                            <option value="{{ $iso }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('cutoff_day') <span class="text-error text-[11px] font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="text-[12px] font-semibold text-base-content/70 block mb-1.5">Cutoff Time</label>
                <input type="time" wire:model.live="cutoff_time" class="input input-sm w-full border border-base-content/15 rounded-lg bg-base-100" />
                @error('cutoff_time') <span class="text-error text-[11px] font-bold mt-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Live preview --}}
            @if($this->previewLabel)
                <div class="bg-primary/5 border border-primary/15 rounded-lg px-4 py-3">
                    <p class="text-[12px] text-primary font-medium">{{ $this->previewLabel }}</p>
                </div>
            @endif
        </div>

        <x-slot name="footer">
            <x-ui.button variant="ghost" @click="show = false" type="button">Cancel</x-ui.button>
            <x-ui.button variant="primary" wire:click="saveWindow">
                {{ $editingWindowId ? 'Save Changes' : 'Create Window' }}
            </x-ui.button>
        </x-slot>
    </x-ui.modal>

    {{-- Delete Confirmation Modal --}}
    <x-ui.modal wire:model="showDeleteModal" title="Delete Booking Window" maxWidth="sm">
        <div class="space-y-4">
            <div class="flex items-center gap-4 text-error">
                <div class="w-12 h-12 rounded-full bg-error/10 flex items-center justify-center shrink-0">
                    @include('layouts.partials.icons.exclamation-triangle-solid', ['class' => 'w-6 h-6'])
                </div>
                <div>
                    <h4 class="font-bold text-base-content">Are you sure?</h4>
                    <p class="text-[13px] text-base-content/60">This window will be removed from all packages assigned to it.</p>
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <x-ui.button variant="ghost" @click="show = false">Keep it</x-ui.button>
            <x-ui.button variant="error" wire:click="deleteWindow">Yes, Delete</x-ui.button>
        </x-slot>
    </x-ui.modal>
</div>
