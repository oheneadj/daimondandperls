@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center gap-2">
    <h1 class="text-xl font-semibold text-base-content">{{ $title }}</h1>
    <p class="text-[14px] text-base-content/50 font-medium leading-relaxed">{{ $description }}</p>
</div>
