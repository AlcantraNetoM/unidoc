<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Sistema de Gestão de Arquivos') }}</title>

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
            function lighten($color, $amount = 90) {
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

            // Definir cores baseadas na empresa do usuário
            if (auth()->check() && auth()->user()->empresa) {
                $primaryColor = auth()->user()->empresa->primary_color ?? '#2563eb';
                $secondaryColor = auth()->user()->empresa->secondary_color ?? '#64748b';
                
                $primaryDark = darken($primaryColor);
                $primaryLight = lighten($primaryColor);
                $primaryOpacity = addOpacity($primaryColor);
                
                $secondaryDark = darken($secondaryColor);
                $secondaryLight = lighten($secondaryColor);
                $secondaryOpacity = addOpacity($secondaryColor);
            } else {
                $primaryColor = '#2563eb';
                $primaryDark = '#1d4ed8';
                $primaryLight = '#dbeafe';
                $primaryOpacity = 'rgba(37, 99, 235, 0.1)';
                $secondaryColor = '#64748b';
                $secondaryDark = '#475569';
                $secondaryLight = '#f1f5f9';
                $secondaryOpacity = 'rgba(100, 116, 139, 0.1)';
            }
        @endphp

        <!-- Custom Colors por Empresa -->
        <!-- DEBUG: Primary={{ $primaryColor }}, Secondary={{ $secondaryColor }} -->
        <style>
            :root {
                --color-primary: {{ $primaryColor }} !important;
                --color-primary-dark: {{ $primaryDark }} !important;
                --color-primary-light: {{ $primaryLight }} !important;
                --color-primary-opacity: {{ $primaryOpacity }} !important;
                --color-secondary: {{ $secondaryColor }} !important;
                --color-secondary-dark: {{ $secondaryDark }} !important;
                --color-secondary-light: {{ $secondaryLight }} !important;
                --color-secondary-opacity: {{ $secondaryOpacity }} !important;
                
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
            }
            
            /* Main content styling */
            .main-content {
                opacity: 1 !important;
                visibility: visible !important;
                display: block !important;
                min-height: calc(100vh - 80px);
                background-color: #f8f9fa;
            }
            
            /* Fallback para animações */
            .fade-in {
                opacity: 1;
                transform: translateY(0);
            }
        </style>
    </head>
    <body>
        <div class="app-container">
            <!-- Header -->
            @auth
            <header class="header">
                <div class="header-container">
                    <a href="{{ route('dashboard') }}" class="header-brand">
                        @if(auth()->user()->empresa && auth()->user()->empresa->logo_path)
                            <img src="{{ auth()->user()->empresa->logo_url }}" alt="{{ auth()->user()->empresa->nome }}" class="company-logo" onerror="this.style.display='none'">
                        @endif
                        <span>{{ auth()->user()->empresa?->nome ?? 'Sistema de Arquivos' }}</span>
                    </a>

                    <nav class="header-nav">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            Dashboard
                        </a>
                        
                        {{-- Files are available for both personal users and company users, but NOT for super admins --}}
                        @unless(auth()->user()->isSuperAdmin())
                            <a href="{{ route('files.index') }}" class="nav-link {{ request()->routeIs('files.*') ? 'active' : '' }}">
                                Arquivos
                            </a>
                        @endunless

                        {{-- Company-specific navigation --}}
                        @if(auth()->user()->empresa)
                            @if(in_array(auth()->user()->role, ['admin', 'company_admin']))
                                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                                    Categorias
                                </a>
                                
                                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                                    Usuários
                                </a>
                            @endif
                        @endif

                        <!-- Dropdown do usuário -->
                        <div class="relative">
                            <button type="button" class="nav-link" onclick="toggleDropdown()">
                                {{ auth()->user()->name }}
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div id="userDropdown" class="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 hidden z-50">
                                <div class="py-2">
                                    <div class="px-4 py-2 text-sm text-gray-600 border-b border-gray-200">
                                        {{ auth()->user()->email }}
                                        <div class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</div>
                                    </div>
                                    
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Perfil
                                    </a>

                                    @if(auth()->user()->empresa && in_array(auth()->user()->role, ['admin', 'company_admin']))
                                        <a href="{{ route('empresa.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Empresa
                                        </a>
                                    @endif

                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Sair
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
            </header>
            @endauth

            <!-- Main Content -->
            <main class="main-content{{ auth()->check() ? '' : ' no-header' }}">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="alert alert-success fade-in">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger fade-in">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="alert alert-warning fade-in">
                        {{ session('warning') }}
                    </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger fade-in">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Page Content -->
                @yield('content')
                
                <!-- Slot para componentes Blade -->
                {{ $slot ?? '' }}
            </main>
        </div>

        <!-- JavaScript para dropdown -->
        <script>
            function toggleDropdown() {
                const dropdown = document.getElementById('userDropdown');
                dropdown.classList.toggle('hidden');
            }

            // Fechar dropdown ao clicar fora
            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById('userDropdown');
                const button = event.target.closest('button');
                
                if (!button || button.getAttribute('onclick') !== 'toggleDropdown()') {
                    dropdown.classList.add('hidden');
                }
            });

            // Sistema de upload drag & drop
            function setupFileUpload() {
                const fileUpload = document.querySelector('.file-upload');
                if (!fileUpload) return;

                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    fileUpload.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    fileUpload.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    fileUpload.addEventListener(eventName, unhighlight, false);
                });

                function highlight(e) {
                    fileUpload.classList.add('dragover');
                }

                function unhighlight(e) {
                    fileUpload.classList.remove('dragover');
                }

                fileUpload.addEventListener('drop', handleDrop, false);

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    const fileInput = document.querySelector('input[type="file"]');
                    if (fileInput && files.length > 0) {
                        fileInput.files = files;
                        updateFileLabel(files[0].name);
                    }
                }
            }

            function updateFileLabel(fileName) {
                const label = document.querySelector('.file-upload p');
                if (label) {
                    label.textContent = `Arquivo selecionado: ${fileName}`;
                }
            }

            // Sistema de subcategorias dinâmicas
            function loadSubcategories(categoryId, subcategorySelect) {
                if (!categoryId) {
                    subcategorySelect.innerHTML = '<option value="">Selecione uma subcategoria</option>';
                    subcategorySelect.disabled = true;
                    return;
                }

                fetch(`/api/categories/${categoryId}/subcategories`)
                    .then(response => response.json())
                    .then(data => {
                        subcategorySelect.innerHTML = '<option value="">Selecione uma subcategoria</option>';
                        data.forEach(subcategory => {
                            const option = document.createElement('option');
                            option.value = subcategory.id;
                            option.textContent = subcategory.nome;
                            subcategorySelect.appendChild(option);
                        });
                        subcategorySelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Erro ao carregar subcategorias:', error);
                        subcategorySelect.innerHTML = '<option value="">Erro ao carregar</option>';
                    });
            }

            // Inicializar quando o DOM estiver pronto
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM carregado - iniciando scripts');
                
                setupFileUpload();

                // Setup para select de categorias
                const categorySelect = document.querySelector('#category_id');
                const subcategorySelect = document.querySelector('#subcategory_id');
                
                if (categorySelect && subcategorySelect) {
                    categorySelect.addEventListener('change', function() {
                        loadSubcategories(this.value, subcategorySelect);
                    });
                }
                
                // Garantir que elementos com fade-in sejam visíveis
                const fadeElements = document.querySelectorAll('.fade-in');
                fadeElements.forEach(element => {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                });
                
                console.log('Scripts inicializados com sucesso');
            });
        </script>

        <!-- Styles adicionais para componentes que precisam de JavaScript -->
        <style>
            .relative { position: relative; }
            .absolute { position: absolute; }
            .hidden { display: none; }
            .z-50 { z-index: 50; }
            .w-4 { width: 1rem; }
            .h-4 { height: 1rem; }
            .w-48 { width: 12rem; }
            .ml-1 { margin-left: 0.25rem; }
            .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
            .px-4 { padding-left: 1rem; padding-right: 1rem; }
            .text-xs { font-size: 0.75rem; }
            .text-sm { font-size: 0.875rem; }
            .right-0 { right: 0; }
            .top-full { top: 100%; }
            .mt-2 { margin-top: 0.5rem; }
            .rounded-lg { border-radius: 0.5rem; }
            .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
            .border { border-width: 1px; }
            .border-b { border-bottom-width: 1px; }
            .border-gray-200 { border-color: #e5e7eb; }
            .text-gray-500 { color: #6b7280; }
            .text-gray-600 { color: #4b5563; }
            .text-gray-700 { color: #374151; }
            .hover\:bg-gray-100:hover { background-color: #f3f4f6; }
            
            .no-header {
                padding-top: 0;
            }
        </style>
    </body>
</html>
