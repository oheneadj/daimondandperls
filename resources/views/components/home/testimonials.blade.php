<section class="py-16 sm:py-24 bg-base-200 border-b border-base-content/10">
    <div class="container mx-auto px-4 lg:px-8">
        <div class="text-center mb-10 sm:mb-16">
            <span class="text-[11px] font-bold text-accent uppercase tracking-[0.2em] mb-3 block">Word of mouth</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-semibold text-base-content tracking-tight">What our clients say</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-8">
            @php
                $reviews = [
                    ['name' => 'Ama Mensah', 'event' => 'Wedding, Labadi', 'text' => "The food was incredible. Every guest was asking who the caterer was. The local dishes tasted exactly like my grandmother's cooking, just elevated!"],
                    ['name' => 'Kojo Asante', 'event' => 'Corporate Retreat', 'text' => "Diamonds & Pearls handled our 200-person retreat flawlessly. Setup was on time, staff were professional, and the continental menu was perfect."],
                    ['name' => 'Efua Boakye', 'event' => 'Outdooring', 'text' => "I was so stressed about food for my baby's outdooring, but they took over everything. The Jollof and goat meat was the highlight of the day."],
                ];
            @endphp
            @foreach($reviews as $review)
            <div class="bg-base-100 p-8 rounded-3xl border border-base-content/5 shadow-sm hover:shadow-lg transition-all relative">
                <div class="absolute -top-4 right-8 bg-[#f5b800] text-white px-3 py-1 rounded-full flex gap-1 items-center shadow-md">
                    @for($i=0; $i<5; $i++)
                    <svg class="size-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    @endfor
                </div>
                <svg class="size-10 text-primary/10 mb-6" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                <p class="text-[14px] leading-relaxed text-base-content/80 font-medium mb-8">"{{ $review['text'] }}"</p>
                <div class="flex items-center gap-4">
                    <div class="size-10 bg-base-200 rounded-full flex items-center justify-center font-bold text-base-content/50 border border-base-content/10">
                        {{ substr($review['name'], 0, 1) }}
                    </div>
                    <div>
                        <div class="text-[14px] font-bold text-base-content">{{ $review['name'] }}</div>
                        <div class="text-[11px] font-bold text-primary uppercase tracking-widest">{{ $review['event'] }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
