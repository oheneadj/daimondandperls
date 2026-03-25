@props(['align' => 'left', 'sortable' => null, 'direction' => null])
<x-ui.table.header align="{{ $align }}" sortable="{{ $sortable }}" direction="{{ $direction }}" {{ $attributes }}>
    {{ $slot }}
</x-ui.table.header>
