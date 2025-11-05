<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="mb-4">Estatísticas (Tempo Real - Livewire)</h3>
                    
                    @livewire('admin.stats-widget')

                    <h3 class="mb-4 mt-5">Gerenciamento de Usuários</h3>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary mb-3">Gerenciar Usuários</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
