@php
    $pageTitle      = 'Service Unavailable';
    $code           = '503';
    $heading        = 'Back Soon';
    $description    = "We're performing some scheduled maintenance. We'll be back up shortly — thank you for your patience.";
    $primaryLabel   = 'Go Home';
    $primaryUrl     = '/';
    $secondaryLabel = null;
    $secondaryUrl   = null;
@endphp
@include('errors.layout')
