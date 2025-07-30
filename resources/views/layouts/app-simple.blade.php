<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'File Management') - {{ config('app.name', 'File Management') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}?v={{ time() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}?v={{ time() }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}?v={{ time() }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom loading animation */
        .loading {
            opacity: 0.6;
            pointer-events: none;
        }
        .file-input {
            background-image: url("data:image/svg+xml,%3csvg width='100' height='100' xmlns='http://www.w3.org/2000/svg'%3e%3cdefs%3e%3cpattern id='a' patternUnits='userSpaceOnUse' width='20' height='20' patternTransform='scale(0.5) rotate(0)'%3e%3crect x='0' y='0' width='100%25' height='100%25' fill='hsla(0, 0%25, 100%25, 1)'/%3e%3cpath d='M 10,-2.55e-7 V 20 Z M -1.1677362e-8,10 H 20 Z' stroke-width='1' stroke='hsla(259, 0%25, 90%25, 1)' fill='none'/%3e%3c/pattern%3e%3c/defs%3e%3crect width='100%25' height='100%25' fill='url(%23a)'/%3e%3c/svg%3e");
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Flash Messages -->
    @if (session('success'))
        <div id="flash-message" class="fixed top-0 left-0 right-0 z-50 bg-green-500 text-white p-4 text-center">
            <div class="max-w-md mx-auto">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div id="flash-message" class="fixed top-0 left-0 right-0 z-50 bg-red-500 text-white p-4 text-center">
            <div class="max-w-md mx-auto">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div id="flash-message" class="fixed top-0 left-0 right-0 z-50 bg-red-500 text-white p-4 text-center">
            <div class="max-w-md mx-auto">
                <ul class="text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @yield('content')

    <script>
        // Auto-hide flash messages
        const flashMessage = document.getElementById('flash-message');
        if (flashMessage) {
            setTimeout(() => {
                flashMessage.style.opacity = '0';
                setTimeout(() => {
                    flashMessage.remove();
                }, 300);
            }, 5000);
        }

        // Form loading state
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('loading');
                    submitBtn.innerHTML = 'Processando...';
                }
            });
        });

        // File input preview
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const label = this.parentNode.querySelector('label');
                    if (label) {
                        label.innerHTML = `${label.innerHTML.split('*')[0]}* - ${file.name}`;
                    }
                }
            });
        });
    </script>
</body>
</html>
