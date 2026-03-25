@props(['name', 'phone' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col']) }}>
    <span class=" text-[13px] font-medium text-base-content leading-tight">{{ $name }}</span>
    @if($phone)
        <span class=" text-[11px] text-base-content/60 mt-0.5">{{ $phone }}</span>
    @endif
</div>
