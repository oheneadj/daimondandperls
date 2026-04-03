<section class="py-16 sm:py-24 bg-base-100 border-b border-base-content/10">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="text-center mb-10 sm:mb-16">
            <span class="text-[11px] font-bold text-primary uppercase tracking-[0.2em] mb-3 block">Simple Process</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">How it works</h2>
        </div>

        <div class="relative max-w-5xl mx-auto">
            <!-- Connecting Line (Desktop) -->
            <div class="hidden md:block absolute top-[4.5rem] left-0 w-full h-0.5 bg-base-content/5 z-0"></div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-4 relative z-10">
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
                    <div class="size-12 sm:size-16 mx-auto bg-base-100 border-2 border-primary/20 text-primary rounded-xl sm:rounded-2xl flex items-center justify-center font-bold text-lg sm:text-xl mb-4 sm:mb-6 shadow-sm group-hover:bg-primary group-hover:text-white group-hover:border-primary transition-all duration-300">
                        {{ $step['num'] }}
                    </div>
                    <h4 class="text-[14px] sm:text-lg font-bold text-base-content mb-1 sm:mb-2">{{ $step['title'] }}</h4>
                    <p class="text-[11px] sm:text-[13px] text-base-content/60 font-medium px-1 sm:px-4">{{ $step['desc'] }}</p>
                </div>
                @endforeach
            </div>

            <div class="mt-10 sm:mt-16 flex flex-wrap justify-center items-center gap-4 sm:gap-6  hover:grayscale-0 transition-all duration-500">
                <span class="text-[10px] sm:text-[11px] font-bold text-base-content uppercase tracking-widest">Accepted Payments:</span>
                <img src="{{ asset('logos/mtn-momo.png') }}" class="h-6 sm:h-8 object-contain" alt="MTN MoMo" loading="lazy">
                <img src="{{ asset('logos/Telecel-Cash.jpg') }}" class="h-6 sm:h-8 object-contain rounded-md shadow-sm" alt="Telecel Cash" loading="lazy">
                <img src="{{ asset('logos/airteltigo-money.png') }}" class="h-6 sm:h-8 object-contain" alt="AirtelTigo Money" loading="lazy">
            </div>
        </div>
    </div>
</section>
