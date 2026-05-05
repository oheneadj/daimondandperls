<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>
    {{ filled($title ?? null) ? $title . ' - ' . config('app.name', 'Laravel') : config('app.name', 'Laravel') }}
</title>

@php
    $pageTitle     = filled($title ?? null) ? $title . ' — ' . config('app.name') : config('app.name');
    $pageDesc      = $metaDescription ?? 'Diamonds and Pearls Catering — Accra\'s premier catering service for meals, events, and private dining. Order online today.';
    $pageCanonical = $canonical ?? url()->current();
    $logoPath      = dpc_setting('business_logo');
    $pageImage     = $ogImage ?? ($logoPath ? Storage::url($logoPath) : asset('logos/og-default.png'));
    $isPublic      = !request()->is('admin/*') && !request()->is('dashboard/*') && !request()->is('checkout*') && !request()->is('booking/*');
@endphp

{{-- SEO --}}
<meta name="description" content="{{ $pageDesc }}">
<link rel="canonical" href="{{ $pageCanonical }}">

{{-- Open Graph --}}
<meta property="og:type" content="{{ $ogType ?? 'website' }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDesc }}">
<meta property="og:url" content="{{ $pageCanonical }}">
<meta property="og:image" content="{{ $pageImage }}">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:locale" content="en_GH">

{{-- Twitter Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDesc }}">
<meta name="twitter:image" content="{{ $pageImage }}">

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

{{-- Font preload + stylesheet --}}
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" as="style">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])

@if($isPublic)
{{-- FoodEstablishment structured data — public pages only --}}
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "FoodEstablishment",
    "name": "Diamonds and Pearls Catering Services",
    "url": "{{ config('app.url') }}",
    "logo": "{{ $pageImage }}",
    "image": "{{ $pageImage }}",
    "description": "Accra's premier catering service for meals, events, and private dining.",
    "address": {
        "@@type": "PostalAddress",
        "addressLocality": "Accra",
        "addressCountry": "GH"
    },
    "servesCuisine": "Ghanaian",
    "priceRange": "$$",
    "openingHoursSpecification": {
        "@@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
        "opens": "08:00",
        "closes": "20:00"
    }
}
</script>

@php $gaId = config('services.google.analytics_id'); @endphp
@if(app()->isProduction() && $gaId)
{{-- Google Analytics --}}
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '{{ $gaId }}');
</script>
@endif
@endif
