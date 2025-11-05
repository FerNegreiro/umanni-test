<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // <--- 1. IMPORTE A CLASSE

class UsersImport implements ToCollection, WithHeadingRow // <--- 2. ADICIONE A INTERFACE
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            
            // Esta parte agora vai funcionar corretamente.
            // Ela vai "limpar" os valores (com trim()) e 
            // garantir que as chaves estão em formato 'slug'
            $row = collect($row)->mapWithKeys(function ($value, $key) {
                return [Str::slug(trim($key), '_') => trim($value)];
            });

            // O resto da sua lógica está perfeita
            $fullName = $row['full_name'] ?? $row['name'] ?? null;
            $email = $row['email'] ?? null;

            
            if (!$fullName || !$email) {
                continue;
            }

            
            $role = Str::lower($row['role'] ?? 'no-admin');
            if (!in_array($role, ['admin', 'no-admin'])) {
                $role = 'no-admin';
            }

            
            User::firstOrCreate(
                ['email' => strtolower($email)],
                [
                    'name' => $fullName,
                    'full_name' => $fullName,
                    'password' => Hash::make(Str::random(10)),
                    'avatar_image' => $row['avatar_image'] 
                        ?? 'https://ui-avatars.com/api/?name=' . urlencode($fullName),
                    'role' => $role,
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}