<x-layouts.app title="Press - DemoPOS">
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/50 backdrop-blur-xl shadow-xl rounded-lg overflow-hidden">
                <div class="p-6 sm:p-10">
                    <div class="text-center">
                        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">Press & Media</h1>
                        <p class="mt-6 max-w-2xl mx-auto text-xl text-gray-600">Welcome to the DemoPOS press room. Here you'll find our latest news, press releases, and media resources.</p>
                    </div>

                    <div class="mt-16">
                        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Latest News</h2>
                        <div class="mt-6 space-y-8">
                            <div class="border-b border-gray-200 pb-8">
                                <p class="text-sm text-gray-500">{{ date('F d, Y') }}</p>
                                <a href="#" class="block mt-2">
                                    <p class="text-xl font-semibold text-gray-900">DemoPOS Launches New Inventory Management Features</p>
                                    <p class="mt-3 text-base text-gray-500">We're excited to announce a major update to our inventory system, designed to give merchants more control and insight than ever before.</p>
                                </a>
                            </div>
                            <!-- More news items here -->
                        </div>
                    </div>

                    <div class="mt-16">
                        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Media Contact</h2>
                        <p class="mt-4 text-lg text-gray-600">For all media inquiries, please contact our communications team:</p>
                        <a href="mailto:press@demopos.com" class="text-lg text-blue-600 hover:text-blue-800">press@demopos.com</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
