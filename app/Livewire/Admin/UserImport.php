<?php

namespace App\Livewire\Admin;

use App\Jobs\ImportUsersJob;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserImport extends Component
{
    use WithFileUploads;

    public $upload;
    public $importing = false;
    public $importId;
    public $importFinished = false;
    public $importSuccess = false;
    public $importMessage = '';
    public $progress = 0;
    
    /**
     * Adicionada para corrigir o erro 'Undefined variable $importStatus' na view.
     */
    public $importStatus = 'Pendente'; 

    protected $rules = [
        'upload' => 'required|file|mimes:xlsx,xls,csv|max:10240',
    ];

    public function import()
    {
        $this->validate();

        $this->importing = true;
        $this->importFinished = false;
        $this->progress = 0;
        $this->importStatus = 'Iniciando...'; // Pode atualizar o status aqui
        $this->importId = now()->timestamp;

        $filePath = $this->upload->storeAs('imports', $this->importId . '.csv');

        ImportUsersJob::dispatch(storage_path('app/' . $filePath), $this->importId);

        $this->reset('upload');
    }

    #[On('echo:users-import,UserImportFinished')]
    public function importFinished($event)
    {
        if ($event['importId'] == $this->importId) {
            $this->importing = false;
            $this->importFinished = true;
            $this->importSuccess = $event['success'];
            $this->importMessage = $event['message'];
            $this->importStatus = $event['success'] ? 'Concluído' : 'Falhou'; // Pode atualizar o status aqui
        }
    }

    public function getListeners()
    {
        return [
            "echo:users-import,UserImportFinished" => "importFinished",
        ];
    }

    public function render()
    {
        return view('livewire.admin.user-import');
    }
}

