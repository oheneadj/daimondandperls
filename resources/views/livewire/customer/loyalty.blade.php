<div>
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-[28px] font-semibold text-base-content leading-tight">Loyalty & Points</h1>
        <p class="text-base-content/50 text-[14px] font-medium mt-1">Earn points on every completed order and redeem them for discounts.</p>
    </div>

    {{-- Balance + Referral cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">

        {{-- Points balance card --}}
        <div class="bg-gradient-to-br from-primary to-primary/80 rounded-2xl p-6 text-white shadow-lg shadow-primary/20 relative overflow-hidden">
            <div class="absolute top-0 right-0 size-40 bg-white/5 rounded-full blur-2xl -translate-y-1/2 translate-x-1/4 pointer-events-none"></div>
            <div class="relative z-10">
                <p class="text-[11px] font-bold uppercase tracking-[0.15em] text-white/60 mb-3">Your Balance</p>
                <div class="flex items-end gap-3 mb-1">
                    <span class="text-[48px] font-bold leading-none">{{ number_format($balance) }}</span>
                    <span class="text-[16px] font-semibold text-white/70 mb-2">pts</span>
                </div>
                <p class="text-[14px] text-white/70 font-medium">≈ GH₵{{ number_format($balanceGhc, 2) }} in discounts</p>
                <p class="text-[11px] text-white/45 mt-3">{{ number_format($redemptionRate) }} points = GH₵1.00 discount</p>
            </div>
        </div>

        {{-- Referral card --}}
        <div class="bg-base-100 border border-base-content/10 rounded-2xl p-6 shadow-sm">
            <p class="text-[11px] font-bold uppercase tracking-[0.15em] text-base-content/40 mb-3">Refer a Friend</p>
            @if($referralUrl)
                <p class="text-[13px] text-base-content/60 leading-relaxed mb-4">
                    Share your link and earn <span class="text-primary font-bold">{{ number_format(dpc_setting('loyalty_referral_bonus', 50)) }} bonus points</span> when they complete their first order.
                </p>
                <div class="flex items-center gap-2 bg-base-200 rounded-xl px-3 py-2.5 mb-4"
                     x-data="{
                         copied: false,
                         copyUrl() {
                             const url = '{{ $referralUrl }}';
                             if (navigator.clipboard && window.isSecureContext) {
                                 navigator.clipboard.writeText(url).then(() => {
                                     this.copied = true;
                                     setTimeout(() => this.copied = false, 2000);
                                 });
                             } else {
                                 const el = document.createElement('textarea');
                                 el.value = url;
                                 el.style.position = 'fixed';
                                 el.style.opacity = '0';
                                 document.body.appendChild(el);
                                 el.select();
                                 document.execCommand('copy');
                                 document.body.removeChild(el);
                                 this.copied = true;
                                 setTimeout(() => this.copied = false, 2000);
                             }
                         }
                     }">
                    <span class="text-[12px] text-base-content/60 font-mono truncate flex-1">{{ $referralUrl }}</span>
                    <button
                        @click="copyUrl()"
                        class="shrink-0 text-[11px] font-bold text-primary hover:text-primary/80 transition-colors"
                        x-text="copied ? 'Copied!' : 'Copy'"
                    ></button>
                </div>
                <div class="flex items-center gap-4 text-[12px] text-base-content/50">
                    <span><span class="font-bold text-base-content">{{ $referralCount }}</span> friends referred</span>
                    <span><span class="font-bold text-primary">+{{ number_format($referralPointsEarned) }} pts</span> earned</span>
                </div>
            @else
                <p class="text-[13px] text-base-content/50">Referral link will appear here once your account is fully set up.</p>
            @endif
        </div>

    </div>

    {{-- Transaction history --}}
    <div class="bg-base-100 border border-base-content/10 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-base-content/5">
            <h2 class="text-[15px] font-semibold text-base-content">Transaction History</h2>
        </div>

        @if($transactions->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="size-14 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-[15px] font-semibold text-base-content">No transactions yet</p>
                <p class="text-[13px] text-base-content/50 mt-1">Complete an order to start earning points.</p>
            </div>
        @else
            <div class="divide-y divide-base-content/5">
                @foreach($transactions as $tx)
                    <div class="flex items-center gap-4 px-6 py-4">
                        {{-- Icon --}}
                        <div @class([
                            'size-9 rounded-xl flex items-center justify-center shrink-0',
                            'bg-success/10' => $tx->type === 'earned',
                            'bg-primary/10' => $tx->type === 'referral_bonus',
                            'bg-error/10'   => $tx->type === 'redeemed',
                            'bg-base-200'   => $tx->type === 'manual_adjustment',
                        ])>
                            @if($tx->type === 'earned')
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @elseif($tx->type === 'referral_bonus')
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                            @elseif($tx->type === 'redeemed')
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 01-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                            @endif
                        </div>

                        {{-- Description + date --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-semibold text-base-content truncate">{{ $tx->description }}</p>
                            <p class="text-[11px] text-base-content/40 font-medium mt-0.5">{{ $tx->created_at->format('d M Y, g:ia') }}</p>
                        </div>

                        {{-- Points --}}
                        <div @class([
                            'text-[15px] font-bold shrink-0',
                            'text-success' => $tx->points > 0,
                            'text-error'   => $tx->points < 0,
                        ])>
                            {{ $tx->points > 0 ? '+' : '' }}{{ number_format($tx->points) }} pts
                        </div>
                    </div>
                @endforeach
            </div>

            @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-base-content/5">
                    {{ $transactions->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
