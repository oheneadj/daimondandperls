@php
    $pageTitle      = 'Page Not Found';
    $code           = '404';
    $heading        = 'Page Not Found';
    $description    = "The page you're looking for doesn't exist or may have been moved. Let's get you back on track.";
    $primaryLabel   = 'Go Home';
    $primaryUrl     = '/';
    $secondaryLabel = 'Browse Our Menu';
    $secondaryUrl   = route('packages.browse');
@endphp
@include('errors.layout')
