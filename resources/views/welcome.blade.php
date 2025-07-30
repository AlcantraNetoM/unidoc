<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
                100% { transform: translateY(0px); }
            }
            .animate-fade-in {
                animation: fadeIn 1s ease-out forwards;
            }
            .animate-float {
                animation: float 3s ease-in-out infinite;
            }
            .glass-effect {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(0, 0, 0, 0.1);
            }
            .dark .glass-effect {
                background: rgba(255, 255, 255, 0.95);
                border: 1px solid rgba(0, 0, 0, 0.1);
            }
        </style>
    </head>
    <body class="antialiased bg-gradient-to-br from-gray-200 via-gray-300 to-gray-400 dark:from-gray-200 dark:via-gray-300 dark:to-gray-400 min-h-screen font-sans">
        <div class="min-h-screen flex flex-col">
            @auth
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    <a href="{{ url('/home') }}" class="font-semibold text-gray-900 hover:text-black focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 transition-all duration-300">Home</a>
                </div>
            @else
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    <a href="{{ route('login') }}" class="font-semibold text-gray-900 hover:text-black focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 transition-all duration-300">Log in</a>
                    <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-900 hover:text-black focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 transition-all duration-300">Register</a>
                </div>
            @endauth

            <main class="flex-grow flex items-center justify-center p-6">
                <div class="max-w-5xl w-full glass-effect rounded-2xl shadow-2xl p-12 animate-fade-in bg-white">
                    <div class="text-center mb-12">
                        <h1 class="text-5xl font-bold text-gray-900 mb-6 drop-shadow-lg">
                            @auth
                                Bem-vindo de volta!
                            @else
                                Acesse sua Conta
                            @endauth
                        </h1>
                        <p class="text-xl text-gray-800 max-w-2xl mx-auto font-medium">
                            @auth
                                Pronto para acessar seu dashboard
                            @else
                                Faça login para acessar o sistema
                            @endauth
                        </p>
                    </div>

                    <div class="max-w-md mx-auto">
                        @auth
                            <div class="text-center space-y-6">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Conta Ativa</h3>
                                    <p class="text-gray-600 mb-4">Sua sessão está ativa e pronta para uso.</p>
                                </div>
                                
                                <a href="{{ route('dashboard') }}" class="w-full bg-gradient-to-r from-blue-700 to-purple-700 text-white py-4 px-6 rounded-xl font-bold text-lg hover:from-blue-800 hover:to-purple-800 transition-all duration-300 transform hover:scale-105 block">
                                    Acessar Dashboard
                                </a>
                            </div>
                        @else
                            <div class="space-y-6">
                                <div class="text-center">
                                    <p class="text-gray-700 mb-6">
                                        Se você é novo aqui, explore nossos planos e crie sua conta.
                                    </p>
                                    
                                    <a href="{{ route('landing') }}" class="w-full bg-gradient-to-r from-green-600 to-blue-600 text-white py-4 px-6 rounded-xl font-bold text-lg hover:from-green-700 hover:to-blue-700 transition-all duration-300 transform hover:scale-105 block mb-4">
                                        Explorar Planos e Registrar
                                    </a>
                                    
                                    <div class="relative">
                                        <div class="absolute inset-0 flex items-center">
                                            <div class="w-full border-t border-gray-300"></div>
                                        </div>
                                        <div class="relative flex justify-center text-sm">
                                            <span class="px-2 bg-white text-gray-500">ou</span>
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('login') }}" class="w-full bg-gradient-to-r from-blue-700 to-purple-700 text-white py-4 px-6 rounded-xl font-bold text-lg hover:from-blue-800 hover:to-purple-800 transition-all duration-300 transform hover:scale-105 block mt-4">
                                        Fazer Login
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </main>
        </div>
    </body>
</html>