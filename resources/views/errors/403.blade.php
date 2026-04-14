@php
    $pageTitle     = 'Access Denied';
    $code          = '403';
    $heading       = 'Access Denied';
    $description   = "You don't have permission to view this page. If you believe this is a mistake, please sign in or contact us.";
    $primaryLabel  = 'Go Home';
    $primaryUrl    = '/';
    $secondaryLabel = 'Log In';
    $secondaryUrl  = '/login';
@endphp
@include('errors.layout')
