<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registro Realizado - UNIDOC</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes bounceIn {
            from { opacity: 0; transform: scale(0.3); }
            50% { opacity: 1; transform: scale(1.1); }
            to { opacity: 1; transform: scale(1); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-bounce-in {
            animation: bounceIn 0.8s ease-out;
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        .success-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
    </style>
</head>
<body class="success-bg min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-6">
        <div class="max-w-2xl w-full">
            <!-- Success Icon -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full shadow-lg animate-bounce-in">
                    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <!-- Success Message -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 animate-fade-in-up">
                <!-- Logo UNIDOC -->
                <div class="text-center mb-6">
                    <img src="{{ asset('logo.png') }}" alt="UNIDOC Logo" class="w-16 h-16 object-contain mx-auto mb-4">
                    <h2 class="text-2xl font-bold text-blue-600">UNIDOC</h2>
                    <p class="text-sm text-gray-600">Único local para documentos</p>
                </div>
                
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4">
                        Registro Realizado com Sucesso!
                    </h1>
                    <p class="text-xl text-gray-600">
                        {{ session('message', 'Sua conta foi criada e está aguardando aprovação.') }}
                    </p>
                </div>

                <!-- Plan Information -->
                @if(session('account_type'))
                <div class="bg-green-50 rounded-xl p-6 mb-8">
                    <div class="flex items-center mb-4">
                        @if(session('account_type') === 'empresa')
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Plano Empresarial Ativado</h3>
                        @else
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">Plano Pessoal Ativado</h3>
                        @endif
                    </div>
                    <p class="text-gray-700">
                        Seu {{ session('account_type') === 'empresa' ? 'plano empresarial' : 'plano pessoal' }} 
                        foi configurado com sucesso e está aguardando validação.
                    </p>
                </div>
                @endif

                <!-- Next Steps -->
                <div class="space-y-6 mb-8">
                    <h3 class="text-2xl font-semibold text-gray-900 text-center">Próximos Passos</h3>
                    
                    <div class="grid gap-4">
                        <div class="flex items-start space-x-4 p-4 bg-blue-50 rounded-lg">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">1</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Aprovação da Conta</h4>
                                <p class="text-sm text-gray-600">
                                    Nossa equipe irá analisar e aprovar sua conta. 
                                    Este processo pode levar até 24 horas.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4 p-4 bg-green-50 rounded-lg">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center font-bold text-sm">2</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">🎉 Período de Teste Gratuito - 15 Dias</h4>
                                <p class="text-sm text-gray-600">
                                    Após a aprovação, você terá <strong>15 dias gratuitos</strong> para testar 
                                    todas as funcionalidades do sistema sem qualquer custo.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4 p-4 bg-yellow-50 rounded-lg">
                            <div class="flex-shrink-0 w-8 h-8 bg-yellow-500 text-white rounded-full flex items-center justify-center font-bold text-sm">3</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Pagamento (Após 15 dias)</h4>
                                <p class="text-sm text-gray-600">
                                    Apenas no final do período de teste será solicitado o pagamento 
                                    para continuar usando o sistema.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trial Benefits -->
                <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6 mb-8">
                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        O que você terá durante o período de teste:
                    </h4>
                    <div class="grid sm:grid-cols-2 gap-3 text-sm text-gray-700">
                        <p class="flex items-center">✅ Acesso completo ao sistema</p>
                        <p class="flex items-center">✅ Upload e gestão de documentos</p>
                        <p class="flex items-center">✅ Criação de categorias</p>
                        <p class="flex items-center">✅ Gestão de utilizadores</p>
                        <p class="flex items-center">✅ Relatórios e estatísticas</p>
                        <p class="flex items-center">✅ Suporte técnico incluído</p>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-gray-50 rounded-xl p-6 mb-8">
                    <h4 class="font-semibold text-gray-900 mb-3">Precisa de Ajuda?</h4>
                    <div class="space-y-2 text-sm text-gray-700">
                        <p class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <strong>Email:</strong> vambert_quaresma@gmail.com
                        </p>
                        <p class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <strong>Telefone:</strong> +244 939 424 288
                        </p>
                        <p class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <strong>Horário:</strong> Segunda a Sexta, 9h às 18h
                        </p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Ir para Login
                    </a>
                    
                    <a href="{{ route('landing') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Voltar ao Início
                    </a>
                </div>

                <!-- Additional Information -->
                <div class="mt-8 p-4 bg-blue-100 rounded-lg">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-blue-500 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <strong>Importante:</strong> Após a aprovação da sua conta, você terá 15 dias totalmente gratuitos para explorar 
                            e usar o sistema. O pagamento só será solicitado no final deste período de teste.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
