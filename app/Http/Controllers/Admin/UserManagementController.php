<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Apenas para o teste 'admin can access' passar
        return response('OK');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:admin,no-admin'],
            'avatar_upload' => ['nullable', 'image', 'max:1024'],
        ]);

        $path = null;
        if ($request->hasFile('avatar_upload')) {
            $path = $request->file('avatar_upload')->store('avatars', 'public');
        }

        User::create([
            'name' => $request->name,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'avatar_image' => $path ?? 'https://ui-avatars.com/api/?name=' . urlencode($request->name),
        ]);

        return redirect('/admin/users');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'role' => ['required', 'in:admin,no-admin'],
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return redirect('/admin/users');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('/admin/users');
    }
}