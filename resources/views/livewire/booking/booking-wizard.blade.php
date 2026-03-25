<div class="bg-base-200 min-h-screen py-10 lg:py-20">
    <div class="container mx-auto px-4 lg:px-8 max-w-6xl">
        <!-- Progress Bar (5 Steps) -->
        <div class="mb-12 lg:mb-16">
            <div class="flex items-center justify-between relative max-w-3xl mx-auto">
                {{-- Line connector --}}
                <div class="absolute top-5 left-0 w-full h-0.5 border-base-content/10 -z-10"></div>
                <div class="absolute top-5 left-0 h-0.5 bg-primary -z-10 transition-all duration-700" style="width: {{ ($currentStep - 1) * 25 }}%"></div>

                @foreach(['Review', 'Contact', 'Event', 'Payment', 'Done'] as $index => $label)
                    @php $stepNum = $index + 1; @endphp
                    <div wire:key="step-nav-{{ $stepNum }}" class="flex flex-col items-center gap-3">
                        <div @class([
                            'size-10 rounded-full flex items-center justify-center  text-sm font-bold transition-all duration-500',
                            'bg-primary text-white shadow-xl scale-110 ring-4 ring-dp-rose-soft' => $currentStep === $stepNum,
                            'bg-primary text-white' => $currentStep > $stepNum,
                            'bg-base-100 text-dp-text-disabled border-2 border-base-content/10' => $currentStep < $stepNum,
                        ])>
                            @if($currentStep > $stepNum)
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @else
                                {{ $stepNum }}
                            @endif
                        </div>
                        <span @class([
                            'text-[10px] uppercase tracking-[0.15em] font-bold hidden sm:block',
                            'text-primary' => $currentStep === $stepNum,
                            'text-base-content/60' => $currentStep !== $stepNum,
                        ])>{{ $label }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            <!-- Main Form Column -->
            <div class="lg:col-span-7">
                <div class="bg-base-100 border border-base-content/10 rounded-[24px] p-8 lg:p-12 shadow-sm">
                    @if($currentStep === 1)
                        <div wire:key="step-review" class="animate-fade-in space-y-8">
                            <div>
                                <h2 class=" text-3xl font-semibold text-base-content mb-2 text-center sm:text-left">Review Selection</h2>
                                <p class="text-base-content/60 text-[14px] font-medium text-center sm:text-left">Review your chosen packages and quantities before proceeding.</p>
                            </div>

                            <div class="space-y-6">
                                @foreach($cartItems as $item)
                                    <div wire:key="cart-review-{{ $item['package']->id }}" class="flex items-center gap-6 p-4 bg-base-200 rounded-2xl border border-base-content/10 group transition-all hover:bg-white hover:shadow-sm">
                                        <div class="size-20 bg-base-200-mid rounded-xl overflow-hidden shrink-0 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-primary/20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class=" text-lg lg:text-xl font-semibold text-base-content mb-1">{{ $item['package']->name }}</h4>
                                            <div class="flex items-center gap-4">
                                                <div class="text-[14px] font-bold text-primary">GH₵ {{ number_format($item['package']->price, 0) }} / head</div>
                                                <div class="size-1 border-base-content/10 rounded-full"></div>
                                                <div class="text-[13px] text-base-content/60 font-medium">Qty: {{ $item['quantity'] }} attendees</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex justify-end pt-8 border-t border-base-content/10">
                                <x-ui.button type="button" wire:click="nextStep" variant="primary" size="lg" class="w-full sm:w-auto shadow-md">
                                    {{ __('Next: Contact Details') }} &rarr;
                                </x-ui.button>
                            </div>
                        </div>
                    @elseif($currentStep === 2)
                        <div wire:key="step-contact" class="animate-fade-in space-y-8">
                            <div>
                                <h2 class=" text-3xl font-semibold text-base-content mb-2">Contact Details</h2>
                                <p class="text-base-content/60 text-[14px] font-medium">Enter your information so we can coordinate your catering experience.</p>
                            </div>

                            <div class="grid gap-6">
                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-1">Your Full Name</label>
                                    <input type="text" wire:model="name" class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[15px] font-medium placeholder:text-dp-text-disabled" placeholder="e.g. Grace Ayensu">
                                    @error('name') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-1">Phone Number</label>
                                    <input type="tel" wire:model="phone" class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[15px] font-medium placeholder:text-dp-text-disabled" placeholder="024 XXX XXXX">
                                    @error('phone') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-1">Email <span class="italic lowercase font-medium opacity-50">(Recommended)</span></label>
                                    <input type="email" wire:model="email" class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[15px] font-medium placeholder:text-dp-text-disabled" placeholder="grace@example.com">
                                    @error('email') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                @if(!auth()->check())
                                    <div class="mt-4 p-6 bg-base-200 border border-base-content/10 rounded-2xl space-y-4">
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="checkbox" wire:model.live="createAccount" class="size-5 rounded border-base-content/10 text-primary focus:ring-dp-rose-soft transition-all">
                                            <span class="text-[14px] font-bold text-base-content group-hover:text-primary transition-colors">
                                                Create an account to track your booking and get more personalized service?
                                            </span>
                                        </label>

                                        @if($createAccount)
                                            <div class="space-y-2 animate-fade-in pt-2">
                                                <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-1">Create Password</label>
                                                <input type="password" wire:model="password" class="w-full px-5 py-4 bg-white border border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[15px] font-medium placeholder:text-dp-text-disabled" placeholder="Minimum 8 characters">
                                                @error('password') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
                                                <p class="text-[11px] text-base-content/60 font-medium italic mt-1">Confirming your booking will automatically create your account and log you in.</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-8 border-t border-base-content/10">
                                <x-ui.button variant="ghost" wire:click="previousStep" class="w-full sm:w-auto font-bold">&larr; {{ __('Back') }}</x-ui.button>
                                <x-ui.button variant="primary" size="lg" wire:click="nextStep" class="w-full sm:w-auto shadow-md">
                                    {{ __('Next: Event Location') }}
                                </x-ui.button>
                            </div>
                        </div>
                    @elseif($currentStep === 3)
                        <div wire:key="step-event" class="animate-fade-in space-y-8">
                            <div>
                                <h2 class=" text-3xl font-semibold text-base-content mb-2">Event Selection <span class="text-base-content/60 font-normal text-lg">(Optional)</span></h2>
                                <p class="text-base-content/60 text-[14px] font-medium">When and where is your event taking place? You can skip this if undecided.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2 space-y-2">
                                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-1">Event Date <span class="italic lowercase font-medium opacity-50">(Optional)</span></label>
                                    <input type="date" wire:model="event_date" class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[15px] font-medium">
                                    @error('event_date') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-1">Start Time</label>
                                    <input type="time" wire:model="event_start_time" class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[15px] font-medium">
                                    @error('event_start_time') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-1">End Time</label>
                                    <input type="time" wire:model="event_end_time" class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[15px] font-medium">
                                    @error('event_end_time') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2 space-y-2">
                                    <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-1">Occasion Type <span class="italic lowercase font-medium opacity-50">(Optional)</span></label>
                                    <select wire:model.live="event_type" class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[15px] font-medium outline-none">
                                        <option value="">Select Event Type...</option>
                                        <option value="wedding">Wedding Reception</option>
                                        <option value="birthday">Birthday Party</option>
                                        <option value="corporate">Corporate Event</option>
                                        <option value="funeral">Funeral Rite</option>
                                        <option value="party">Social Gathering</option>
                                        <option value="other">Other Event</option>
                                    </select>
                                    @error('event_type') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
                                </div>

                                @if($event_type === 'other')
                                    <div class="md:col-span-2 space-y-2 animate-fade-in">
                                        <label class="text-[11px] font-bold uppercase tracking-widest text-base-content/60 ml-1">Specify Occasion</label>
                                        <input type="text" wire:model="event_type_other" class="w-full px-5 py-4 bg-base-200 border border-base-content/10 focus:border-dp-rose focus:ring-4 focus:ring-primary/20 rounded-xl transition-all text-[15px] font-medium" placeholder="Describe the event...">
                                        @error('event_type_other') <span class="text-xs font-bold text-error mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-8 border-t border-base-content/10">
                                <x-ui.button variant="ghost" wire:click="previousStep" class="w-full sm:w-auto font-bold">&larr; {{ __('Back') }}</x-ui.button>
                                <x-ui.button variant="primary" size="lg" wire:click="nextStep" class="w-full sm:w-auto shadow-md">
                                    {{ __('Next: Review Summary') }}
                                </x-ui.button>
                            </div>
                        </div>
                    @elseif($currentStep === 4)
                        <div wire:key="step-payment" class="animate-fade-in space-y-8">
                            <div>
                                <h2 class=" text-3xl font-semibold text-base-content mb-2">Final Summary</h2>
                                <p class="text-base-content/60 text-[14px] font-medium">Verify all details before proceeding to secure payment.</p>
                            </div>

                            <div class="bg-base-200 rounded-2xl p-6 lg:p-8 space-y-6 border border-base-content/10">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-3">Client Information</div>
                                        <div class=" text-lg font-semibold text-base-content">{{ $name }}</div>
                                        <div class="text-[13px] text-base-content/60 font-medium">{{ $phone }}</div>
                                        @if($email) <div class="text-[13px] text-base-content/60 font-medium">{{ $email }}</div> @endif
                                    </div>
                                    <div>
                                        <div class="text-[10px] font-bold text-primary uppercase tracking-widest mb-3">Event Particulars</div>
                                        @if($event_date || $event_type)
                                            <div class=" text-lg font-semibold text-base-content">
                                                {{ $event_date ? \Carbon\Carbon::parse($event_date)->format('D, M j, Y') : 'Date TBD' }}
                                            </div>
                                            <div class="text-[13px] text-base-content/60 font-medium">
                                                @if($event_start_time) {{ \Carbon\Carbon::parse($event_start_time)->format('g:i A') }} @endif
                                                @if($event_end_time) — {{ \Carbon\Carbon::parse($event_end_time)->format('g:i A') }} @endif
                                            </div>
                                            @if($event_type)
                                                <div class="inline-block mt-2 bg-primary/5 text-primary text-[10px] font-bold px-3 py-1 rounded-full border border-dp-rose/10 uppercase tracking-widest">
                                                    {{ $event_type === 'other' ? $event_type_other : $event_type }}
                                                </div>
                                            @endif
                                        @else
                                            <div class="text-[13px] text-base-content/60 font-medium italic">
                                                No specific event details provided.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="bg-success/5 border border-dp-success/20 rounded-2xl p-5 flex items-start gap-4">
                                <div class="size-6 bg-success text-white rounded-full flex items-center justify-center mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                </div>
                                <div>
                                    <div class="text-[13px] font-bold text-dp-success uppercase tracking-wide">Ready for confirmation</div>
                                    <p class="text-[12px] text-dp-success/80 font-medium leading-relaxed">By clicking finalize, you agree to our catering terms. A booking reference will be generated for your payment.</p>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-8 border-t border-base-content/10">
                                <x-ui.button variant="ghost" wire:click="previousStep" class="w-full sm:w-auto font-bold">&larr; {{ __('Back') }}</x-ui.button>
                                <x-ui.button variant="primary" size="lg" wire:click="confirmBooking" wire:loading.attr="disabled" :loading="$loading === 'confirmBooking'" class="w-full sm:w-auto shadow-xl text-lg">
                                    {{ __('Finalise & Pay') }}
                                </x-ui.button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar Summary -->
            <div class="lg:col-span-5 space-y-8">
                <div class="bg-base-100 border border-base-content/10 rounded-[24px] p-8 lg:p-10 shadow-sm">
                    <h4 class=" text-2xl font-semibold text-base-content mb-8 pb-4 border-b border-base-content/10">Order Summary</h4>
                    
                    <div class="space-y-6 mb-10">
                        @foreach($cartItems as $item)
                        <div wire:key="cart-summary-{{ $item['package']->id }}" class="flex items-center justify-between gap-4">
                            <div class="flex-1">
                                <div class="text-[14px] font-bold text-base-content line-clamp-1">{{ $item['package']->name }}</div>
                                <div class="text-[11px] text-base-content/60 font-medium">GH₵ {{ number_format($item['package']->price, 0) }} × {{ $item['quantity'] }}</div>
                            </div>
                            <div class="text-[14px] font-bold text-base-content whitespace-nowrap">
                                GH₵ {{ number_format($item['subtotal'], 0) }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="space-y-4 pt-8 border-t-2 border-dashed border-base-content/10">
                        <div class="flex justify-between items-center text-[14px] text-base-content/60 font-medium">
                            <span>Subtotal</span>
                            <span>GH₵ {{ number_format($cartTotal, 0) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[14px] text-base-content/60 font-medium">
                            <span>Service Charge</span>
                            <span class="text-dp-success">Complimentary</span>
                        </div>
                        <div class="flex justify-between items-center pt-4">
                            <span class=" text-xl font-bold text-base-content">Total Amount</span>
                            <span class=" text-3xl font-bold text-primary">GH₵ {{ number_format($cartTotal, 0) }}</span>
                        </div>
                    </div>

                    <div class="mt-10 p-5 bg-base-200 border border-base-content/10 rounded-2xl flex items-center gap-4">
                        <div class="size-10 bg-base-100 rounded-full flex items-center justify-center shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <div class="text-[11px] text-base-content/60 font-medium leading-relaxed uppercase tracking-widest">
                            Secure 256-bit encrypted checkout handling
                        </div>
                    </div>
                </div>

                <!-- Trust Badges -->
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-4 text-base-content/60 opacity-50 grayscale hover:grayscale-0 transition-all cursor-pointer">
                        <div class="bg-white border border-base-content/10 px-3 py-1 rounded text-[10px] font-black tracking-tighter">VISA</div>
                        <div class="bg-white border border-base-content/10 px-3 py-1 rounded text-[10px] font-black tracking-tighter">Mastercard</div>
                        <div class="bg-white border border-base-content/10 px-3 py-1 rounded text-[10px] font-black tracking-tighter whitespace-nowrap">MTN MoMo</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
