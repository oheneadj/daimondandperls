<x-guest-layout title="About Us">
    <div class="bg-base-200 min-h-screen">
        {{-- Hero Section --}}
        <section class="relative py-20 lg:py-32 overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-7xl h-full -z-10">
                <div class="absolute top-0 right-0 size-[500px] bg-primary/5 blur-[120px] rounded-full -translate-y-1/2 translate-x-1/4"></div>
                <div class="absolute bottom-0 left-0 size-[400px] bg-secondary/5 blur-[120px] rounded-full translate-y-1/2 -translate-x-1/4"></div>
            </div>

            <div class="container mx-auto px-4 lg:px-8 text-center">
                <h1 class=" text-5xl lg:text-7xl font-semibold text-base-content tracking-tight mb-8">Our Heritage</h1>
                <p class=" text-xl text-base-content/60 font-medium italic max-w-2xl mx-auto leading-relaxed mb-12">
                    Crafting extraordinary catering experiences with passion, precision, and the finest Ghanaian ingredients since 2018.
                </p>
                <div class="flex justify-center gap-4">
                    <div class="h-0.5 w-12 bg-primary mt-4"></div>
                    <div class="size-2 rounded-full bg-primary mt-3.5"></div>
                    <div class="h-0.5 w-12 bg-primary mt-4"></div>
                </div>
            </div>
        </section>

        {{-- Content Section --}}
        <section class="py-20 lg:py-32 bg-base-100 border-y border-base-content/10">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-20 items-center">
                    <div class="space-y-8">
                        <h2 class=" text-4xl font-semibold text-base-content leading-tight">The Diamonds & Pearls Story</h2>
                        <div class="space-y-6 text-dp-text-body leading-relaxed text-lg">
                            <p>
                                Founded in the heart of Accra, Diamonds & Pearls Catering emerged from a simple desire: to elevate the standard of catering through impeccable service and authentic culinary storytelling. 
                            </p>
                            <p>
                                We believe that every event is a unique narrative. Whether it's an intimate wedding or a large-scale corporate gathering, we infuse every dish with the richness of our culture and the sophistication of modern presentation.
                            </p>
                            <p>
                                Our team of expert chefs and dedicated coordinators work in harmony to ensure that every detail—from the initial consultation to the final service—is executed with the grace and brilliance that our name suggests.
                            </p>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="aspect-[4/5] rounded-[40px] overflow-hidden shadow-xl">
                            <img src="{{ asset('images/hero-catering.jpg') }}" alt="Our Catering" class="w-full h-full object-cover">
                        </div>
                        <div class="absolute -bottom-10 -left-10 bg-primary text-white p-8 rounded-[32px] shadow-dp-lg max-w-[240px]">
                            <div class=" text-4xl font-bold mb-2">7+</div>
                            <div class="text-xs font-bold uppercase tracking-[0.2em] opacity-80">Years of Culinary Excellence</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Values Section --}}
        <section class="py-20 lg:py-32">
            <div class="container mx-auto px-4 lg:px-8">
                <div class="text-center mb-20">
                    <h2 class=" text-4xl font-semibold text-base-content mb-4">Our Core Values</h2>
                    <p class="text-base-content/60 uppercase tracking-[0.2em] font-bold text-xs">The pillars of our service</p>
                </div>

                <div class="grid md:grid-cols-3 gap-12">
                    <div class="bg-base-100 p-10 rounded-[32px] border border-base-content/10 shadow-sm hover:shadow-md transition-shadow">
                        <div class="size-14 bg-primary/5 rounded-2xl flex items-center justify-center text-rose-500 mb-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                        </div>
                        <h3 class=" text-2xl font-bold text-base-content mb-4">Quality</h3>
                        <p class="text-base-content/60 leading-relaxed">We source only the finest local and international ingredients, ensuring every bite is a testament to quality.</p>
                    </div>
                    <div class="bg-base-100 p-10 rounded-[32px] border border-base-content/10 shadow-sm hover:shadow-md transition-shadow">
                        <div class="size-14 bg-secondary/5 rounded-2xl flex items-center justify-center text-green-600 mb-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-7.714 2.143L11 21l-2.286-6.857L1 12l7.714-2.143L11 3z" /></svg>
                        </div>
                        <h3 class=" text-2xl font-bold text-base-content mb-4">Elegance</h3>
                        <p class="text-base-content/60 leading-relaxed">Our presentation is our signature. We bring a touch of sophistication to every table we set.</p>
                    </div>
                    <div class="bg-base-100 p-10 rounded-[32px] border border-base-content/10 shadow-sm hover:shadow-md transition-shadow">
                        <div class="size-14 bg-base-200-mid rounded-2xl flex items-center justify-center text-base-content/60 mb-8">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        <h3 class=" text-2xl font-bold text-base-content mb-4">Trust</h3>
                        <p class="text-base-content/60 leading-relaxed">Reliability is at our core. We honor our commitments and deliver on our promises, every time.</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-guest-layout>
