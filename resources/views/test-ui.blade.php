<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.head')
    <title>UI Component Test Library</title>
</head>
<body class="bg-base-200 p-10 ">
    <div class="max-w-5xl mx-auto space-y-12">
        <section>
            <h2 class="text-dp-2xl  font-semibold text-base-content mb-6">2.1 — Button Component</h2>
            
            <div class="space-y-8 bg-white p-8 rounded-xl border border-base-content/10 shadow-sm">
                {{-- Variants x Sizes --}}
                <div class="space-y-6">
                    <h3 class="text-dp-xs font-bold text-base-content/60 uppercase tracking-widest">Variants & Sizes</h3>
                    @foreach(['primary', 'secondary', 'outline', 'ghost', 'danger'] as $variant)
                        <div class="flex items-center gap-4 flex-wrap">
                            <span class="w-24 text-[11px] font-bold text-base-content/60 uppercase tracking-widest">{{ $variant }}</span>
                            <x-button :variant="$variant" size="sm">Small {{ ucfirst($variant) }}</x-button>
                            <x-button :variant="$variant" size="md">Medium {{ ucfirst($variant) }}</x-button>
                            <x-button :variant="$variant" size="lg">Large {{ ucfirst($variant) }}</x-button>
                        </div>
                    @endforeach
                </div>

                {{-- Icons --}}
                <div class="space-y-6 pt-6 border-t border-base-content/10">
                    <h3 class="text-dp-xs font-bold text-base-content/60 uppercase tracking-widest">With Icons</h3>
                    <div class="flex items-center gap-4 flex-wrap">
                        <x-button variant="primary" icon="plus">Add New</x-button>
                        <x-button variant="secondary" icon="check-circle-solid">Save Changes</x-button>
                        <x-button variant="outline" icon="cog-6-tooth">Settings</x-button>
                        <x-button variant="ghost" icon="magnifying-glass" size="sm">Search</x-button>
                        <x-button variant="danger" icon="x-circle-solid">Delete</x-button>
                    </div>
                </div>

                {{-- Icon Only --}}
                <div class="space-y-6 pt-6 border-t border-base-content/10">
                    <h3 class="text-dp-xs font-bold text-base-content/60 uppercase tracking-widest">Icon Only (Accessibility Title)</h3>
                    <div class="flex items-center gap-4">
                        <x-button variant="ghost" size="icon" icon="bell" title="Notifications" />
                        <x-button variant="outline" size="icon" icon="magnifying-glass" title="Search" />
                        <x-button variant="primary" size="icon" icon="plus" title="Add" />
                    </div>
                </div>

                {{-- States --}}
                <div class="space-y-6 pt-6 border-t border-base-content/10">
                    <h3 class="text-dp-xs font-bold text-base-content/60 uppercase tracking-widest">States (Disabled & Loading)</h3>
                    <div class="flex items-center gap-4 flex-wrap">
                        <x-button variant="primary" disabled>Primary Disabled</x-button>
                        <x-button variant="secondary" loading>Loading State</x-button>
                        <x-button variant="outline" disabled loading>Outline both</x-button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
