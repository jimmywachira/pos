<x-layouts.app title="Blog - DemoPOS">
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">The DemoPOS Blog</h1>
                <p class="mt-6 max-w-2xl mx-auto text-xl text-gray-600">Insights, tips, and stories from the world of retail and e-commerce.</p>
            </div>

            <div class="mt-16 max-w-lg mx-auto grid gap-8 lg:grid-cols-3 lg:max-w-none">
                {{-- Blog Post Example 1 --}}
                <div class="flex flex-col rounded-lg shadow-lg overflow-hidden bg-white/50 backdrop-blur-xl">
                    <div class="flex-shrink-0">
                        <img class="h-48 w-full object-cover" src="https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1679&q=80" alt="">
                    </div>
                    <div class="flex-1 p-6 flex flex-col justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-blue-600">
                                <a href="#" class="hover:underline">Article</a>
                            </p>
                            <a href="#" class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900">Boost Your Sales: 5 Tips for Small Businesses</p>
                                <p class="mt-3 text-base text-gray-500">Learn how to increase your revenue and grow your customer base with these proven strategies.</p>
                            </a>
                        </div>
                        <div class="mt-6 flex items-center">
                            <div class="flex-shrink-0">
                                <span class="sr-only">Jane Doe</span>
                                <img class="h-10 w-10 rounded-full" src="https://i.pravatar.cc/150?img=68" alt="">
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Jane Doe</p>
                                <p class="text-sm text-gray-500">{{ date('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Add more blog posts here --}}
            </div>
        </div>
    </div>
</x-layouts.app>
