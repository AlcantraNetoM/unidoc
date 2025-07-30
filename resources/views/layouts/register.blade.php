<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Registro') - {{ config('app.name', 'File Management') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}?v={{ time() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}?v={{ time() }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}?v={{ time() }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Animações customizadas */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        .file-input:focus {
            outline: 2px solid #3B82F6;
            outline-offset: 2px;
        }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <!-- Flash Messages -->
    @if (session('success'))
        <div id="flash-message" class="fixed top-0 left-0 right-0 z-50 bg-green-500 text-white p-4 text-center fade-in">
            <div class="max-w-md mx-auto">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div id="flash-message" class="fixed top-0 left-0 right-0 z-50 bg-red-500 text-white p-4 text-center fade-in">
            <div class="max-w-md mx-auto">
                {{ session('error') }}
            </div>
        </div>
    @endif

    @if (isset($errors) && $errors->any())
        <div id="flash-message" class="fixed top-0 left-0 right-0 z-50 bg-red-500 text-white p-4 text-center fade-in">
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
                    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
                    const originalText = submitBtn.textContent;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processando...
                    `;
                }
            });
        });

        // File input feedback
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const file = this.files[0];
                const feedback = this.parentNode.querySelector('.file-feedback');
                if (file && feedback) {
                    feedback.textContent = `Arquivo selecionado: ${file.name}`;
                    feedback.classList.remove('text-gray-500');
                    feedback.classList.add('text-green-600');
                }
            });
        });
    </script>
</body>
</html>
