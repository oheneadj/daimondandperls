@php
    $pageTitle      = 'Server Error';
    $code           = '500';
    $heading        = 'Something Went Wrong';
    $description    = 'We encountered an unexpected error on our end. Our team has been notified. Please try again shortly.';
    $primaryLabel   = 'Go Home';
    $primaryUrl     = '/';
    $secondaryLabel = null;
    $secondaryUrl   = null;
@endphp
@include('errors.layout')
