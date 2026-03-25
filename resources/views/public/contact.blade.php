<x-guest-layout title="Contact Us">
    <div class="bg-base-200 min-h-screen py-20 lg:py-32">
        <div class="container mx-auto px-4 lg:px-8">
            <div class="max-w-5xl mx-auto">
                {{-- Header --}}
                <div class="text-center mb-20 animate-fade-in">
                    <h1 class=" text-5xl lg:text-6xl font-semibold text-base-content mb-6 tracking-tight">Get in Touch</h1>
                    <p class=" text-xl text-base-content/60 font-medium italic max-w-2xl mx-auto leading-relaxed">
                        We would be honored to discuss your upcoming event and tailor a catering experience that exceeds your expectations.
                    </p>
                </div>

                <div class="grid lg:grid-cols-12 gap-16 items-start">
                    {{-- Contact Info --}}
                    <div class="lg:col-span-5 space-y-12">
                        <div class="space-y-8">
                            <h2 class=" text-2xl font-bold text-base-content uppercase tracking-widest border-b border-base-content/10 pb-4">Our Studio</h2>
                            
                            <div class="flex items-start gap-6">
                                <div class="size-12 bg-primary/5 rounded-2xl flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </div>
                                <div>
                                    <h3 class=" text-lg font-bold text-base-content mb-1">Address</h3>
                                    <p class="text-base-content/60 leading-relaxed font-medium">P.O. Box 18123, Accra, Ghana</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-6">
                                <div class="size-12 bg-primary/5 rounded-2xl flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                                </div>
                                <div>
                                    <h3 class=" text-lg font-bold text-base-content mb-1">Phone</h3>
                                    <p class="text-base-content/60 leading-relaxed font-medium">+233 244 203 181</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-6">
                                <div class="size-12 bg-primary/5 rounded-2xl flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                </div>
                                <div>
                                    <h3 class=" text-lg font-bold text-base-content mb-1">Email</h3>
                                    <p class="text-base-content/60 leading-relaxed font-medium">graceayesu@yahoo.com</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-secondary p-8 rounded-[32px] text-white space-y-4 shadow-dp-lg">
                            <h3 class=" text-xl font-bold">Inquiry Hours</h3>
                            <div class="space-y-2 opacity-90 text-sm font-medium">
                                <div class="flex justify-between">
                                    <span>Mon — Fri</span>
                                    <span>8:00 AM — 6:00 PM</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Saturday</span>
                                    <span>9:00 AM — 4:00 PM</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Sunday</span>
                                    <span>Closed</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Contact Form --}}
                    <div class="lg:col-span-7 bg-base-100 p-8 lg:p-12 rounded-[40px] border border-base-content/10 shadow-xl">
                        <form action="#" class="space-y-8">
                            <div class="grid sm:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-base-content/60 uppercase tracking-widest ml-1">Full Name</label>
                                    <input type="text" class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-primary/20 rounded-2xl transition-all text-sm font-medium" placeholder="Your name">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold text-base-content/60 uppercase tracking-widest ml-1">Phone Number</label>
                                    <input type="tel" class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-primary/20 rounded-2xl transition-all text-sm font-medium" placeholder="+233 ...">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-base-content/60 uppercase tracking-widest ml-1">Email Address</label>
                                <input type="email" class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-primary/20 rounded-2xl transition-all text-sm font-medium" placeholder="your@email.com">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-base-content/60 uppercase tracking-widest ml-1">Inquiry Type</label>
                                <select class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-primary/20 rounded-2xl transition-all text-sm font-medium appearance-none">
                                    <option>General Inquiry</option>
                                    <option>Wedding Catering</option>
                                    <option>Corporate Event</option>
                                    <option>Private Celebration</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[11px] font-bold text-base-content/60 uppercase tracking-widest ml-1">Message</label>
                                <textarea rows="5" class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-primary/20 rounded-2xl transition-all text-sm font-medium" placeholder="Tell us about your event..."></textarea>
                            </div>

                            <x-ui.button type="submit" variant="primary" size="lg" class="w-full shadow-md uppercase tracking-widest h-16">
                                {{ __('Send Message') }}
                            </x-ui.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
