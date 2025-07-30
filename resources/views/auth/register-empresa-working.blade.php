<!DOCTYPE html>
<html>
<head>
    <title>Registro Empresarial - Sistema de Gestão</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
            <h1 class="text-2xl font-bold mb-6 text-center text-blue-600">✅ Registro Empresarial - FUNCIONANDO</h1>
            
            <form method="POST" action="{{ route('register.store.empresa') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="account_type" value="company">
                <input type="hidden" name="plan_type" value="empresa">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nome da Empresa</label>
                    <input type="text" name="company_name" required class="w-full px-3 py-2 border rounded-md">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 border rounded-md">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nome do Admin</label>
                    <input type="text" name="admin_name" required class="w-full px-3 py-2 border rounded-md">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Senha</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 border rounded-md">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Senha</label>
                    <input type="password" name="password_confirmation" required class="w-full px-3 py-2 border rounded-md">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Comprovativo</label>
                    <input type="file" name="payment_proof" required class="w-full px-3 py-2 border rounded-md">
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="terms" required class="mr-2">
                        <span class="text-sm">Concordo com os termos</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                    Registrar
                </button>
            </form>
        </div>
    </div>
</body>
</html>
