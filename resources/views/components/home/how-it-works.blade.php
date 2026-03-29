<section class="py-24 bg-base-100 border-b border-base-content/10">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="text-center mb-16">
            <span class="text-[11px] font-bold text-primary uppercase tracking-[0.2em] mb-3 block">Simple Process</span>
            <h2 class="text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">How it works</h2>
        </div>

        <div class="relative max-w-5xl mx-auto">
            <!-- Connecting Line (Desktop) -->
            <div class="hidden md:block absolute top-[4.5rem] left-0 w-full h-0.5 bg-base-content/5 z-0"></div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-4 relative z-10">
                @php
                    $steps = [
                        ['num' => '1', 'title' => 'Choose Package', 'desc' => 'Browse our curated packages and find the perfect match for your event size and budget.'],
                        ['num' => '2', 'title' => 'Book Online', 'desc' => 'Fill out your event details, location, and guest count securely on our platform.'],
                        ['num' => '3', 'title' => 'Pay Deposit', 'desc' => 'Secure your date instantly with a Momo or card payment. Minimum deposit rules apply.'],
                        ['num' => '4', 'title' => 'We Deliver', 'desc' => 'Our team arrives early to set up, serve, and clean up. You enjoy your special day.'],
                    ];
                @endphp
                @foreach($steps as $step)
                <div class="text-center group">
                    <div class="size-16 mx-auto bg-base-100 border-2 border-primary/20 text-primary rounded-2xl flex items-center justify-center font-bold text-xl mb-6 shadow-sm group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all duration-300">
                        {{ $step['num'] }}
                    </div>
                    <h4 class="text-lg font-bold text-base-content mb-2">{{ $step['title'] }}</h4>
                    <p class="text-[13px] text-base-content/60 font-medium px-4">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>

            <div class="mt-16 flex flex-wrap justify-center items-center gap-6 opacity-60 grayscale hover:grayscale-0 transition-all duration-500">
                <span class="text-[11px] font-bold text-base-content uppercase tracking-widest">Accepted Payments:</span>
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/14/MTN_Logo.svg/1024px-MTN_Logo.svg.png" class="h-8 object-contain mix-blend-multiply" alt="MTN Momo" loading="lazy">
                <img src="https://logos-world.net/wp-content/uploads/2020/09/Mastercard-Logo-2016-1024x576.png" class="h-8 object-contain mix-blend-multiply" alt="Mastercard" loading="lazy">
                <img src="https://logos-world.net/wp-content/uploads/2020/04/Visa-Logo-2014-present-800x450.png" class="h-8 object-contain mix-blend-multiply" alt="Visa" loading="lazy">
            </div>
        </div>
    </div>
</section>
