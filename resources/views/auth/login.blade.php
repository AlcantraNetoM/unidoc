@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center">
                    <!-- Logo UNIDOC -->
                    <div class="w-12 h-12 mr-3 flex items-center justify-center">
                        <img src="{{ asset('logo.png') }}" alt="UNIDOC Logo" class="w-12 h-12 object-contain">
                    </div>
                    <h1 class="text-2xl font-bold text-blue-600">UNIDOC</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('landing') }}" class="text-gray-600 hover:text-gray-900 px-4 py-2 rounded-lg transition duration-200">Voltar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Form -->
    <div class="flex items-center justify-center py-20">
        <div class="max-w-md w-full mx-4">
            <div class="bg-white rounded-xl shadow-lg p-8 border">
                <!-- Header -->
                <div class="text-center mb-8">
                    <!-- Logo UNIDOC -->
                    <div class="mx-auto flex items-center justify-center mb-4">
                        <img src="{{ asset('logo.png') }}" alt="UNIDOC Logo" class="w-20 h-20 object-contain">
                    </div>
                    <h1 class="text-3xl font-bold text-blue-600 mb-2">Bem-vindo ao UNIDOC</h1>
                    <p class="text-gray-600">Entre na sua conta para continuar</p>
                </div>

                <!-- Session Status -->
                @if(session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" required autofocus autocomplete="username"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('email') border-red-500 @enderror"
                               value="{{ old('email') }}" placeholder="Digite seu email">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Senha</label>
                        <input type="password" name="password" id="password" required autocomplete="current-password"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300 @error('password') border-red-500 @enderror"
                               placeholder="Digite sua senha">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700">Lembrar de mim</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-semibold transition-colors duration-200 flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Entrar
                    </button>
                </form>

                <!-- Additional Links -->
                <div class="mt-6 pt-6 border-t border-gray-200 text-center space-y-3">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="block text-blue-600 hover:text-blue-700 font-medium">Esqueceu a senha?</a>
                    @endif
                    <a href="{{ route('landing') }}" class="block text-blue-600 hover:text-blue-700 font-medium">Não tem uma conta? Registre-se aqui</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
