<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice System - @yield('title')</title>
    <!-- Add your CSS and JS here -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        const currencyFormatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        });
    </script>
</head>
<body>
    <nav>
        <!-- Navigation menu -->
    </nav>

    <main class="container mx-auto px-4 py-6">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer>
        <!-- Footer content -->
    </footer>
    @stack('scripts')
</body>
</html>
