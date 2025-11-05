<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class StatsWidget extends Component
{
    public $totalUsers = 0;
    public $adminCount = 0;
    public $nonAdminCount = 0;
    public $importProgress = 0;
    public $isImporting = false;

    
    protected $listeners = [
        'user-updated' => 'loadStats',
        'echo:users-import,UserImportFinished' => 'handleImportFinished',
    ];

    public function mount()
    {
        $this->loadStats();
        $this->loadImportStatus();
    }

    public function loadStats()
    {
        $this->totalUsers = User::count();
        $this->adminCount = User::where('role', 'admin')->count();
        $this->nonAdminCount = User::where('role', 'no-admin')->count();
    }

    public function loadImportStatus()
    {
        $this->isImporting = Cache::get('import_status', 'idle') === 'processing';
        $this->importProgress = Cache::get('import_progress', 0);
    }

    
    public function handleImportFinished()
    {
        $this->loadStats();
        $this->loadImportStatus();
    }

    public function render()
    {
        return view('livewire.admin.stats-widget');
    }
}
