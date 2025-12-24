{{-- Footer Component - Standard Tailwind CSS Footer Layout --}}
<footer class="text-sm" aria-labelledby="footer-heading">
    <h2 id="footer-heading" class="sr-only">Footer</h2>
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
        <div class="pb-8 xl:grid xl:grid-cols-5 xl:gap-8">
            <div class="grid grid-cols-2 gap-8 xl:col-span-4 md:grid-cols-4">
                <div>
                    <h3 class=" text-blue-600 tracking-wider">Solutions</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="{{ route('pos') }}" class=" hover:text-blue-600">
                                <ion-icon size="large" name="cash-outline"></ion-icon> Point of Sale
                            </a></li>
                        <li><a href="{{ route('inventory.products') }}" class=" hover:text-blue-600">
                                <ion-icon size="large" name="cube-outline"></ion-icon> Inventory
                            </a></li>
                        <li><a href="{{ route('reports.sales') }}" class=" hover:text-blue-600">
                                <ion-icon size="large" name="bar-chart-outline"></ion-icon> Reporting
                            </a></li>
                        <li><a href="{{ route('customers.management') }}    " class=" hover:text-blue-600">
                                <ion-icon size="large" name="people-outline"></ion-icon> Customers
                            </a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-blue-600  tracking-wider">Support</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="{{ route('pricing') }}" class=" hover:text-blue-600">
                                <ion-icon size="large" name="pricetag-outline"></ion-icon> Pricing
                            </a></li>
                        <li><a href="{{ route('documentation') }}" class=" hover:text-blue-600">
                                <ion-icon size="large" name="document-text-outline"></ion-icon> Documentation
                            </a></li>
                        <li><a href="{{ route('guides') }}" class=" hover:text-blue-600">
                                <ion-icon size="large" name="book-outline"></ion-icon> Guides
                            </a></li>
                        <li><a href="{{ route('api-status') }}" class=" hover:text-blue-600">
                                <ion-icon size="large" name="information-circle-outline"></ion-icon> API Status
                            </a></li>
                    </ul>
                </div>
                <div class="mt-6 md:mt-0">
                    <h3 class="text-blue-600  tracking-wider">Company</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="{{ route('about') }}" class=" hover:text-blue-600">
                                <ion-icon size="large" name="information-circle-outline"></ion-icon> About
                            </a></li>
                        <li><a href="{{ route('blog') }}" class=" hover:text-blue-600">
                                <ion-icon size="large" name="book-outline"></ion-icon> Blog
                            </a></li>
                        <li><a href="{{ route('jobs') }}" class=" hover:text-blue-600">
                                <ion-icon size="large" name="briefcase-outline"></ion-icon> Jobs
                            </a></li>
                        <li><a href="{{ route('press') }}" class=" hover:text-blue-600">
                                <ion-icon size="large" name="megaphone-outline"></ion-icon> Press
                            </a></li>
                    </ul>
                </div>
                <div class="mt-6 md:mt-0">
                    <h3 class="text-blue-600  tracking-wider">Legal</h3>
                    <ul class="mt-4 space-y-4">
                        <li><a href="{{ route('claim') }}" class=" hover:text-blue-600">Claim</a></li>
                        <li><a href="{{ route('privacy') }}" class=" hover:text-blue-600">Privacy</a></li>
                        <li><a href="{{ route('terms') }}" class=" hover:text-blue-600">Terms</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-6 xl:mt-0 xl:col-span-1">
                <h3 class="text-blue-600  tracking-wider">Subscribe to our newsletter</h3>
                <p class="mt-4 ">The latest news, articles, and resources, sent to your inbox weekly.</p>
                <form class="mt-4 sm:flex sm:max-w-md">
                    <label for="email-address" class="sr-only">Email address</label>
                    <input type="email" name="email-address" id="email-address" autocomplete="email" required class="appearance-none min-w-0 w-full border border-transparent rounded-md py-2 px-4  text-gray-900 placeholder-black  focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white focus:border-white focus:placeholder-gray-400" placeholder="Enter your email">
                    <div class="mt-3 rounded-md sm:mt-0 sm:ml-3 sm:flex-shrink-0">
                        <button type="submit" class="w-full bg-blue-600 flex items-center justify-center border border-transparent rounded-md py-2 px-4 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-blue-500">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="mt-4 pt-4 md:flex md:items-center md:justify-between">
            <div class="flex space-x-4 md:order-2">
                <a href="#" class=" hover:"><span class="sr-only">Facebook</span>
                    <ion-icon name="logo-facebook" class="h-6 w-6"></ion-icon>
                </a>
                <a href="#" class=" hover:"><span class="sr-only">Instagram</span>
                    <ion-icon size="large" name="logo-instagram" class="h-6 w-6"></ion-icon>
                </a>
                <a href="#" class=" hover:"><span class="sr-only">Twitter</span>
                    <ion-icon size="large" name="logo-twitter" class="h-6 w-6"></ion-icon>
                </a>
                <a href="#" class=" hover:"><span class="sr-only">GitHub</span>
                    <ion-icon size="large" name="logo-github" class="h-6 w-6"></ion-icon>
                </a>
            </div>
            <p class="mt-4 text-xl text-center  md:mt-0 md:order-1"> {{ date('Y') }} &copy; DemoPOS, Inc. All rights reserved.</p>
        </div>
    </div>
</footer>
