<div class="bg-base-200 min-h-screen py-10 lg:py-20" x-data x-ref="wizardTop">
    <div class="container mx-auto px-4 lg:px-8 max-w-3xl">
        <x-booking.progress-bar
            :steps="Auth::check() ? ['Event Details', 'Summary'] : ['Event Details', 'Contact', 'Summary']"
            :currentStep="$this->getVisualStep()"
        />

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
                        <div class="md:col-span-2">
                            <x-app.input
                                name="event_date"
                                type="date"
                                label="Event Date"
                                wire:model.live="event_date"
                                :min="$minEventDate"
                            >
                                <x-slot:icon>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </x-slot:icon>
                            </x-app.input>
                        </div>

                        {{-- Start Time --}}
                        <x-app.input
                            name="event_start_time"
                            type="time"
                            label="Start Time"
                            wire:model="event_start_time"
                            :disabled="!$event_date"
                            :hint="!$event_date ? 'Select an event date first' : null"
                        >
                            <x-slot:icon>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </x-slot:icon>
                        </x-app.input>

                        {{-- End Time --}}
                        <x-app.input
                            name="event_end_time"
                            type="time"
                            label="End Time"
                            wire:model="event_end_time"
                            :disabled="!$event_date"
                            :hint="!$event_date ? 'Select an event date first' : null"
                        >
                            <x-slot:icon>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </x-slot:icon>
                        </x-app.input>

                        {{-- Occasion Type --}}
                        <div class="md:col-span-2">
                            <x-app.input
                                name="event_type"
                                as="select"
                                label="Occasion Type"
                                wire:model.live="event_type"
                            >
                                <x-slot:icon>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                </x-slot:icon>
                                <option value="">Select Event Type...</option>
                                <option value="wedding">Wedding Reception</option>
                                <option value="birthday">Birthday Party</option>
                                <option value="corporate">Corporate Event</option>
                                <option value="funeral">Funeral Rite</option>
                                <option value="party">Social Gathering</option>
                                <option value="other">Other Event</option>
                            </x-app.input>
                        </div>

                        @if($event_type === 'other')
                            <div class="md:col-span-2 animate-fade-in">
                                <x-app.input
                                    name="event_type_other"
                                    type="text"
                                    :label="__('Specify Occasion')"
                                    wire:model="event_type_other"
                                    placeholder="Describe the event..."
                                >
                                    <x-slot:icon>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </x-slot:icon>
                                </x-app.input>
                            </div>
                        @endif

                        {{-- Event Location --}}
                        <div class="md:col-span-2">
                            <x-app.input
                                name="event_location"
                                type="text"
                                label="Event Location"
                                wire:model="event_location"
                                placeholder="e.g. Kempinski Hotel, Accra"
                            >
                                <x-slot:icon>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </x-slot:icon>
                            </x-app.input>
                        </div>
                    </div>

                    <div class="flex justify-end pt-8 border-t border-base-content/10">
                        <x-ui.button type="button" wire:click="nextStep" x-on:click="$nextTick(() => $refs.wizardTop.scrollIntoView({ behavior: 'smooth' }))" variant="primary" size="lg" class="w-full sm:w-auto">
                            {{ Auth::check() ? __('Next: Review Summary') : __('Next: Contact Details') }} &rarr;
                        </x-ui.button>
                    </div>
                </div>

            {{-- ══ STEP 2: Contact Details ══ --}}
            @elseif($currentStep === 2)
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

            {{-- ══ STEP 3: Summary ══ --}}
            @elseif($currentStep === 3)
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
                                @if($event_location)
                                    <div class="flex items-center gap-1.5 mt-2 text-[13px] text-base-content/60 font-medium">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        {{ $event_location }}
                                    </div>
                                @endif
                            </div>
                        </div>

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
