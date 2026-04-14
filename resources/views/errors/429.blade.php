@php
    $pageTitle      = 'Too Many Requests';
    $code           = '429';
    $heading        = 'Too Many Requests';
    $description    = "You've made too many requests in a short time. Please wait a moment before trying again.";
    $primaryLabel   = 'Go Home';
    $primaryUrl     = '/';
    $secondaryLabel = null;
    $secondaryUrl   = null;
@endphp
@include('errors.layout')
