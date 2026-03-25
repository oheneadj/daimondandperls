@props([
    'headers' => [],
    'rows' => [], // Optional if using custom slot
])

<div class="bg-white border border-base-content/10 rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table {{ $attributes->merge(['class' => 'w-full text-left border-collapse']) }}>
            <thead class="bg-base-200-mid border-b border-base-content/10">
                <tr>
                    @foreach($headers as $header)
                        <th class="px-5 py-3  text-[11px] font-bold uppercase tracking-[0.08em] text-base-content/60 whitespace-nowrap">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-base-200">
                @if(isset($slot) && $slot->isNotEmpty())
                    {{ $slot }}
                @else
                    {{-- Default empty state handled by parent if needed, but we can have a fallback --}}
                    <tr>
                        <td colspan="{{ count($headers) }}" class="px-5 py-16 text-center">
                            <x-empty-state 
                                icon="table-cells" 
                                title="No data available" 
                                description="There are no records to display at this time." 
                            />
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
