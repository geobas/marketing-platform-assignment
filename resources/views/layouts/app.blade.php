<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Marketing platform assignment')</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen font-sans">

    <!-- Header -->
    <header class="bg-blue-600 text-white py-4 shadow">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <h1 class="text-xl font-bold">Marketing platform assignment</h1>
            <nav>
                <a href="{{ url('/') }}" class="mr-4 hover:underline">Subscribe</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-200 text-gray-700 py-4 mt-8 text-center">
        &copy; {{ date('Y') }} Marketing platform
    </footer>

</body>
</html>
