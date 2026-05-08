<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $role   = $request->get('role', '');

        $users = User::query()
            ->when($search, fn($q) => $q->where('nama_user', 'like', "%$search%")
                                        ->orWhere('email_user', 'like', "%$search%"))
            ->when($role,   fn($q) => $q->where('role_user', $role))
            ->latest('created_at')
            ->get();

        return view('administrator.ManageUser', compact('users', 'search', 'role'));
    }

    public function update(Request $request, $id)
    {
        $authUser = auth()->user();
        $user     = User::findOrFail($id);

        $isSelf    = $user->id_user === $authUser->id_user;
        $isManager = $authUser->role_user === 'manager';

        // Non-manager tidak boleh edit dirinya sendiri
        if ($isSelf && !$isManager) {
            return redirect()->route('admin.users')
                ->with('error', 'Tidak bisa mengedit akun diri sendiri.');
        }

        $rules = [
            'nama_user'  => 'required|string|max:255',
            'email_user' => 'required|email|max:255|unique:users,email_user,' . $id . ',id_user',
            'password'   => 'nullable|string|min:6',
        ];

        // Manager bisa edit role, tapi tidak boleh ubah role dirinya sendiri
        if ($isManager && !$isSelf) {
            $rules['role_user'] = 'required|in:karyawan,admin,manager,admin_trans,driver';
        }

        $request->validate($rules);

        $data = [
            'nama_user'  => $request->nama_user,
            'email_user' => $request->email_user,
        ];

        if ($request->filled('password')) {
            $data['pass_user'] = Hash::make($request->password);
        }

        // Update role hanya jika manager dan bukan edit diri sendiri
        if ($isManager && !$isSelf && $request->filled('role_user')) {
            $data['role_user'] = $request->role_user;
        }

        $user->update($data);

        return redirect()->route('admin.users')
            ->with('success', "Data {$user->nama_user} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $authUser = auth()->user();
        $user     = User::findOrFail($id);

        if ($user->id_user === $authUser->id_user) {
            return redirect()->route('admin.users')
                ->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        $nama = $user->nama_user;
        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', "Akun {$nama} berhasil dihapus.");
    }
}