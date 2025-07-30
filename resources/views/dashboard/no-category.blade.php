<x-app-layout>
    <div class="fade-in">
        <div class="text-center" style="padding: 80px 20px;">
            <!-- Ícone de aviso -->
            <svg width="80" height="80" fill="var(--color-warning)" viewBox="0 0 16 16" style="margin-bottom: 24px;">
                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
            </svg>
            
            <h1 class="card-title mb-3">Categoria Não Atribuída</h1>
            
            <div class="card" style="max-width: 600px; margin: 0 auto; text-align: left;">
                <div class="alert alert-warning">
                    <strong>Atenção!</strong> Você ainda não foi atribuído a uma categoria específica.
                </div>
                
                <p class="text-muted mb-4">
                    Como técnico, você precisa ter uma categoria específica atribuída pelo administrador 
                    para poder enviar arquivos ao sistema.
                </p>

                <div class="mb-4">
                    <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--color-gray-900);">
                        Informações da sua conta:
                    </h3>
                    <ul style="list-style: none; padding: 0;">
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Nome:</span>
                            <span class="font-weight-600">{{ $user->name }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Email:</span>
                            <span class="font-weight-600">{{ $user->email }}</span>
                        </li>
                        <li class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Papel:</span>
                            <span class="font-weight-600">Técnico Normal</span>
                        </li>
                        <li class="d-flex justify-content-between py-2">
                            <span class="text-muted">Categoria:</span>
                            <span class="text-warning font-weight-600">Não Atribuída</span>
                        </li>
                    </ul>
                </div>

                <div class="mb-4">
                    <h3 style="font-size: 18px; margin-bottom: 12px; color: var(--color-gray-900);">
                        O que fazer agora:
                    </h3>
                    <ol style="padding-left: 20px; color: var(--color-gray-700);">
                        <li class="mb-2">Entre em contato com o administrador da empresa</li>
                        <li class="mb-2">Solicite que uma categoria seja atribuída à sua conta</li>
                        <li class="mb-2">Após a atribuição, você poderá enviar arquivos normalmente</li>
                    </ol>
                </div>

                <div style="background-color: var(--color-gray-50); padding: 16px; border-radius: var(--radius-md); border-left: 4px solid var(--color-primary);">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <svg width="16" height="16" fill="var(--color-primary)" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                        </svg>
                        <strong style="color: var(--color-primary);">Informação</strong>
                    </div>
                    <p style="margin: 0; font-size: 14px; color: var(--color-gray-600);">
                        Enquanto aguarda a atribuição da categoria, você pode explorar o sistema e 
                        editar suas informações de perfil.
                    </p>
                </div>
            </div>

            <!-- Ações disponíveis -->
            <div class="d-flex gap-3 justify-content-center mt-4">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                    </svg>
                    Editar Perfil
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="btn btn-secondary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                        </svg>
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        .border-bottom {
            border-bottom: 1px solid var(--color-gray-200);
        }
        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }
        .font-weight-600 {
            font-weight: 600;
        }
        .inline {
            display: inline;
        }
        .justify-content-center {
            justify-content: center;
        }
    </style>
</x-app-layout> 