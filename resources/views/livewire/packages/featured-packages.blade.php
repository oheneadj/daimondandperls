<div 
    x-data="{ 
        showDetails: false, 
        selectedPackage: null,
        openDetails(pkg) {
            this.selectedPackage = pkg;
            this.showDetails = true;
        }
    }"
>
    @if($packages->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch pt-8">
        @foreach($packages as $package)
            @php
                $inCart = $cartItems->has($package->id);
            @endphp
            <div @click="openDetails({{ json_encode($package) }})">
                <x-package-card 
                    :package="$package" 
                    :selected="$inCart" 
                />
            </div>
        @endforeach
    </div>
    @endif

    <!-- Package Details Modal -->
    <x-package-details-modal />
</div>
