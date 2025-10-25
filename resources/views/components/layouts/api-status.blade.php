<x-layouts.app title="API Status - DemoPOS">
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/50 backdrop-blur-xl shadow-xl rounded-lg overflow-hidden">
                <div class="p-6 sm:p-10">
                    <div class="text-center">
                        <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl sm:tracking-tight lg:text-6xl">API Status</h1>
                        <p class="mt-6 max-w-2xl mx-auto text-xl text-gray-600">Current status of all DemoPOS services.</p>
                    </div>

                    <div class="mt-12">
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" role="alert">
                            <p class="font-bold">All Systems Operational</p>
                        </div>

                        <div class="mt-8 space-y-6">
                            <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow">
                                <p class="text-lg font-medium text-gray-900">REST API</p>
                                <div class="flex items-center">
                                    <div class="h-4 w-4 rounded-full bg-green-500 mr-2"></div>
                                    <p class="text-green-700 font-semibold">Operational</p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between p-4 bg-white rounded-lg shadow">
                                <p class="text-lg font-medium text-gray-900">Web App</p>
                                <div class="flex items-center">
                                    <div class="h-4 w-4 rounded-full bg-green-500 mr-2"></div>
                                    <p class="text-green-700 font-semibold">Operational</p>
                                </div>
                            </div>
                            <!-- Add more services here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
