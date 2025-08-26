<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required',
                'email' => 'required|email|unique:User,email',
                'kata_sandi' => 'required|min:6',
                'peran' => 'required'
            ]);

            User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'kata_sandi' => Hash::make($request->kata_sandi),
                'peran' => $request->peran
            ]);

            return redirect()->back()->with('success', 'User berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan User: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $user->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'peran' => $request->peran,
            ]);

            return redirect()->back()->with('success', 'User berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui User: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->back()->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus User: ' . $e->getMessage());
        }
    }
}
