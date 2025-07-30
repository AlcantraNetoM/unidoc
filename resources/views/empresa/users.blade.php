<x-app-layout>
    <div class="fade-in">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="card-title mb-1">Gestão de Usuários</h1>
                <p class="text-muted">Gerencie usuários da {{ $empresa->nome }}</p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newUserModal">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5            // Handle edit user button clicks
            document.addEventListener('click', function(e) {
                if (e.target.closest('.edit-user-btn')) {
                    const userId = e.target.closest('.edit-user-btn').dataset.userId;
                    
                    // Fetch user data via AJAX
                    fetch(`/empresa/users/${userId}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                alert('Erro ao carregar dados do usuário');
                                return;
                            }
                            
                            // Fill the form with user data
                            document.getElementById('edit_name').value = data.name;
                            document.getElementById('edit_email').value = data.email;
                            document.getElementById('edit_role').value = data.role;
                            document.getElementById('edit_category_id').value = data.category_id || '';
                            
                            // Clear password fields
                            document.getElementById('edit_password').value = '';
                            document.getElementById('edit_password_confirmation').value = '';
                            
                            // Set form action to correct route
                            document.getElementById('editUserForm').action = `/users/${userId}`;
                            
                            // Show the modal
                            document.getElementById('editUserModal').classList.add('show');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Erro ao carregar dados do usuário');
                        });
                }
            }); 4z"/>
                    </svg>
                    Novo Usuário
                </button>
                <a href="{{ route('empresa.settings') }}" class="btn btn-secondary">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                        <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.292-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.292c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                    </svg>
                    Configurações
                </a>
            </div>
        </div>

        <!-- Filtros e Estatísticas -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Resumo dos Usuários</h3>
            </div>
            
            <div class="user-stats">
                <div class="stat-item">
                    <div class="stat-icon bg-primary">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $users->total() }}</div>
                        <div class="stat-label">Total de Usuários</div>
                    </div>
                </div>

                <div class="stat-item">
                    <div class="stat-icon bg-warning">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                            <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $users->where('role', 'admin')->count() }}</div>
                        <div class="stat-label">Administradores</div>
                    </div>
                </div>

                <div class="stat-item">
                    <div class="stat-icon bg-success">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $users->where('role', 'general_technician')->count() }}</div>
                        <div class="stat-label">Técnicos Gerais</div>
                    </div>
                </div>

                <div class="stat-item">
                    <div class="stat-icon bg-info">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $users->where('role', 'normal_technician')->count() }}</div>
                        <div class="stat-label">Técnicos Normais</div>
                    </div>
                </div>

                @if(isset($usuariosPendentes) && $usuariosPendentes->count() > 0)
                <div class="stat-item">
                    <div class="stat-icon bg-warning">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                        </svg>
                    </div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $usuariosPendentes->count() }}</div>
                        <div class="stat-label">Aguardando Aprovação</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Usuários Pendentes de Aprovação -->
        @if(isset($usuariosPendentes) && $usuariosPendentes->count() > 0)
        <div class="card mb-4">
            <div class="card-header bg-warning text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16" class="me-2">
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                        </svg>
                        Usuários Aguardando Aprovação
                    </h3>
                    <span class="badge bg-light text-dark">{{ $usuariosPendentes->count() }} pendente(s)</span>
                </div>
            </div>
            
            <div class="users-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>Papel</th>
                            <th>Categoria</th>
                            <th>Registrado</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuariosPendentes as $usuario)
                        <tr class="user-row">
                            <td>
                                <div class="user-info">
                                    <div class="user-avatar">
                                        <span>{{ strtoupper(substr($usuario->name, 0, 2)) }}</span>
                                    </div>
                                    <div class="user-details">
                                        <div class="user-name">{{ $usuario->name }}</div>
                                        <div class="user-email">{{ $usuario->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="role-badge role-{{ $usuario->role }}">
                                    @switch($usuario->role)
                                        @case('admin')
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                            </svg>
                                            Administrador
                                            @break
                                        @case('general_technician')
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                            </svg>
                                            Técnico Geral
                                            @break
                                        @case('normal_technician')
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                            </svg>
                                            Técnico Normal
                                            @break
                                    @endswitch
                                </span>
                            </td>
                            <td>
                                @if($usuario->category)
                                    <span class="category-tag">{{ $usuario->category->nome }}</span>
                                @else
                                    <span class="text-muted">Nenhuma</span>
                                @endif
                            </td>
                            <td>
                                <span class="date-text">{{ $usuario->created_at->format('d/m/Y') }}</span>
                                <span class="time-text">{{ $usuario->created_at->format('H:i') }}</span>
                            </td>
                            <td>
                                <span class="badge bg-warning">Pendente</span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <form action="{{ route('empresa.users.approve', $usuario->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Tem certeza que deseja aprovar este usuário?')"
                                                class="btn btn-sm btn-success" 
                                                title="Aprovar usuário">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                            </svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('empresa.users.reject', $usuario->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                onclick="return confirm('Tem certeza que deseja rejeitar este usuário?')"
                                                class="btn btn-sm btn-danger" 
                                                title="Rejeitar usuário">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Lista de Usuários -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Usuários da Empresa</h3>
            </div>

            @if($users->count() > 0)
                <div class="users-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <th>Papel</th>
                                <th>Categoria</th>
                                <th>Registrado</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="user-row">
                                <td>
                                    <div class="user-info">
                                        <div class="user-avatar">
                                            <span>{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                        </div>
                                        <div class="user-details">
                                            <div class="user-name">{{ $user->name }}</div>
                                            <div class="user-email">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="role-badge role-{{ $user->role }}">
                                        @switch($user->role)
                                            @case('admin')
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                                </svg>
                                                Administrador
                                                @break
                                            @case('general_technician')
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                </svg>
                                                Técnico Geral
                                                @break
                                            @case('normal_technician')
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                                </svg>
                                                Técnico Normal
                                                @break
                                        @endswitch
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            @if($user->category)
                                                <span class="category-tag">{{ $user->category->nome }}</span>
                                            @else
                                                <span class="text-muted">Nenhuma</span>
                                            @endif
                                        </div>
                                        @if($user->role === 'normal_technician')
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-secondary assign-category-btn" 
                                                    data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}"
                                                    data-current-category="{{ $user->category_id ?? '' }}"
                                                    title="Atribuir categoria">
                                                <svg width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708L4.5 15.207a.5.5 0 0 1-.146.146l-3.5 2a.5.5 0 0 1-.708-.708l2-3.5a.5.5 0 0 1 .146-.146L13.646.854a.5.5 0 0 1 .5-.708zm-11.4 11.4L2.5 14.5 5 12.5l-3.854-3.854z"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="date-text">{{ $user->created_at->format('d/m/Y') }}</span>
                                    <span class="time-text">{{ $user->created_at->format('H:i') }}</span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-sm btn-secondary edit-user-btn" 
                                                data-user-id="{{ $user->id }}"
                                                title="Editar usuário">
                                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708L4.5 15.207a.5.5 0 0 1-.146.146l-3.5 2a.5.5 0 0 1-.708-.708l2-3.5a.5.5 0 0 1 .146-.146L13.646.854a.5.5 0 0 1 .5-.708zm-11.4 11.4L2.5 14.5 5 12.5l-3.854-3.854z"/>
                                            </svg>
                                        </button>
                                        @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" 
                                              onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')" 
                                              style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Excluir usuário">
                                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                                                    <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5ZM11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84L13.962 3.5H14.5a.5.5 0 0 0 0-1h-1.004a.58.58 0 0 0-.01 0H11Zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5h9.916Zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5Z"/>
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                @if($users->hasPages())
                    <div class="pagination">
                        {{ $users->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <svg width="64" height="64" fill="var(--color-gray-400)" viewBox="0 0 16 16" style="margin-bottom: 20px; opacity: 0.5;">
                        <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                    </svg>
                    <h3>Nenhum usuário encontrado</h3>
                    <p class="text-muted">Comece criando o primeiro usuário da empresa.</p>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newUserModal">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Criar Primeiro Usuário
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Novo Usuário -->
    <div class="modal" id="newUserModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Novo Usuário</h4>
                    <button type="button" class="modal-close" data-dismiss="modal">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name" class="form-label required">Nome</label>
                            <input type="text" name="name" id="name" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label required">Email</label>
                            <input type="email" name="email" id="email" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="role" class="form-label required">Papel</label>
                            <select name="role" id="role" class="form-select" required onchange="toggleCategoryField()">
                                <option value="">Selecione o papel</option>
                                <option value="admin">Administrador</option>
                                <option value="general_technician">Técnico Geral</option>
                                <option value="normal_technician">Técnico Normal</option>
                            </select>
                        </div>

                        <div class="form-group" id="category-field" style="display: none;">
                            <label for="category_id" class="form-label required">Categoria</label>
                            <select name="category_id" id="category_id" class="form-select">
                                <option value="">Selecione a categoria</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="password" class="form-label required">Senha</label>
                            <input type="password" name="password" id="password" class="form-input" required 
                                   minlength="8" 
                                   placeholder="Mínimo 8 caracteres">
                            <div id="password-feedback" class="mt-1 text-sm"></div>
                            <div id="password-strength" class="mt-2" style="display: none;">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div id="strength-bar" class="h-2 rounded-full transition-all duration-300" style="width: 0%;"></div>
                                </div>
                                <div id="strength-text" class="text-xs mt-1"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="form-label required">Confirmar Senha</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" required 
                                   minlength="8" 
                                   placeholder="Confirme a senha">
                            <div id="password-match-feedback" class="mt-1 text-sm"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Criar Usuário</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuário -->
    <div class="modal" id="editUserModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Editar Usuário</h4>
                    <button type="button" class="modal-close" data-dismiss="modal">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                        </svg>
                    </button>
                </div>
                <form method="POST" id="editUserForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name" class="form-label required">Nome</label>
                            <input type="text" name="name" id="edit_name" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_email" class="form-label required">Email</label>
                            <input type="email" name="email" id="edit_email" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_role" class="form-label required">Papel</label>
                            <select name="role" id="edit_role" class="form-select" required onchange="toggleEditCategoryField()">
                                <option value="admin">Administrador</option>
                                <option value="general_technician">Técnico Geral</option>
                                <option value="normal_technician">Técnico Normal</option>
                            </select>
                        </div>

                        <div class="form-group" id="edit-category-field">
                            <label for="edit_category_id" class="form-label">Categoria</label>
                            <select name="category_id" id="edit_category_id" class="form-select">
                                <option value="">Nenhuma categoria</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_password" class="form-label">Nova Senha (deixe em branco para manter)</label>
                            <input type="password" name="password" id="edit_password" class="form-input" 
                                   minlength="8" 
                                   placeholder="Mínimo 8 caracteres (opcional)">
                            <div id="edit-password-feedback" class="mt-1 text-sm"></div>
                            <div id="edit-password-strength" class="mt-2" style="display: none;">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div id="edit-strength-bar" class="h-2 rounded-full transition-all duration-300" style="width: 0%;"></div>
                                </div>
                                <div id="edit-strength-text" class="text-xs mt-1"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="edit_password_confirmation" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" name="password_confirmation" id="edit_password_confirmation" class="form-input" 
                                   minlength="8" 
                                   placeholder="Confirme a nova senha (se alterada)">
                            <div id="edit-password-match-feedback" class="mt-1 text-sm"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Atribuir Categoria -->
    <div class="modal" id="assignCategoryModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Atribuir Categoria</h4>
                    <button type="button" class="modal-close" data-dismiss="modal">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                        </svg>
                    </button>
                </div>
                <form id="assignCategoryForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Selecione a categoria para o usuário <strong id="assignCategoryUserName"></strong>:</p>
                        
                        <div class="form-group">
                            <label for="assign_category_id" class="form-label">Categoria</label>
                            <select name="category_id" id="assign_category_id" class="form-select">
                                <option value="">Nenhuma categoria</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Atribuir Categoria</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .user-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 0;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            background: var(--color-gray-50);
            border-radius: var(--radius-md);
            border: 1px solid var(--color-gray-200);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .stat-icon.bg-primary { background: var(--color-primary); }
        .stat-icon.bg-success { background: var(--color-success); }
        .stat-icon.bg-info { background: var(--color-info); }
        .stat-icon.bg-warning { background: var(--color-warning); }

        .stat-content {
            flex: 1;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: var(--color-gray-900);
            line-height: 1;
        }

        .stat-label {
            font-size: 14px;
            color: var(--color-gray-600);
            margin-top: 4px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid var(--color-gray-200);
        }

        .table th {
            background: var(--color-gray-50);
            font-weight: 600;
            color: var(--color-gray-700);
            font-size: 14px;
        }

        .user-row:hover {
            background: var(--color-gray-50);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: var(--color-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .user-name {
            font-weight: 600;
            color: var(--color-gray-900);
            margin-bottom: 2px;
        }

        .user-email {
            font-size: 14px;
            color: var(--color-gray-600);
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 500;
        }

        .role-admin {
            background: var(--color-warning-light);
            color: var(--color-warning);
        }

        .role-general_technician {
            background: var(--color-success-light);
            color: var(--color-success);
        }

        .role-normal_technician {
            background: var(--color-info-light);
            color: var(--color-info);
        }

        .category-tag {
            display: inline-block;
            padding: 4px 8px;
            background: var(--color-primary-light);
            color: var(--color-primary);
            border-radius: var(--radius-sm);
            font-size: 12px;
        }

        .date-text {
            display: block;
            font-weight: 500;
            color: var(--color-gray-900);
        }

        .time-text {
            display: block;
            font-size: 12px;
            color: var(--color-gray-600);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal.show {
            display: flex;
        }

        .modal-dialog {
            background: white;
            border-radius: var(--radius-lg);
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid var(--color-gray-200);
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--color-gray-900);
            margin: 0;
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--color-gray-500);
            cursor: pointer;
            padding: 4px;
            border-radius: var(--radius-sm);
            transition: background-color 0.2s;
        }

        .modal-close:hover {
            background: var(--color-gray-100);
            color: var(--color-gray-700);
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 20px;
            border-top: 1px solid var(--color-gray-200);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state h3 {
            margin-bottom: 12px;
            color: var(--color-gray-700);
        }

        /* Password strength indicators */
        #password-strength,
        #edit-password-strength {
            margin-top: 8px;
        }
        
        #password-feedback,
        #edit-password-feedback,
        #password-match-feedback,
        #edit-password-match-feedback {
            font-size: 12px;
            font-weight: 500;
            margin-top: 4px;
        }
        
        .w-full {
            width: 100%;
        }
        
        .bg-gray-200 {
            background-color: #e5e7eb;
        }
        
        .rounded-full {
            border-radius: 9999px;
        }
        
        .h-2 {
            height: 8px;
        }
        
        .transition-all {
            transition: all 0.3s ease;
        }
        
        .duration-300 {
            transition-duration: 300ms;
        }
        
        .text-xs {
            font-size: 11px;
        }
        
        .mt-1 {
            margin-top: 4px;
        }
        
        .mt-2 {
            margin-top: 8px;
        }
    </style>

    <script>
        // Password validation functions
        function checkPasswordStrength(password) {
            let strength = 0;
            let feedback = [];
            
            if (password.length >= 8) {
                strength += 25;
            } else {
                feedback.push('Mínimo 8 caracteres');
            }
            
            if (/[a-z]/.test(password)) {
                strength += 25;
            } else {
                feedback.push('letras minúsculas');
            }
            
            if (/[A-Z]/.test(password)) {
                strength += 25;
            } else {
                feedback.push('letras maiúsculas');
            }
            
            if (/[0-9]/.test(password)) {
                strength += 25;
            } else {
                feedback.push('números');
            }
            
            return { strength, feedback };
        }

        function updatePasswordStrength(password, strengthBarId, strengthTextId, strengthContainerId) {
            const result = checkPasswordStrength(password);
            const strengthBar = document.getElementById(strengthBarId);
            const strengthText = document.getElementById(strengthTextId);
            const strengthContainer = document.getElementById(strengthContainerId);
            
            if (password.length > 0) {
                strengthContainer.style.display = 'block';
                strengthBar.style.width = result.strength + '%';
                
                if (result.strength < 50) {
                    strengthBar.style.backgroundColor = '#ef4444'; // red
                    strengthText.textContent = 'Fraca - Precisa: ' + result.feedback.join(', ');
                    strengthText.style.color = '#ef4444';
                } else if (result.strength < 75) {
                    strengthBar.style.backgroundColor = '#f59e0b'; // yellow
                    strengthText.textContent = 'Média - Precisa: ' + result.feedback.join(', ');
                    strengthText.style.color = '#f59e0b';
                } else {
                    strengthBar.style.backgroundColor = '#10b981'; // green
                    strengthText.textContent = 'Forte - Senha segura!';
                    strengthText.style.color = '#10b981';
                }
            } else {
                strengthContainer.style.display = 'none';
            }
            
            return result.strength >= 50; // Mínimo 50% de força
        }

        function validatePasswordMatch(password, confirmation, feedbackId) {
            const feedback = document.getElementById(feedbackId);
            
            if (confirmation.length > 0) {
                if (password === confirmation) {
                    feedback.textContent = '✓ Senhas coincidem';
                    feedback.style.color = '#10b981';
                    return true;
                } else {
                    feedback.textContent = '✗ Senhas não coincidem';
                    feedback.style.color = '#ef4444';
                    return false;
                }
            } else {
                feedback.textContent = '';
                return password.length === 0;
            }
        }

        function validatePasswordRequirements(password, feedbackId) {
            const feedback = document.getElementById(feedbackId);
            
            if (password.length === 0) {
                feedback.textContent = '';
                return false;
            }
            
            if (password.length < 8) {
                feedback.textContent = '✗ A senha deve ter pelo menos 8 caracteres';
                feedback.style.color = '#ef4444';
                return false;
            }
            
            feedback.textContent = '✓ Senha atende aos requisitos mínimos';
            feedback.style.color = '#10b981';
            return true;
        }

        function toggleCategoryField() {
            const roleSelect = document.getElementById('role');
            const categoryField = document.getElementById('category-field');
            const categorySelect = document.getElementById('category_id');
            
            if (roleSelect.value === 'normal_technician') {
                categoryField.style.display = 'block';
                categorySelect.required = true;
            } else {
                categoryField.style.display = 'none';
                categorySelect.required = false;
                categorySelect.value = '';
            }
        }

        function toggleEditCategoryField() {
            const roleSelect = document.getElementById('edit_role');
            const categoryField = document.getElementById('edit-category-field');
            const categorySelect = document.getElementById('edit_category_id');
            
            if (roleSelect.value === 'normal_technician') {
                categoryField.style.display = 'block';
            } else {
                categoryField.style.display = 'none';
                categorySelect.value = '';
            }
        }

        // Modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Password validation for new user modal
            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');
            const newUserForm = document.querySelector('#newUserModal form');
            
            passwordInput.addEventListener('input', function() {
                updatePasswordStrength(this.value, 'strength-bar', 'strength-text', 'password-strength');
                validatePasswordRequirements(this.value, 'password-feedback');
                if (passwordConfirmationInput.value) {
                    validatePasswordMatch(this.value, passwordConfirmationInput.value, 'password-match-feedback');
                }
            });
            
            passwordConfirmationInput.addEventListener('input', function() {
                validatePasswordMatch(passwordInput.value, this.value, 'password-match-feedback');
            });
            
            // Password validation for edit user modal
            const editPasswordInput = document.getElementById('edit_password');
            const editPasswordConfirmationInput = document.getElementById('edit_password_confirmation');
            const editUserForm = document.getElementById('editUserForm');
            
            editPasswordInput.addEventListener('input', function() {
                if (this.value.length > 0) {
                    updatePasswordStrength(this.value, 'edit-strength-bar', 'edit-strength-text', 'edit-password-strength');
                    validatePasswordRequirements(this.value, 'edit-password-feedback');
                    if (editPasswordConfirmationInput.value) {
                        validatePasswordMatch(this.value, editPasswordConfirmationInput.value, 'edit-password-match-feedback');
                    }
                } else {
                    document.getElementById('edit-password-strength').style.display = 'none';
                    document.getElementById('edit-password-feedback').textContent = '';
                }
            });
            
            editPasswordConfirmationInput.addEventListener('input', function() {
                if (editPasswordInput.value.length > 0 || this.value.length > 0) {
                    validatePasswordMatch(editPasswordInput.value, this.value, 'edit-password-match-feedback');
                } else {
                    document.getElementById('edit-password-match-feedback').textContent = '';
                }
            });
            
            // Form validation before submit
            newUserForm.addEventListener('submit', function(e) {
                const password = passwordInput.value;
                const passwordConfirmation = passwordConfirmationInput.value;
                
                if (password.length < 8) {
                    e.preventDefault();
                    alert('A senha deve ter pelo menos 8 caracteres.');
                    passwordInput.focus();
                    return false;
                }
                
                if (password !== passwordConfirmation) {
                    e.preventDefault();
                    alert('As senhas não coincidem.');
                    passwordConfirmationInput.focus();
                    return false;
                }
                
                const result = checkPasswordStrength(password);
                if (result.strength < 50) {
                    e.preventDefault();
                    alert('A senha é muito fraca. Por favor, use uma senha mais forte que inclua: ' + result.feedback.join(', '));
                    passwordInput.focus();
                    return false;
                }
            });
            
            editUserForm.addEventListener('submit', function(e) {
                const password = editPasswordInput.value;
                const passwordConfirmation = editPasswordConfirmationInput.value;
                
                // Se senha foi informada, validar
                if (password.length > 0) {
                    if (password.length < 8) {
                        e.preventDefault();
                        alert('A nova senha deve ter pelo menos 8 caracteres.');
                        editPasswordInput.focus();
                        return false;
                    }
                    
                    if (password !== passwordConfirmation) {
                        e.preventDefault();
                        alert('As senhas não coincidem.');
                        editPasswordConfirmationInput.focus();
                        return false;
                    }
                    
                    const result = checkPasswordStrength(password);
                    if (result.strength < 50) {
                        e.preventDefault();
                        alert('A senha é muito fraca. Por favor, use uma senha mais forte que inclua: ' + result.feedback.join(', '));
                        editPasswordInput.focus();
                        return false;
                    }
                }
            });
            // Handle assign category buttons
            document.addEventListener('click', function(e) {
                if (e.target.closest('.assign-category-btn')) {
                    const button = e.target.closest('.assign-category-btn');
                    const userId = button.dataset.userId;
                    const userName = button.dataset.userName;
                    const currentCategory = button.dataset.currentCategory;
                    
                    // Atualizar o modal
                    document.getElementById('assignCategoryUserName').textContent = userName;
                    document.getElementById('assign_category_id').value = currentCategory;
                    document.getElementById('assignCategoryForm').action = `/empresa/users/${userId}/assign-category`;
                    
                    // Mostrar o modal
                    document.getElementById('assignCategoryModal').classList.add('show');
                }
            });

            // Handle edit user buttons
            document.addEventListener('click', function(e) {
                if (e.target.closest('.edit-user-btn')) {
                    const userId = e.target.closest('.edit-user-btn').dataset.userId;
                    
                    // Buscar dados do usuário via AJAX
                    fetch(`/empresa/users/${userId}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            // Preencher o formulário com os dados do usuário
                            document.getElementById('edit_name').value = data.name;
                            document.getElementById('edit_email').value = data.email;
                            document.getElementById('edit_role').value = data.role;
                            document.getElementById('edit_category_id').value = data.category_id || '';
                            
                            // Configurar a action do formulário para a rota correta
                            document.getElementById('editUserForm').action = `/users/${userId}`;
                            
                            // Mostrar/ocultar campo de categoria baseado no papel
                            toggleEditCategoryField();
                            
                            // Mostrar o modal
                            document.getElementById('editUserModal').classList.add('show');
                        })
                        .catch(error => {
                            console.error('Erro ao buscar dados do usuário:', error);
                            alert('Erro ao carregar dados do usuário');
                        });
                }
            });

            // Open modals
            document.querySelectorAll('[data-toggle="modal"]').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const modal = document.querySelector(targetId);
                    if (modal) {
                        modal.classList.add('show');
                    }
                });
            });

            // Close modals
            document.querySelectorAll('[data-dismiss="modal"]').forEach(button => {
                button.addEventListener('click', function() {
                    const modal = this.closest('.modal');
                    if (modal) {
                        modal.classList.remove('show');
                    }
                });
            });

            // Close modal on background click
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.remove('show');
                    }
                });
            });
        });
    </script>
</x-app-layout> 