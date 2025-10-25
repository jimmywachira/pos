<x-layouts.app title="Documentation - DemoPOS">
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/50 backdrop-blur-xl shadow-xl rounded-lg overflow-hidden">
                <div class="p-6 sm:p-10">
                    <div class="text-center">
                        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">Documentation</h1>
                        <p class="mt-6 max-w-2xl mx-auto text-xl text-gray-600">Everything you need to integrate with DemoPOS and get the most out of our platform.</p>
                    </div>

                    <div class="mt-16 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="rounded-lg bg-white shadow p-6">
                            <h3 class="text-xl font-semibold text-gray-900">Getting Started</h3>
                            <p class="mt-2 text-base text-gray-500">A step-by-step guide to setting up your DemoPOS account and hardware.</p>
                            <a href="{{ route('guides') }}" class="mt-4 text-blue-600 hover:text-blue-800 font-semibold">Read the guide &rarr;</a>
                        </div>
                        <div class="rounded-lg bg-white shadow p-6">
                            <h3 class="text-xl font-semibold text-gray-900">API Reference</h3>
                            <p class="mt-2 text-base text-gray-500">Detailed information about our API endpoints, parameters, and responses.</p>
                            <a href="{{ route('api-status') }}" class="mt-4 text-blue-600 hover:text-blue-800 font-semibold">Explore the API &rarr;</a>
                        </div>
                        <!-- More documentation sections -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
