<?php

namespace Tests\Feature\Admin;

use App\Imports\UsersImport;
use App\Jobs\ImportUsersJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Http\UploadedFile; 

class ImportTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($this->admin);
    }

    public function test_import_job_is_queued(): void
    {
        Queue::fake();
        Storage::fake('local');
        
        $file = UploadedFile::fake()->create('users.xlsx', 10);

        \Livewire::test(\App\Livewire\Admin\UserImport::class)
            ->set('upload', $file)
            ->call('import');

        Queue::assertPushed(ImportUsersJob::class);
    }

    public function test_users_are_created_from_import_file(): void
    {
        Storage::fake('local');

        $content = "full_name,email,role,avatar_image\n"; 
        $content .= "John Doe,john@example.com,admin,http://avatar.com/john.png\n";
        $content .= "Jane Smith,jane@example.com,no-admin,http://avatar.com/jane.png";

        $file = UploadedFile::fake()->create('users.csv', $content);

        Excel::import(new UsersImport, $file);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com', 'role' => 'admin', 'full_name' => 'John Doe']);
        $this->assertDatabaseHas('users', ['email' => 'jane@example.com', 'role' => 'no-admin']);
    }

    public function test_import_validation_handles_missing_fields(): void
    {
        Storage::fake('local');

        $content = "full_name,email,role,avatar_image\n";
        $content .= "Missing Email,,admin,";
        
        $file = UploadedFile::fake()->create('invalid.csv', $content);
        
        try {
            Excel::import(new UsersImport, $file);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $this->assertCount(1, $e->failures());
        }

        $this->assertDatabaseMissing('users', ['full_name' => 'Missing Email']);
    }
}