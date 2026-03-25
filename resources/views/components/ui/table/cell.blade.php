@props(['align' => 'left'])

<td {{ $attributes->merge(['class' => "px-5 py-3.5  text-[13px] text-dp-text-body" . ($align === 'right' ? ' text-right' : '')]) }}>
    {{ $slot }}
</td>
