<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'users' => User::with('roles')->orderBy('id')->paginate(15),
            'roles' => Role::orderBy('name')->pluck('name'),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'role'     => ['required', Rule::in(['admin', 'petugas', 'siswa'])],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->assignRole($data['role']);

        return back()->with('success', 'Pengguna dibuat.');
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['admin', 'petugas', 'siswa'])],
        ]);

        abort_if($user->id === auth()->id(), 403, 'Tidak boleh mengubah role sendiri.');

        $user->syncRoles($data['role']);

        return back()->with('success', 'Role diperbarui.');
    }

    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'Tidak boleh menghapus akun sendiri.');
        $user->delete();
        return back()->with('success', 'Pengguna dihapus.');
    }
}
