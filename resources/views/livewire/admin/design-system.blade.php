<div class="p-6 bg-[#F3F4F6] min-h-screen">
    <div class="max-w-6xl mx-auto space-y-12">
        <!-- Header -->
        <div class="flex flex-col gap-2">
            <h1 class="text-4xl font-black text-gray-900 tracking-tight">Design System</h1>
            <p class="text-gray-500 font-medium italic">Unified flat design system for AI-friendly dashboard generation.</p>
        </div>

        <!-- Design Tokens -->
        <section class="space-y-6">
            <h2 class="text-2xl font-black text-gray-900 border-b border-gray-200 pb-2">Design Tokens</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <div class="flex flex-col gap-2">
                    <div class="h-16 w-full bg-[#FE5826] rounded-lg border border-gray-200"></div>
                    <span class="text-xs font-bold text-gray-500">primary: #FE5826</span>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="h-16 w-full bg-[#F4B303] rounded-lg border border-gray-200"></div>
                    <span class="text-xs font-bold text-gray-500">secondary: #F4B303</span>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="h-16 w-full bg-[#16A34A] rounded-lg border border-gray-200"></div>
                    <span class="text-xs font-bold text-gray-500">success: #16A34A</span>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="h-16 w-full bg-[#DC2626] rounded-lg border border-gray-200"></div>
                    <span class="text-xs font-bold text-gray-500">danger: #DC2626</span>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="h-16 w-full bg-[#F59E0B] rounded-lg border border-gray-200"></div>
                    <span class="text-xs font-bold text-gray-500">warning: #F59E0B</span>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="h-16 w-full bg-[#3B82F6] rounded-lg border border-gray-200"></div>
                    <span class="text-xs font-bold text-gray-500">info: #3B82F6</span>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="h-16 w-full bg-white rounded-lg border border-gray-200"></div>
                    <span class="text-xs font-bold text-gray-500">card-bg: #FFFFFF</span>
                </div>
                <div class="flex flex-col gap-2">
                    <div class="h-16 w-full bg-[#F3F4F6] rounded-lg border border-gray-300"></div>
                    <span class="text-xs font-bold text-gray-500">page-bg: #F3F4F6</span>
                </div>
            </div>
        </section>

        <!-- 1 & 2: Grid & Base Cards -->
        <section class="space-y-6">
            <h2 class="text-2xl font-black text-gray-900 border-b border-gray-200 pb-2">Metric Card Grid (2x2)</h2>
            <div class="bg-white border border-[#E5E7EB] rounded-lg overflow-hidden grid grid-cols-2 shadow-none">
                <!-- Card 1 -->
                <div class="p-5 border-r border-b border-[#E5E7EB]">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500">New Users</p>
                            <h3 class="text-3xl font-black text-gray-900 tracking-tight">1.39K</h3>
                            <p class="text-green-600 text-xs font-bold mt-1">+147% vs prev. 28 days</p>
                        </div>
                        <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-100 text-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="p-5 border-b border-[#E5E7EB]">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Revenue</p>
                            <h3 class="text-3xl font-black text-gray-900 tracking-tight">GH₵12.5k</h3>
                            <p class="text-green-600 text-xs font-bold mt-1">+24% vs last week</p>
                        </div>
                        <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-green-100 text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="p-5 border-r border-[#E5E7EB]">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Active Bookings</p>
                            <h3 class="text-3xl font-black text-gray-900 tracking-tight">42</h3>
                            <p class="text-blue-600 text-xs font-bold mt-1">12 pending verification</p>
                        </div>
                        <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        </div>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="p-5">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-medium text-gray-500">AOV</p>
                            <h3 class="text-3xl font-black text-gray-900 tracking-tight">GH₵298</h3>
                            <p class="text-orange-600 text-xs font-bold mt-1">-5% drop this month</p>
                        </div>
                        <div class="w-9 h-9 flex items-center justify-center rounded-lg bg-orange-100 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4 & 5: Analytics & Card Header -->
        <section class="space-y-6">
            <h2 class="text-2xl font-black text-gray-900 border-b border-gray-200 pb-2">Analytics Chart Card</h2>
            <div class="bg-white border border-[#E5E7EB] rounded-lg p-6 shadow-none">
                <!-- Card Header -->
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Views</p>
                        <h2 class="text-4xl font-black text-gray-900 tracking-tight">12,740</h2>
                        <span class="text-green-600 text-xs font-bold font-mono tracking-tighter uppercase">+2.6% GROWTH</span>
                    </div>

                    <div class="flex gap-1 bg-gray-100 p-1 rounded-lg">
                        <button class="px-4 py-1.5 text-xs font-bold uppercase tracking-widest bg-white rounded-md border border-gray-200 shadow-none">Day</button>
                        <button class="px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-gray-900">Week</button>
                        <button class="px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-gray-500 hover:text-gray-900">Month</button>
                    </div>
                </div>

                <!-- Simulation of Chart Area -->
                <div class="h-48 w-full bg-gray-50 rounded-lg border border-dashed border-gray-200 flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-x-0 bottom-0 h-24 bg-blue-500/10"></div>
                    <div class="absolute bottom-24 inset-x-0 border-t-2 border-dashed border-orange-400 opacity-50"></div>
                    <span class="text-xs font-bold uppercase tracking-widest text-gray-400 z-10">Chart Visualization Placeholder</span>
                </div>
            </div>
        </section>

        <!-- Order Card Component -->
        <section class="space-y-6">
            <h2 class="text-2xl font-black text-gray-900 border-b border-gray-200 pb-2">Order Card Component</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Example Card 1 -->
                <div class="bg-white border border-[#E5E7EB] rounded-xl p-4 space-y-4 shadow-none">
                    <!-- Header -->
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-black text-gray-900">Order #12458</h3>
                            <p class="text-sm font-medium text-gray-500">John Doe</p>
                        </div>
                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full bg-yellow-100 text-yellow-700">
                            Pending
                        </span>
                    </div>

                    <!-- Items -->
                    <div class="text-sm text-gray-800 space-y-1 font-medium bg-gray-50 p-3 rounded-lg border border-gray-100">
                        <p><span class="text-primary font-bold">2×</span> Chicken Burger</p>
                        <p><span class="text-primary font-bold">1×</span> Fries</p>
                        <p><span class="text-primary font-bold">1×</span> Coke</p>
                    </div>

                    <!-- Meta -->
                    <div class="text-[11px] text-gray-500 uppercase tracking-widest font-bold flex flex-wrap gap-x-4 gap-y-1">
                        <p>Total: GH₵24.50</p>
                        <p>Payment: Card</p>
                        <p>Time: 12:45 PM</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 justify-end pt-2">
                        <button class="bg-[#FE5826] text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-[#e14b1f] transition-colors">
                            Accept
                        </button>
                        <button class="bg-[#16A34A] text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-[#13873e] transition-colors">
                            Ready
                        </button>
                        <button class="border border-gray-200 text-gray-400 px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>

                <!-- Status Variant Reference -->
                <div class="space-y-4">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Status Badge Variants</p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full bg-yellow-100 text-yellow-700 border border-yellow-200/50">Pending</span>
                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full bg-blue-100 text-blue-800 border border-blue-200/50">Preparing</span>
                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full bg-green-100 text-green-800 border border-green-200/50">Ready</span>
                        <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full bg-red-100 text-red-800 border border-red-200/50">Cancelled</span>
                    </div>

                    <div class="bg-gray-900 rounded-lg p-5 text-white/90 text-xs font-mono">
                        <p class="text-[#F4B303] mb-2">// AI Construction Logic</p>
                        <p>component: OrderCard</p>
                        <p>structure: Header > Items > Meta > Actions</p>
                        <p>radius: 12px</p>
                        <p>gap: 12px</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- AI Rules -->
        <section class="bg-gray-900 rounded-lg p-8 text-white space-y-4">
            <h2 class="text-xl font-black tracking-tight text-[#FE5826]">AI Agent Generation Rules</h2>
            <div class="grid md:grid-cols-2 gap-8 text-sm font-mono leading-relaxed opacity-90">
                <ul class="space-y-2">
                    <li><span class="text-[#F4B303]">design-style:</span> flat</li>
                    <li><span class="text-[#F4B303]">use-soft-contrast:</span> true</li>
                    <li><span class="text-[#F4B303]">card-radius:</span> 8px (rounded-lg)</li>
                    <li><span class="text-[#F4B303]">card-border:</span> #E5E7EB</li>
                </ul>
                <ul class="space-y-2">
                    <li><span class="text-[#F4B303]">metric-grid:</span> 2x2</li>
                    <li><span class="text-[#F4B303]">card-padding:</span> 20px</li>
                    <li><span class="text-[#F4B303]">component-reuse:</span> enabled</li>
                    <li><span class="text-[#F4B303]">page-background:</span> #F3F4F6</li>
                </ul>
            </div>
        </section>
    </div>
</div>
