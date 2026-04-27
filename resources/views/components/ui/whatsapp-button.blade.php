@props([
    'number'  => null,
    'label'   => 'Chat on WhatsApp',
    'size'    => 'md',   // sm | md | lg
    'variant' => 'solid', // solid | outline | ghost
])

@php
    $whatsappNumber = $number ?? dpc_setting('business_whatsapp', '233244203181');

    $sizeClasses = match($size) {
        'sm'  => 'px-4 py-2.5 text-[12px] gap-2',
        'lg'  => 'px-8 py-4 text-[15px] gap-3',
        default => 'px-5 py-3 text-[13px] gap-2.5',
    };

    $variantClasses = match($variant) {
        'outline' => 'bg-transparent border-2 border-[#25D366] text-[#25D366] hover:bg-[#25D366] hover:text-white',
        'white'   => 'bg-transparent border-2 border-white/50 text-white hover:bg-white/15 hover:border-white',
        'ghost'   => 'bg-[#25D366]/10 text-[#25D366] hover:bg-[#25D366] hover:text-white',
        default   => 'bg-[#25D366] text-white hover:bg-[#20bd5a] shadow-sm hover:shadow-md',
    };
@endphp

<a href="https://wa.me/{{ $whatsappNumber }}"
   target="_blank"
   rel="noopener noreferrer"
   {{ $attributes->merge(['class' => "inline-flex items-center justify-center font-bold rounded-xl transition-all duration-200 {$sizeClasses} {$variantClasses}"]) }}>
    {{-- Official WhatsApp bubble icon --}}
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
         class="{{ $size === 'sm' ? 'size-4' : ($size === 'lg' ? 'size-5' : 'size-4.5') }} shrink-0">
        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.573-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86s.274.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.045.072.045.419-.1.824zm-3.423-14.416c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm.082 21.183c-1.653 0-3.331-.482-4.717-1.3l-5.365 1.488 1.474-5.26c-.822-1.391-1.309-3.093-1.309-4.821 0-5.319 4.316-9.635 9.636-9.635 5.316 0 9.632 4.316 9.632 9.635 0 5.317-4.316 9.631-9.351 9.631z"/>
    </svg>
    {{ $label }}
</a>
