@props([
    'sidebar' => false,
])

<div {{ $attributes->merge(['class' => 'flex items-center gap-2']) }}>
    <div class="h-12 w-auto flex items-center gap-3">
        <x-app-logo-icon class="size-6 fill-current" />
    </div>
    @if(!$sidebar || ($sidebar && !isset($collapsed)))
        <div class="flex flex-col gap-0 leading-none">
            <span class=" text-[22px] font-semibold tracking-tight text-primary whitespace-nowrap">Diamonds & Pearls</span>
            <span class=" text-[9px] font-bold tracking-[0.4em] text-secondary-border uppercase pl-0.5">Catering Services</span>
        </div>
    @endif
</div>
