<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        Importar Usuários via Planilha (CSV/XLSX)
    </div>
    <div class="card-body">

        @if (session()->has('import-success'))
            <div class="alert alert-success">
                {{ session('import-success') }}
            </div>
        @endif

        <form wire:submit="importFile" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="file" class="form-label">Arquivo de Importação (.csv, .xlsx)</label>
                <input class="form-control" type="file" id="file" wire:model="file" accept=".csv, .xlsx">
                @error('file') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-success" wire:loading.attr="disabled" wire:target="file">
                <span wire:loading wire:target="importFile" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                <span wire:loading.remove wire:target="importFile">Iniciar Importação</span>
                <span wire:loading wire:target="importFile">Processando...</span>
            </button>
        </form>

        <hr class="my-4">

        <h6 class="mb-3">Progresso da Importação em Tempo Real</h6>

        <div class="progress mb-3" role="progressbar" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" style="width: {{ $progress }}%">{{ $progress }}%</div>
        </div>

        @if ($importStatus)
            <div class="alert alert-{{ $importStatus === 'Finished' ? 'success' : 'info' }}">
                Status Atual: {{ $importStatus }}
            </div>
        @endif
        
    </div>
</div>