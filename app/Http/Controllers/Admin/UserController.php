<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
    {
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'avatar_image' => ['nullable', 'url'], 
            'role' => ['required', Rule::in(['admin', 'no-admin'])],
        ]);

        User::create([
            'name' => $data['full_name'], 
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'avatar_image' => $data['avatar_image'] ?? 'https://ui-avatars.com/api/?name='.urlencode($data['full_name']),
            'role' => $data['role'],
        ]);

        return redirect()->route('admin.users.index')->with('status', 'Usuário criado com sucesso!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'avatar_image' => ['nullable', 'url'],
            'role' => ['required', Rule::in(['admin', 'no-admin'])],
        ]);

        $user->fill([
            'name' => $data['full_name'],
            'full_name' => $data['full_name'],
            'email' => $data['email'],
            'avatar_image' => $data['avatar_image'] ?? 'https://ui-avatars.com/api/?name='.urlencode($data['full_name']),
            'role' => $data['role'],
        ]);

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        
        $user->save();

        
        $this->dispatch('user-updated');

        return redirect()->route('admin.users.index')->with('status', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        
        $this->dispatch('user-updated');

        return redirect()->route('admin.users.index')->with('status', 'Usuário excluído com sucesso!');
    }

    
    public function toggleRole(User $user)
    {
        $user->role = ($user->role === 'admin') ? 'no-admin' : 'admin';
        $user->save();
        $this->dispatch('user-updated');
        return back()->with('status', 'Função do usuário alterada para ' . $user->role);
    }
}
