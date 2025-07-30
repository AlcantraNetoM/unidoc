@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-md mx-auto">
        <div class="bg-white rounded-lg shadow-md p-8">
            <h1 class="text-2xl font-bold text-center mb-6">Registro Empresarial</h1>
            
            <form method="POST" action="{{ route('register.store.empresa') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="account_type" value="company">
                <input type="hidden" name="plan_type" value="empresa">

                <div class="mb-4">
                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">Nome da Empresa</label>
                    <input type="text" id="company_name" name="company_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    @error('company_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="admin_name" class="block text-sm font-medium text-gray-700 mb-2">Nome do Administrador</label>
                    <input type="text" id="admin_name" name="admin_name" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    @error('admin_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Senha</label>
                    <input type="password" id="password" name="password" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmar Senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div class="mb-4">
                    <label for="payment_proof" class="block text-sm font-medium text-gray-700 mb-2">Comprovativo de Pagamento</label>
                    <input type="file" id="payment_proof" name="payment_proof" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    @error('payment_proof')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="terms" required class="mr-2">
                        <span class="text-sm text-gray-700">Concordo com os termos</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md">
                    Registrar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
