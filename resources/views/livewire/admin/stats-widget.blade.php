<div wire:poll.10s="loadStats" class="container">
    {{-- Contadores do Dashboard --}}
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-primary">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total de Usuários</h5>
                    <p class="card-text fs-1 fw-bold">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-success">
                <div class="card-body">
                    <h5 class="card-title text-success">Administradores</h5>
                    <p class="card-text fs-1 fw-bold">{{ $adminCount }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-secondary">
                <div class="card-body">
                    <h5 class="card-title text-secondary">Usuários Comuns</h5>
                    <p class="card-text fs-1 fw-bold">{{ $nonAdminCount }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Progresso de Importação (Tempo Real) --}}
    @if ($isImporting)
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="alert alert-info shadow-sm" role="alert">
                    <h5 class="alert-heading">Importação em Andamento...</h5>
                    <p>Processando planilha de usuários.</p>
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                            style="width: {{ $importProgress }}%;" 
                            aria-valuenow="{{ $importProgress }}" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                            {{ $importProgress }}% Concluído
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
