<x-layout>
    <!-- Slider Section -->
    <section class="relative w-full h-[500px] overflow-hidden">
        <div id="slider" class="flex transition-transform duration-500 ease-in-out">
            <!-- Slide 1 -->
            <div class="w-full flex-shrink-0 relative">
                <img src="{{ asset('storage/slider/s1.jpg') }}" alt="Collaboration"
                    class="w-full h-[500px] object-cover">
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 bg-black/30">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Welcome to <span
                            class="text-orange-600">Smart Task Management</span></h2>
                    <p class="text-lg md:text-xl text-gray-200 mb-6 max-w-2xl">Organize, prioritize, and accomplish your
                        goals with ease. Our app helps you manage your tasks efficiently, collaborate with your team,
                        and boost your productivity.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}"
                            class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-semibold transition">Get
                            Started Free</a>
                        <a href="{{ route('filament.admin.auth.login') }}"
                            class="bg-white border border-orange-600 text-orange-600 hover:bg-orange-50 px-6 py-3 rounded-lg font-semibold transition">Login</a>
                    </div>
                </div>
            </div>
            <!-- Slide 2 -->
            <div class="w-full flex-shrink-0 relative">
                <img src="{{ asset('storage/slider/s2.jpg') }}" alt="Collaboration"
                    class="w-full h-[500px] object-cover">
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 bg-black/30">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Welcome to <span
                            class="text-orange-600">Smart Task Management</span></h2>
                    <p class="text-lg md:text-xl text-gray-200 mb-6 max-w-2xl">Organize, prioritize, and accomplish your
                        goals with ease. Our app helps you manage your tasks efficiently, collaborate with your team,
                        and boost your productivity.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}"
                            class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-semibold transition">Get
                            Started Free</a>
                        <a href="{{ route('filament.admin.auth.login') }}"
                            class="bg-white border border-orange-600 text-orange-600 hover:bg-orange-50 px-6 py-3 rounded-lg font-semibold transition">Login</a>
                    </div>
                </div>
            </div>
            <!-- Slide 3 -->
            <div class="w-full flex-shrink-0 relative">
                <img src="{{ asset('storage/slider/s3.jpg') }}" alt="Collaboration"
                    class="w-full h-[500px] object-cover">
                <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-4 bg-black/30">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">Welcome to <span
                            class="text-orange-600">Smart Task Management</span></h2>
                    <p class="text-lg md:text-xl text-gray-200 mb-6 max-w0-2xl">Organize, prioritize, and accomplish
                        your goals with ease. Our app helps you manage your tasks efficiently, collaborate with your
                        team, and boost your productivity.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}"
                            class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-lg font-semibold transition">Get
                            Started Free</a>
                        <a href="{{ route('filament.admin.auth.login') }}"
                            class="bg-white border border-orange-600 text-orange-600 hover:bg-orange-50 px-6 py-3 rounded-lg font-semibold transition">Login</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Navigation Dots -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex gap-2">
            <button onclick="goToSlide(0)"
                class="w-3 h-3 bg-white rounded-full opacity-50 hover:opacity-100 transition"></button>
            <button onclick="goToSlide(1)"
                class="w-3 h-3 bg-white rounded-full opacity-50 hover:opacity-100 transition"></button>
            <button onclick="goToSlide(2)"
                class="w-3 h-3 bg-white rounded-full opacity-50 hover:opacity-100 transition"></button>
        </div>
        <!-- Navigation Arrows -->
        <button onclick="prevSlide()"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/50 hover:bg-white/80 p-2 rounded-full transition">
            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button onclick="nextSlide()"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/50 hover:bg-white/80 p-2 rounded-full transition">
            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Why Choose Smart Task Management System?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <svg class="w-12 h-12 mx-auto text-orange-600 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900">Task Organization</h3>
                    <p class="text-gray-600 mt-2">Easily categorize and prioritize tasks to stay on top of your work.
                    </p>
                </div>
                <div class="text-center">
                    <svg class="w-12 h-12 mx-auto text-orange-600 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a2 2 0 00-2-2h-3m-2-2H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v7a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900">AI powered Task Priority Prediction</h3>
                    <p class="text-gray-600 mt-2">Leverage AI to predict task priorities and deadlines.</p>
                </div>
                <div class="text-center">
                    <svg class="w-12 h-12 mx-auto text-orange-600 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-900">Boost Productivity</h3>
                    <p class="text-gray-600 mt-2">Track progress and meet deadlines with smart reminders.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">What Our Users Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <p class="text-gray-600 italic">"Smart Task has transformed how our team manages projects. It's
                        intuitive and saves us so much time!"</p>
                    <p class="mt-4 font-semibold text-gray-900">Jane Doe</p>
                    <p class="text-gray-500 text-sm">Project Manager, ABC Corp</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <p class="text-gray-600 italic">"The collaboration features are fantastic. We can now work
                        seamlessly across departments."</p>
                    <p class="mt-4 font-semibold text-gray-900">John Smith</p>
                    <p class="text-gray-500 text-sm">Team Lead, XYZ Inc</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    {{-- <section class="py-16 bg-white">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Frequently Asked Questions</h2>
            <div class="space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">How do I create a new task?</h3>
                    <p class="text-gray-600 mt-2">Simply click the "New Task" button on the dashboard, enter the task
                        details, set a priority, and assign it to a team member if needed.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Can I use Smart Task on mobile?</h3>
                    <p class="text-gray-600 mt-2">Yes, Smart Task is fully responsive and works seamlessly on both iOS
                        and Android devices through our mobile app or browser.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Is my data secure?</h3>
                    <p class="text-gray-600 mt-2">Absolutely. We use industry-standard encryption to protect your data
                        and ensure your privacy.</p>
                </div>
            </div>
        </div>
    </section> --}}
</x-layout>