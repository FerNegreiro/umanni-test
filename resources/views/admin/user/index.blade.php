<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gerenciamento de Usuários
        </h2>
    </x-slot>

    <div class="container py-4">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Usuários</h5>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                    Criar Novo Usuário
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Avatar</th>
                                <th scope="col">Nome Completo</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Função</th>
                                <th scope="col">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <img src="{{ $user->avatar_image ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name) }}" 
                                             class="rounded-circle" width="40" height="40" alt="Avatar">
                                    </td>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-success' }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-info btn-sm me-2">Editar</a>
                                        
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este usuário?');">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</x-app-layout>
