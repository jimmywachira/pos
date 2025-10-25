<x-layouts.app title="Pricing - DemoPOS">
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">Simple, Transparent Pricing</h1>
                <p class="mt-6 max-w-2xl mx-auto text-xl text-gray-600">Choose the plan that's right for your business. No hidden fees, no surprises.</p>
            </div>

            <div class="mt-16 space-y-12 lg:space-y-0 lg:grid lg:grid-cols-3 lg:gap-x-8">
                {{-- Plan 1: Basic --}}
                <div class="relative p-8 bg-white/50 backdrop-blur-xl border border-gray-200 rounded-2xl shadow-sm flex flex-col">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900">Starter</h3>
                        <p class="mt-4 flex items-baseline text-gray-900">
                            <span class="text-5xl font-extrabold tracking-tight">$29</span>
                            <span class="ml-1 text-xl font-semibold">/mo</span>
                        </p>
                        <p class="mt-6 text-gray-500">Perfect for new businesses and small shops.</p>

                        <ul role="list" class="mt-6 space-y-6">
                            <li class="flex"><span class="text-blue-500">&#10003;</span><span class="ml-3 text-gray-500">Point of Sale</span></li>
                            <li class="flex"><span class="text-blue-500">&#10003;</span><span class="ml-3 text-gray-500">Basic Inventory</span></li>
                            <li class="flex"><span class="text-blue-500">&#10003;</span><span class="ml-3 text-gray-500">Basic Reporting</span></li>
                        </ul>
                    </div>
                    <a href="#" class="bg-blue-50 text-blue-700 hover:bg-blue-100 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium">Get Started</a>
                </div>

                {{-- Plan 2: Pro --}}
                <div class="relative p-8 bg-white/50 backdrop-blur-xl border-2 border-blue-500 rounded-2xl shadow-sm flex flex-col">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900">Professional</h3>
                        <p class="absolute top-0 py-1.5 px-4 bg-blue-500 rounded-full text-xs font-semibold uppercase tracking-wide text-white transform -translate-y-1/2">Most popular</p>
                        <p class="mt-4 flex items-baseline text-gray-900">
                            <span class="text-5xl font-extrabold tracking-tight">$79</span>
                            <span class="ml-1 text-xl font-semibold">/mo</span>
                        </p>
                        <p class="mt-6 text-gray-500">For growing businesses that need more power.</p>

                        <ul role="list" class="mt-6 space-y-6">
                            <li class="flex"><span class="text-blue-500">&#10003;</span><span class="ml-3 text-gray-500">Everything in Starter</span></li>
                            <li class="flex"><span class="text-blue-500">&#10003;</span><span class="ml-3 text-gray-500">Advanced Inventory</span></li>
                            <li class="flex"><span class="text-blue-500">&#10003;</span><span class="ml-3 text-gray-500">Advanced Reporting</span></li>
                            <li class="flex"><span class="text-blue-500">&#10003;</span><span class="ml-3 text-gray-500">Customer Management</span></li>
                        </ul>
                    </div>
                    <a href="#" class="bg-blue-500 text-white hover:bg-blue-600 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium">Choose Pro</a>
                </div>

                {{-- Plan 3: Enterprise --}}
                <div class="relative p-8 bg-white/50 backdrop-blur-xl border border-gray-200 rounded-2xl shadow-sm flex flex-col">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900">Enterprise</h3>
                        <p class="mt-4 flex items-baseline text-gray-900">
                            <span class="text-5xl font-extrabold tracking-tight">Contact Us</span>
                        </p>
                        <p class="mt-6 text-gray-500">For large-scale operations with custom needs.</p>

                        <ul role="list" class="mt-6 space-y-6">
                            <li class="flex"><span class="text-blue-500">&#10003;</span><span class="ml-3 text-gray-500">Everything in Pro</span></li>
                            <li class="flex"><span class="text-blue-500">&#10003;</span><span class="ml-3 text-gray-500">API Access</span></li>
                            <li class="flex"><span class="text-blue-500">&#10003;</span><span class="ml-3 text-gray-500">Dedicated Support</span></li>
                        </ul>
                    </div>
                    <a href="#" class="bg-blue-50 text-blue-700 hover:bg-blue-100 mt-8 block w-full py-3 px-6 border border-transparent rounded-md text-center font-medium">Contact Sales</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
