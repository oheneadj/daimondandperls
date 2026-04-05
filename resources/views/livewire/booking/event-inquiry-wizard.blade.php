<div class="bg-base-200 min-h-screen py-10 lg:py-20" x-data x-ref="wizardTop">
    <div class="container mx-auto px-4 lg:px-8 max-w-3xl">
        <x-booking.progress-bar :steps="['Event', 'Menu', 'Contact', 'Summary']" :currentStep="$currentStep" />

        <div class="bg-base-100 border border-base-content/10 rounded-lg p-5 sm:p-8 lg:p-10 shadow-dp-lg">

            {{-- ══ STEP 1: Event Details ══ --}}
            @if($currentStep === 1)
                <div wire:key="step-event" class="animate-fade-in space-y-8">
                    <div>
                        <h2 class="text-xl font-semibold text-base-content mb-2">Event Details</h2>
                        <p class="text-[14px] text-base-content/50 font-medium">Tell us about your event so we can prepare the perfect catering experience.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        {{-- Event Date --}}
                        <div class="md:col-span-2 form-control w-full space-y-1.5">
                            <label class="text-dp-sm font-medium text-base-content block">Event Date</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-base-content/40 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </span>
                                <input type="date" wire:model.live="event_date" class="w-full max-w-full pl-12 pr-[14px] py-[10px] bg-base-100 border border-base-content/10 focus:border-primary focus:ring-3 focus:ring-primary/20 rounded-lg transition-all duration-120 outline-none text-[15px] font-medium">
                            </div>
                            @error('event_date') <p class="text-xs text-error flex items-center gap-1"><span>⚠</span> {{ $message }}</p> @enderror
                        </div>

                        {{-- Start Time --}}
                        <div class="form-control w-full space-y-1.5">
                            <label class="text-dp-sm font-medium text-base-content block">Start Time</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-base-content/40 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                <input type="time" wire:model="event_start_time"
                                    @if(!$event_date) disabled @endif
                                    @class([
                                        'w-full max-w-full pl-12 pr-[14px] py-[10px] border rounded-lg transition-all duration-120 outline-none text-[15px] font-medium',
                                        'bg-base-100 border-base-content/10 focus:border-primary focus:ring-3 focus:ring-primary/20' => $event_date,
                                        'bg-base-200 border-base-content/5 text-base-content/30 cursor-not-allowed' => !$event_date,
                                    ])>
                            </div>
                            @if(!$event_date)
                                <p class="text-xs text-base-content/40">Select an event date first</p>
                            @endif
                            @error('event_start_time') <p class="text-xs text-error flex items-center gap-1"><span>⚠</span> {{ $message }}</p> @enderror
                        </div>

                        {{-- End Time --}}
                        <div class="form-control w-full space-y-1.5">
                            <label class="text-dp-sm font-medium text-base-content block">End Time</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-base-content/40 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </span>
                                <input type="time" wire:model="event_end_time"
                                    @if(!$event_date) disabled @endif
                                    @class([
                                        'w-full max-w-full pl-12 pr-[14px] py-[10px] border rounded-lg transition-all duration-120 outline-none text-[15px] font-medium',
                                        'bg-base-100 border-base-content/10 focus:border-primary focus:ring-3 focus:ring-primary/20' => $event_date,
                                        'bg-base-200 border-base-content/5 text-base-content/30 cursor-not-allowed' => !$event_date,
                                    ])>
                            </div>
                            @if(!$event_date)
                                <p class="text-xs text-base-content/40">Select an event date first</p>
                            @endif
                            @error('event_end_time') <p class="text-xs text-error flex items-center gap-1"><span>⚠</span> {{ $message }}</p> @enderror
                        </div>

                        {{-- Occasion Type --}}
                        <div class="md:col-span-2 form-control w-full space-y-1.5">
                            <label class="text-dp-sm font-medium text-base-content block">Occasion Type</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-base-content/40 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                </span>
                                <select wire:model.live="event_type" class="w-full max-w-full pl-12 pr-[14px] py-[10px] bg-base-100 border border-base-content/10 focus:border-primary focus:ring-3 focus:ring-primary/20 rounded-lg transition-all duration-120 outline-none text-[15px] font-medium appearance-none">
                                    <option value="">Select Event Type...</option>
                                    <option value="wedding">Wedding Reception</option>
                                    <option value="birthday">Birthday Party</option>
                                    <option value="corporate">Corporate Event</option>
                                    <option value="funeral">Funeral Rite</option>
                                    <option value="party">Social Gathering</option>
                                    <option value="other">Other Event</option>
                                </select>
                                <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-base-content/40 pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </span>
                            </div>
                            @error('event_type') <p class="text-xs text-error flex items-center gap-1"><span>⚠</span> {{ $message }}</p> @enderror
                        </div>

                        @if($event_type === 'other')
                            <div class="md:col-span-2 animate-fade-in">
                                <x-auth.input
                                    name="event_type_other"
                                    type="text"
                                    :label="__('Specify Occasion')"
                                    wireModel="event_type_other"
                                    placeholder="Describe the event..."
                                >
                                    <x-slot:icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </x-slot:icon>
                                </x-auth.input>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end pt-8 border-t border-base-content/10">
                        <x-ui.button type="button" wire:click="nextStep" x-on:click="$nextTick(() => $refs.wizardTop.scrollIntoView({ behavior: 'smooth' }))" variant="primary" size="lg" class="w-full sm:w-auto">
                            {{ __('Next: Menu Suggestions') }} &rarr;
                        </x-ui.button>
                    </div>
                </div>

            {{-- ══ STEP 2: Menu Suggestions + Service Style + Pax ══ --}}
            @elseif($currentStep === 2)
                <div wire:key="step-menu" class="animate-fade-in space-y-8">
                    <div>
                        <h2 class="text-xl font-semibold text-base-content mb-2">Menu Suggestions</h2>
                        <p class="text-[14px] text-base-content/50 font-medium">Optionally select packages to suggest for your event menu. You can also browse our menu to add items.</p>
                    </div>

                    @if(count($cartItems) > 0)
                        <div class="space-y-3">
                            @foreach($cartItems as $item)
                                <div wire:key="cart-review-{{ $item['package']->id }}" class="flex items-center gap-4 p-4 bg-base-200 rounded-lg border border-base-content/10">
                                    <div class="size-10 bg-primary/10 rounded-lg flex items-center justify-center shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <span class="text-[15px] font-semibold text-base-content">{{ $item['package']->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 bg-base-200/50 rounded-lg border border-dashed border-base-content/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-10 mx-auto text-base-content/20 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-[14px] text-base-content/40 font-medium mb-3">No menu items selected yet</p>
                            <a href="{{ route('packages.browse') }}" class="text-[13px] font-semibold text-primary hover:underline">Browse Our Menu &rarr;</a>
                        </div>
                    @endif

                    {{-- Service Style --}}
                    <div class="form-control w-full space-y-1.5">
                        <label class="text-dp-sm font-medium text-base-content block">Service Style</label>
                        <div class="flex gap-3">
                            <button type="button" wire:click="$set('is_buffet', false)"
                                @class([
                                    'flex-1 px-4 py-[10px] rounded-lg border text-[14px] font-medium transition-all flex items-center justify-center gap-2',
                                    'border-primary bg-primary/5 text-primary' => !$is_buffet,
                                    'border-base-content/10 bg-base-100 text-base-content/60 hover:border-base-content/20' => $is_buffet,
                                ])>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                Fixed Plates
                            </button>
                            <button type="button" wire:click="$set('is_buffet', true)"
                                @class([
                                    'flex-1 px-4 py-[10px] rounded-lg border text-[14px] font-medium transition-all flex items-center justify-center gap-2',
                                    'border-primary bg-primary/5 text-primary' => $is_buffet,
                                    'border-base-content/10 bg-base-100 text-base-content/60 hover:border-base-content/20' => !$is_buffet,
                                ])>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                                Buffet
                            </button>
                        </div>
                    </div>

                    {{-- Number of Guests / Plates --}}
                    <div class="form-control w-full space-y-1.5">
                        <label class="text-dp-sm font-medium text-base-content block">
                            @if($is_buffet) Expected Number of Guests @else Number of Plates @endif
                            <span class="text-base-content/40">(Optional)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-base-content/40 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </span>
                            <input type="number" wire:model="pax" min="1" max="10000"
                                class="w-full max-w-full pl-12 pr-[14px] py-[10px] bg-base-100 border border-base-content/10 focus:border-primary focus:ring-3 focus:ring-primary/20 rounded-lg transition-all duration-120 outline-none text-[15px] font-medium placeholder:text-base-content/40"
                                placeholder="e.g. 50">
                        </div>
                        @error('pax') <p class="text-xs text-error flex items-center gap-1"><span>⚠</span> {{ $message }}</p> @enderror
                    </div>

                    {{-- Notes --}}
                    <div class="form-control w-full space-y-1.5">
                        <label class="text-dp-sm font-medium text-base-content block">
                            Additional Notes <span class="text-base-content/40">(Optional)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute top-0 left-0 pl-4 pt-3 flex text-base-content/40 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                            </span>
                            <textarea wire:model="notes" rows="4"
                                class="w-full max-w-full pl-12 pr-[14px] py-[10px] bg-base-100 border border-base-content/10 focus:border-primary focus:ring-3 focus:ring-primary/20 rounded-lg transition-all duration-120 outline-none text-[15px] font-medium placeholder:text-base-content/40 resize-none"
                                placeholder="Any special requests, dietary requirements, or additional details..."></textarea>
                        </div>
                        @error('notes') <p class="text-xs text-error flex items-center gap-1"><span>⚠</span> {{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-8 border-t border-base-content/10">
                        <x-ui.button variant="ghost" size="md" wire:click="previousStep" x-on:click="$nextTick(() => $refs.wizardTop.scrollIntoView({ behavior: 'smooth' }))" class="w-full sm:w-auto">&larr; {{ __('Back') }}</x-ui.button>
                        <x-ui.button variant="primary" size="lg" wire:click="nextStep" x-on:click="$nextTick(() => $refs.wizardTop.scrollIntoView({ behavior: 'smooth' }))" class="w-full sm:w-auto">
                            {{ __('Next: Contact Details') }} &rarr;
                        </x-ui.button>
                    </div>
                </div>

            {{-- ══ STEP 3: Contact Details ══ --}}
            @elseif($currentStep === 3)
                <div wire:key="step-contact" class="animate-fade-in space-y-8">
                    <div>
                        <h2 class="text-xl font-semibold text-base-content mb-2">Contact Details</h2>
                        <p class="text-[14px] text-base-content/50 font-medium">Enter your information so we can send you a detailed quote.</p>
                    </div>

                    <x-booking.contact-form :verifyPhone="$verifyPhone" :otpStep="$otpStep" :otpError="$otpError" :phone="$phone" />

                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-8 border-t border-base-content/10">
                        <x-ui.button variant="ghost" size="md" wire:click="previousStep" x-on:click="$nextTick(() => $refs.wizardTop.scrollIntoView({ behavior: 'smooth' }))" class="w-full sm:w-auto">&larr; {{ __('Back') }}</x-ui.button>
                        <x-ui.button variant="primary" size="lg" wire:click="nextStep" x-on:click="$nextTick(() => $refs.wizardTop.scrollIntoView({ behavior: 'smooth' }))" class="w-full sm:w-auto">
                            {{ __('Next: Review Summary') }} &rarr;
                        </x-ui.button>
                    </div>
                </div>

            {{-- ══ STEP 4: Summary ══ --}}
            @elseif($currentStep === 4)
                <div wire:key="step-summary" class="animate-fade-in space-y-8">
                    <div>
                        <h2 class="text-xl font-semibold text-base-content mb-2">Event Summary</h2>
                        <p class="text-[14px] text-base-content/50 font-medium">Review your event details before submitting your quote request.</p>
                    </div>

                    <div class="bg-base-200 rounded-lg p-5 lg:p-8 space-y-5 border border-base-content/10">
                        {{-- Badge --}}
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-primary/10 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                            Event Inquiry
                        </span>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="text-dp-sm font-medium text-primary uppercase tracking-wider mb-2">Client Information</div>
                                <div class="text-[15px] font-semibold text-base-content">{{ $name }}</div>
                                <div class="text-[13px] text-base-content/60 font-medium">{{ $phone }}</div>
                                @if($email) <div class="text-[13px] text-base-content/60 font-medium">{{ $email }}</div> @endif
                            </div>
                            <div>
                                <div class="text-dp-sm font-medium text-primary uppercase tracking-wider mb-2">Event Particulars</div>
                                <div class="text-[15px] font-semibold text-base-content">
                                    {{ $event_date ? \Carbon\Carbon::parse($event_date)->format('D, M j, Y') : 'Date TBD' }}
                                </div>
                                <div class="text-[13px] text-base-content/60 font-medium">
                                    @if($event_start_time) {{ \Carbon\Carbon::parse($event_start_time)->format('g:i A') }} @endif
                                    @if($event_end_time) — {{ \Carbon\Carbon::parse($event_end_time)->format('g:i A') }} @endif
                                </div>
                                @if($event_type)
                                    <div class="inline-block mt-2 bg-primary/5 text-primary text-[9px] font-black px-3 py-1 rounded-full border border-primary/10 uppercase tracking-wider">
                                        {{ $event_type === 'other' ? $event_type_other : $event_type }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Service & Guests --}}
                        @if($pax)
                            <div class="pt-4 border-t border-base-content/10">
                                <div class="text-dp-sm font-medium text-primary uppercase tracking-wider mb-2">Service Details</div>
                                <div class="text-[14px] font-semibold text-base-content">
                                    {{ $is_buffet ? 'Buffet' : 'Fixed Plates' }} — {{ $pax }} {{ $is_buffet ? 'guests' : 'plates' }}
                                </div>
                            </div>
                        @endif

                        {{-- Menu Selections --}}
                        @if(count($cartItems) > 0)
                            <div class="pt-4 border-t border-base-content/10">
                                <div class="text-dp-sm font-medium text-primary uppercase tracking-wider mb-2">Menu Suggestions</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($cartItems as $item)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-base-100 border border-base-content/10 text-[12px] font-semibold text-base-content">
                                            {{ $item['package']->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Notes --}}
                        @if($notes)
                            <div class="pt-4 border-t border-base-content/10">
                                <div class="text-dp-sm font-medium text-primary uppercase tracking-wider mb-2">Additional Notes</div>
                                <p class="text-[13px] text-base-content/70 font-medium leading-relaxed">{{ $notes }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Quote Info --}}
                    <div class="bg-primary/5 border border-primary/15 rounded-lg p-5 flex items-start gap-4">
                        <div class="size-10 bg-primary text-white rounded-full flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-[13px] font-semibold text-primary">What Happens Next?</div>
                            <p class="text-xs text-base-content/60 font-medium leading-relaxed mt-1">
                                Our team will review your event details and send you a quote via SMS and email. You'll receive a payment link once the quote is ready.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-8 border-t border-base-content/10">
                        <x-ui.button variant="ghost" size="md" wire:click="previousStep" x-on:click="$nextTick(() => $refs.wizardTop.scrollIntoView({ behavior: 'smooth' }))" class="w-full sm:w-auto">&larr; {{ __('Back') }}</x-ui.button>
                        <x-ui.button variant="primary" size="lg" wire:click="confirmBooking" wire:loading.attr="disabled" :loading="$loading === 'confirmBooking'" class="w-full sm:w-auto">
                            {{ __('Request Quote') }}
                        </x-ui.button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
