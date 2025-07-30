<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v={{ time() }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}?v={{ time() }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}?v={{ time() }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}?v={{ time() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @php
        // Função para escurecer cor
        function darken($color, $amount = 20) {
            $color = ltrim($color, '#');
            $r = hexdec(substr($color, 0, 2));
            $g = hexdec(substr($color, 2, 2));
            $b = hexdec(substr($color, 4, 2));
            
            $r = max(0, $r - ($r * $amount / 100));
            $g = max(0, $g - ($g * $amount / 100));
            $b = max(0, $b - ($b * $amount / 100));
            
            return sprintf("#%02x%02x%02x", $r, $g, $b);
        }
        
        // Função para clarear cor
        function lighten($color, $amount = 20) {
            $color = ltrim($color, '#');
            $r = hexdec(substr($color, 0, 2));
            $g = hexdec(substr($color, 2, 2));
            $b = hexdec(substr($color, 4, 2));
            
            $r = min(255, $r + ((255 - $r) * $amount / 100));
            $g = min(255, $g + ((255 - $g) * $amount / 100));
            $b = min(255, $b + ((255 - $b) * $amount / 100));
            
            return sprintf("#%02x%02x%02x", $r, $g, $b);
        }
        
        // Função para criar cor com transparência
        function addOpacity($color, $opacity = 0.1) {
            $color = ltrim($color, '#');
            $r = hexdec(substr($color, 0, 2));
            $g = hexdec(substr($color, 2, 2));
            $b = hexdec(substr($color, 4, 2));
            
            return "rgba($r, $g, $b, $opacity)";
        }

        // Cores padrão para páginas de autenticação
        $primaryColor = '#2563eb';
        $primaryDark = darken($primaryColor);
        $primaryLight = lighten($primaryColor);
        $primaryOpacity = addOpacity($primaryColor);
        $secondaryColor = '#64748b';
        $secondaryDark = darken($secondaryColor);
        $secondaryLight = lighten($secondaryColor);
        $secondaryOpacity = addOpacity($secondaryColor);
    @endphp

    <style>
        :root {
            --color-primary: {{ $primaryColor }};
            --color-primary-dark: {{ $primaryDark }};
            --color-primary-light: {{ $primaryLight }};
            --color-primary-opacity: {{ $primaryOpacity }};
            --color-secondary: {{ $secondaryColor }};
            --color-secondary-dark: {{ $secondaryDark }};
            --color-secondary-light: {{ $secondaryLight }};
            --color-secondary-opacity: {{ $secondaryOpacity }};
            
            /* Cores de estado */
            --color-success: #10b981;
            --color-success-dark: #059669;
            --color-success-light: #d1fae5;
            --color-warning: #f59e0b;
            --color-warning-dark: #d97706;
            --color-warning-light: #fef3c7;
            --color-danger: #ef4444;
            --color-danger-dark: #dc2626;
            --color-danger-light: #fee2e2;
            --color-info: #3b82f6;
            --color-info-dark: #2563eb;
            --color-info-light: #dbeafe;
            
            /* Tons de cinza */
            --color-white: #ffffff;
            --color-gray-50: #f9fafb;
            --color-gray-100: #f3f4f6;
            --color-gray-200: #e5e7eb;
            --color-gray-300: #d1d5db;
            --color-gray-400: #9ca3af;
            --color-gray-500: #6b7280;
            --color-gray-600: #4b5563;
            --color-gray-700: #374151;
            --color-gray-800: #1f2937;
            --color-gray-900: #111827;
            
            /* Bordas */
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;
            
            /* Sombras */
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: var(--color-gray-50);
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
            padding: 0 24px;
        }

        .auth-card {
            background: var(--color-white);
            padding: 32px;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--color-gray-200);
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .auth-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-logo {
            width: 64px;
            height: 64px;
            background: var(--color-primary);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: var(--color-white);
        }

        .auth-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--color-gray-900);
            margin: 0 0 8px;
        }

        .auth-subtitle {
            color: var(--color-gray-600);
            font-size: 14px;
            margin: 0;
        }

        .form-group {
            margin-bottom: 20px;
            transition: transform 0.2s ease;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--color-gray-900);
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--color-gray-300);
            border-radius: var(--radius-md);
            font-size: 16px;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: var(--color-white);
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            transform: translateY(-1px);
        }

        .form-input.error {
            border-color: var(--color-danger);
            animation: shake 0.5s ease-in-out;
        }

        .password-strength {
            margin-top: 8px;
            height: 4px;
            background: var(--color-gray-200);
            border-radius: var(--radius-sm);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: var(--radius-sm);
        }

        .strength-weak { 
            background: var(--color-danger); 
            width: 25%; 
        }

        .strength-fair { 
            background: var(--color-warning); 
            width: 50%; 
        }

        .strength-good { 
            background: var(--color-info); 
            width: 75%; 
        }

        .strength-strong { 
            background: var(--color-success); 
            width: 100%; 
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
            border: none;
            border-radius: var(--radius-md);
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
        }

        .btn-primary {
            background: var(--color-primary);
            color: var(--color-white);
        }

        .btn-primary:hover {
            background: var(--color-primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }

        .auth-links {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid var(--color-gray-200);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .auth-links a {
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
        }

        .auth-links a:hover {
            color: var(--color-primary-dark);
        }

        .error-message {
            color: var(--color-danger);
            font-size: 14px;
            margin-top: 6px;
        }

        .remember-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox {
            width: 16px;
            height: 16px;
            accent-color: var(--color-primary);
        }

        .checkbox-label {
            font-size: 14px;
            color: var(--color-gray-700);
            margin: 0;
        }

        .status-message {
            background: var(--color-success);
            color: var(--color-white);
            padding: 12px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* Radio buttons styling for register */
        .radio-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .radio-option {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border: 1px solid var(--color-gray-300);
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: all 0.2s;
        }

        .radio-option:hover {
            border-color: var(--color-primary);
            background: var(--color-gray-50);
        }

        .radio-option input {
            margin-right: 12px;
            accent-color: var(--color-primary);
        }

        .radio-option.checked {
            border-color: var(--color-primary);
            background: var(--color-primary-light);
        }

        .company-fields {
            background: var(--color-gray-50);
            padding: 20px;
            border-radius: var(--radius-md);
            margin-top: 16px;
            border: 1px solid var(--color-gray-200);
        }

        .company-fields h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--color-gray-900);
            margin: 0 0 16px;
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--color-gray-300);
            border-radius: var(--radius-md);
            font-size: 16px;
            background: var(--color-white);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid var(--color-gray-300);
            border-radius: var(--radius-md);
            font-size: 16px;
            background: var(--color-white);
            resize: vertical;
            min-height: 80px;
            font-family: inherit;
            transition: all 0.2s ease;
        }

        .form-textarea:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .hidden {
            display: none !important;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .auth-container {
                max-width: 100%;
                padding: 0 16px;
            }
            
            .auth-card {
                padding: 24px;
            }
            
            .radio-group {
                gap: 8px;
            }
            
            .company-fields {
                padding: 16px;
            }
        }

        /* Pending Page Styles */
        .pending-content {
            text-align: center;
            padding: 20px 0;
        }

        .pending-icon {
            width: 80px;
            height: 80px;
            background: var(--color-primary-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            border: 3px solid var(--color-primary);
            animation: pulse 2s infinite;
        }

        .pending-icon svg {
            width: 40px;
            height: 40px;
            color: var(--color-primary);
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }
        }

        .pending-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--color-gray-900);
            margin: 0 0 12px;
        }

        .pending-message {
            font-size: 16px;
            color: var(--color-gray-600);
            margin: 0 0 32px;
            line-height: 1.6;
        }

        .pending-steps {
            background: var(--color-gray-50);
            border-radius: var(--radius-lg);
            padding: 24px;
            margin: 32px 0;
            border: 1px solid var(--color-gray-200);
        }

        .pending-steps h3 {
            font-size: 18px;
            font-weight: 600;
            color: var(--color-gray-900);
            margin: 0 0 20px;
            text-align: center;
        }

        .step {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
            padding: 12px;
            background: var(--color-white);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-gray-200);
        }

        .step:last-child {
            margin-bottom: 0;
        }

        .step-number {
            width: 32px;
            height: 32px;
            background: var(--color-primary);
            color: var(--color-white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            margin-right: 16px;
            flex-shrink: 0;
        }

        .step.completed .step-number {
            background: var(--color-success);
        }

        .step.active .step-number {
            background: var(--color-primary);
            animation: pulse 2s infinite;
        }

        .step-content {
            flex: 1;
        }

        .step-title {
            font-weight: 600;
            color: var(--color-gray-900);
            margin: 0 0 4px;
            font-size: 14px;
        }

        .step-description {
            color: var(--color-gray-600);
            margin: 0;
            font-size: 13px;
            line-height: 1.4;
        }

        .step.completed .step-title {
            color: var(--color-success);
        }

        .step.active .step-title {
            color: var(--color-primary);
        }

        .contact-info {
            background: var(--color-info-light);
            border: 1px solid var(--color-info);
            border-radius: var(--radius-md);
            padding: 16px;
            margin-top: 24px;
        }

        .contact-info p {
            margin: 0;
            color: var(--color-info-dark);
            font-size: 14px;
            text-align: center;
        }

        .contact-info strong {
            color: var(--color-info-dark);
        }

        /* Success message styling */
        .success-message {
            background: var(--color-success-light);
            border: 1px solid var(--color-success);
            color: var(--color-success-dark);
            padding: 12px 16px;
            border-radius: var(--radius-md);
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .success-message svg {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            flex-shrink: 0;
        }

        /* Additional responsive styles for pending page */
        @media (max-width: 640px) {
            .pending-title {
                font-size: 24px;
            }

            .pending-icon {
                width: 60px;
                height: 60px;
            }

            .pending-icon svg {
                width: 30px;
                height: 30px;
            }

            .step {
                padding: 8px;
            }

            .step-number {
                width: 28px;
                height: 28px;
                font-size: 12px;
                margin-right: 12px;
            }
        }
    </style>
</head>
<body>
    @yield('content')
    
    <script>
        // Add form loading states
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitButton = form.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.style.opacity = '0.7';
                        
                        // Add loading text
                        const originalText = submitButton.innerHTML;
                        submitButton.innerHTML = `
                            <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a7.646 7.646 0 100 15.292 7.646 7.646 0 000-15.292zm0 2.25a5.396 5.396 0 110 10.792 5.396 5.396 0 010-10.792z"></path>
                            </svg>
                            Processando...
                        `;
                        
                        // Restore button if form submission fails
                        setTimeout(() => {
                            if (!form.checkValidity()) {
                                submitButton.disabled = false;
                                submitButton.style.opacity = '1';
                                submitButton.innerHTML = originalText;
                            }
                        }, 100);
                    }
                });
            });
        });
    </script>
    
    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
</body>
</html>
