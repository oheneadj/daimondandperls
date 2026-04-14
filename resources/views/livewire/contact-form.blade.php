<div>
    @if($submitted)
        {{-- Success state --}}
        <div class="p-8 bg-success/10 border border-success/15 rounded-2xl flex flex-col items-center text-center gap-4">
            <div class="size-14 bg-success/10 rounded-full flex items-center justify-center">
                <svg class="size-7 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-base-content mb-1">Message Sent!</h3>
                <p class="text-[14px] text-base-content/60 font-medium leading-relaxed">
                    Thank you for reaching out. We'll get back to you within 24 hours.
                </p>
            </div>
            <button wire:click="$set('submitted', false)" class="text-[13px] font-medium text-primary hover:underline transition-colors">
                Send another message
            </button>
        </div>
    @else
        <form wire:submit="submit" class="space-y-5">
            <div class="grid sm:grid-cols-2 gap-5">
                <div class="space-y-1.5">
                    <label class="text-[13px] font-medium text-base-content block">Full Name</label>
                    <input
                        wire:model="name"
                        type="text"
                        class="w-full px-[14px] py-[10px] text-[15px] bg-base-100 border rounded-lg transition-all duration-120 placeholder:text-base-content/40 @error('name') border-error focus:border-error focus:ring-3 focus:ring-error/20 @else border-base-content/10 focus:border-primary focus:ring-3 focus:ring-primary/20 @enderror"
                        placeholder="Your full name"
                    >
                    @error('name')
                        <p class="text-[12px] text-error font-medium flex items-center gap-1 mt-1">
                            <svg class="size-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label class="text-[13px] font-medium text-base-content block">Phone Number</label>
                    <input
                        wire:model="phone"
                        type="tel"
                        class="w-full px-[14px] py-[10px] text-[15px] bg-base-100 border rounded-lg transition-all duration-120 placeholder:text-base-content/40 @error('phone') border-error focus:border-error focus:ring-3 focus:ring-error/20 @else border-base-content/10 focus:border-primary focus:ring-3 focus:ring-primary/20 @enderror"
                        placeholder="0244 000 000"
                    >
                    @error('phone')
                        <p class="text-[12px] text-error font-medium flex items-center gap-1 mt-1">
                            <svg class="size-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-[13px] font-medium text-base-content block">Email Address</label>
                <input
                    wire:model="email"
                    type="email"
                    class="w-full px-[14px] py-[10px] text-[15px] bg-base-100 border rounded-lg transition-all duration-120 placeholder:text-base-content/40 @error('email') border-error focus:border-error focus:ring-3 focus:ring-error/20 @else border-base-content/10 focus:border-primary focus:ring-3 focus:ring-primary/20 @enderror"
                    placeholder="you@example.com"
                >
                @error('email')
                    <p class="text-[12px] text-error font-medium flex items-center gap-1 mt-1">
                        <svg class="size-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="space-y-1.5">
                <label class="text-[13px] font-medium text-base-content block">Inquiry Type</label>
                <div class="relative">
                    <select
                        wire:model="inquiry_type"
                        class="w-full px-[14px] py-[10px] text-[15px] bg-base-100 border border-base-content/10 rounded-lg focus:border-primary focus:ring-3 focus:ring-primary/20 transition-all duration-120 appearance-none pr-10"
                    >
                        <option>General Inquiry</option>
                        <option>Meal Catering</option>
                        <option>Event Catering</option>
                        <option>Corporate Event</option>
                        <option>Wedding</option>
                        <option>Other</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 pr-3 flex items-center text-base-content/40">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-[13px] font-medium text-base-content block">Message</label>
                <textarea
                    wire:model="message"
                    rows="5"
                    class="w-full px-[14px] py-[10px] text-[15px] bg-base-100 border rounded-lg transition-all duration-120 placeholder:text-base-content/40 resize-none @error('message') border-error focus:border-error focus:ring-3 focus:ring-error/20 @else border-base-content/10 focus:border-primary focus:ring-3 focus:ring-primary/20 @enderror"
                    placeholder="Tell us about your event — date, guest count, menu preferences..."
                ></textarea>
                @error('message')
                    <p class="text-[12px] text-error font-medium flex items-center gap-1 mt-1">
                        <svg class="size-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <x-ui.button type="submit" variant="primary" size="lg" class="w-full" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="submit">Send Message</span>
                <span wire:loading wire:target="submit" class="flex items-center gap-2">
                    <svg class="animate-spin size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sending...
                </span>
            </x-ui.button>
        </form>
    @endif
</div>
