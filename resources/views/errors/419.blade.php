@php
    $pageTitle      = 'Session Expired';
    $code           = '419';
    $heading        = 'Session Expired';
    $description    = 'Your session has expired for security reasons. Please go back and try your action again.';
    $primaryLabel   = 'Go Back';
    $primaryUrl     = 'javascript:history.back()';
    $secondaryLabel = 'Go Home';
    $secondaryUrl   = '/';
@endphp
@include('errors.layout')
