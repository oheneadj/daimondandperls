<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-base-100 antialiased font-sans">
    <div class="relative flex min-h-svh flex-col lg:grid lg:max-w-none lg:grid-cols-2 lg:px-0">
        <!-- Brand / Image Side -->
        <div class="relative hidden h-full flex-col bg-neutral-900 p-10 text-white lg:flex border-r border-white/5">
            <div
                class="absolute inset-0 opacity-20 bg-[url('https://images.unsplash.com/photo-1555244162-803834f70033?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80')] bg-cover bg-center grayscale">
            </div>
            <div class="absolute inset-0 bg-gradient-to-br from-primary/40 to-black/80"></div>

            <div class="relative z-20 flex items-center gap-3">
                <x-app-logo />
            </div>

            <div class="relative z-20 mt-auto">
                <blockquote class="space-y-4">
                    <p class="text-3xl font-light italic leading-relaxed tracking-tight">
                        &ldquo;Experience the finest catering management platform. Efficiency meets culinary excellence
                        in every click.&rdquo;
                    </p>
                    <footer class="flex items-center gap-4">
                        <div class="h-px w-10 bg-primary"></div>
                        <span class="text-sm font-bold uppercase tracking-widest text-primary">Antigravity Premium
                            Systems</span>
                    </footer>
                </blockquote>
            </div>
        </div>

        <!-- Form Side -->
        <div class="flex items-center justify-center p-8 lg:p-12 bg-base-200">
            <div class="mx-auto flex w-full flex-col justify-center gap-8 sm:w-[400px]">
                <div class="lg:hidden flex justify-center mb-4">
                    <x-app-logo />
                </div>

                <div class="card bg-base-100 shadow-2xl border border-base-content/5 overflow-hidden">
                    <div class="card-body p-10 gap-8">
                        {{ $slot }}
                    </div>
                </div>

                <p class="px-8 text-center text-sm text-base-content/40 italic">
                    By clicking continue, you agree to our
                    <a href="#" class="underline underline-offset-4 hover:text-primary transition-colors">Terms of
                        Service</a>
                    and
                    <a href="#" class="underline underline-offset-4 hover:text-primary transition-colors">Privacy
                        Policy</a>.
                </p>
            </div>
        </div>
    </div>
</body>

</html>