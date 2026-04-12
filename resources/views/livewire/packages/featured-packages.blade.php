<div
    x-data="{
        showDetails: false,
        selectedPackage: null,
        packageInCart: false,
        selectedWindowInfo: null,
        openDetails(pkg, inCart = false, windowInfo = null) {
            this.selectedPackage = pkg;
            this.packageInCart = inCart;
            this.selectedWindowInfo = windowInfo;
            this.showDetails = true;
        }
    }"
>
    @if($packages->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch pt-8">
        @foreach($packages as $package)
            @php
                $inCart = $cartItems->has($package->id);
                $ws = $windowStatuses[$package->category_id] ?? null;
                $wi = null;
                if ($ws && $ws['enabled'] && !$package->window_exempt) {
                    $wi = [
                        'open'          => $ws['open'],
                        'cutoffTs'      => $ws['cutoff']->timestamp * 1000,
                        'cutoffLabel'   => $ws['cutoffLabel'],
                        'cutoffTime'    => substr($ws['cutoff']->format('H:i'), 0, 5),
                        'deliveryLabel' => $ws['deliveryDayLabel'],
                        'deliveryDate'  => $ws['scheduledDelivery']->format('D, M j'),
                    ];
                }
            @endphp
            <div x-data="{ pkg: @js($package), wi: @js($wi) }" @click="openDetails(pkg, {{ $inCart ? 'true' : 'false' }}, wi)">
                <x-package-card
                    :package="$package"
                    :selected="$inCart"
                    :windowStatus="$ws"
                />
            </div>
        @endforeach
    </div>
    @endif

    <!-- Package Details Modal -->
    <x-package-details-modal />
</div>
