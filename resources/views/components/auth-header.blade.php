@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center gap-2">
    <h1 class="text-2xl font-bold tracking-tight text-base-content">{{ $title }}</h1>
    <p class="text-sm text-base-content/60">{{ $description }}</p>
</div>
