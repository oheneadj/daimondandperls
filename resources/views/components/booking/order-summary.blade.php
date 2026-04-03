@props([
    'cartItems' => [],
    'cartTotal' => 0,
    'isEvent' => false,
])

<div class="lg:col-span-5 space-y-8">
    <div class="bg-base-100 border border-base-content/10 rounded-[24px] p-5 sm:p-8 lg:p-10 shadow-sm">
        <h4 class="text-2xl font-semibold text-base-content mb-8 pb-4 border-b border-base-content/10">Order Summary</h4>

        @if(count($cartItems) > 0)
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
        @else
            <div class="mb-10 text-center py-6">
                <p class="text-[13px] text-base-content/40 italic">No menu items selected yet.</p>
            </div>
        @endif

        <div class="space-y-4 pt-8 border-t-2 border-dashed border-base-content/10">
            @if($isEvent)
                <div class="flex justify-between items-center text-[14px] text-base-content/60 font-medium">
                    <span>Menu Selections</span>
                    <span class="text-base-content/40 italic">For reference</span>
                </div>
                <div class="flex justify-between items-center pt-4">
                    <span class="text-xl font-bold text-base-content">Quote Total</span>
                    <span class="text-2xl font-bold text-primary">Pending</span>
                </div>
                <p class="text-[11px] text-base-content/40 leading-relaxed">Final pricing will be provided by our team after reviewing your event requirements.</p>
            @else
                <div class="flex justify-between items-center text-[14px] text-base-content/60 font-medium">
                    <span>Subtotal</span>
                    <span>GH₵ {{ number_format($cartTotal, 0) }}</span>
                </div>
                <div class="flex justify-between items-center text-[14px] text-base-content/60 font-medium">
                    <span>Service Charge</span>
                    <span class="text-success">Complimentary</span>
                </div>
                <div class="flex justify-between items-center pt-4">
                    <span class="text-xl font-bold text-base-content">Total Amount</span>
                    <span class="text-3xl font-bold text-primary">GH₵ {{ number_format($cartTotal, 0) }}</span>
                </div>
            @endif
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

    @unless($isEvent)
        <div class="flex flex-col gap-4">
            <div class="flex items-center gap-4 text-base-content/60 opacity-50 grayscale hover:grayscale-0 transition-all cursor-pointer">
                <div class="bg-white border border-base-content/10 px-3 py-1 rounded text-[10px] font-black tracking-tighter">VISA</div>
                <div class="bg-white border border-base-content/10 px-3 py-1 rounded text-[10px] font-black tracking-tighter">Mastercard</div>
                <div class="bg-white border border-base-content/10 px-3 py-1 rounded text-[10px] font-black tracking-tighter whitespace-nowrap">MTN MoMo</div>
            </div>
        </div>
    @endunless
</div>
