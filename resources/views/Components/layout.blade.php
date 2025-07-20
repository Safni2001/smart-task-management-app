<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Task Management App</title>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-50 min-h-screen font-sans flex flex-col">
    <!-- Header -->
    <header class="w-full bg-white shadow-sm py-4 px-6 flex items-center sticky top-0 z-50">
        <div class="flex items-center gap-2">
            <svg class="w-8 h-8 text-orange-600" fill="none" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                <rect x="4" y="4" width="24" height="24" rx="6" fill="currentColor" />
                <path d="M10 16h12M16 10v12" stroke="#fff" stroke-width="2" stroke-linecap="round" />
            </svg>
            <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800 hover:text-orange-600 transition">Smart
                Task</a>
        </div>
        <div class="flex flex-1 items-center">
            <nav style="margin-left:50px;" class="flex gap-10 items-center">
                <a href="/" class="text-gray-700 hover:text-orange-600 font-medium transition">Home</a>
                <a href="/" class="text-gray-700 hover:text-orange-600 font-medium transition">About</a>
                <a href="/" class="text-gray-700 hover:text-orange-600 font-medium transition">Services</a>
                <a href="/" class="text-gray-700 hover:text-orange-600 font-medium transition">Contact</a>
            </nav>
            <div class="flex gap-2 items-center ml-auto">
                <a href="{{ route('filament.admin.auth.login') }}"
                    class="text-white bg-orange-600 hover:bg-orange-700 px-4 py-2 rounded transition font-medium">Login</a>
                <a href="{{ route('register') }}"
                    class="text-white bg-orange-600 hover:bg-orange-700 px-4 py-2 rounded transition font-medium">Register</a>
            </div>
        </div>
    </header>

    {{ $slot }}

    <!-- JavaScript for Slider -->
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('#slider > div');
        const dots = document.querySelectorAll('.w-3');

        function showSlide(index) {
            document.getElementById('slider').style.transform = `translateX(-${index * 100}%)`;
            dots.forEach((dot, i) => {
                dot.classList.toggle('opacity-100', i === index);
                dot.classList.toggle('opacity-50', i !== index);
            });
            currentSlide = index;
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }

        function goToSlide(index) {
            showSlide(index);
        }

        // Auto-slide every 5 seconds
        setInterval(nextSlide, 5000);

        // Initialize first slide
        showSlide(0);
    </script>

    <!-- Footer -->
    <footer class="w-full py-6 text-center text-gray-400 text-sm mt-8">
        © {{ date('Y') }} Smart Task Management App. All rights reserved.
    </footer>
</body>

</html>